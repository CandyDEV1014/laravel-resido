<?php

namespace Botble\RealEstate\Http\Controllers;

use Assets;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Media\Chunks\Exceptions\UploadMissingFileException;
use Botble\Media\Chunks\Handler\DropZoneUploadHandler;
use Botble\Media\Chunks\Receiver\FileReceiver;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Services\ThumbnailService;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Repositories\Interfaces\PaymentInterface;
use Botble\Payment\Services\Gateways\PayPalPaymentService;
use Botble\RealEstate\Http\Requests\AvatarRequest;
use Botble\RealEstate\Http\Requests\SettingRequest;
use Botble\RealEstate\Http\Requests\UpdatePasswordRequest;
use Botble\RealEstate\Http\Resources\AccountResource;
use Botble\RealEstate\Http\Resources\ActivityLogResource;
use Botble\RealEstate\Http\Resources\PackageResource;
use Botble\RealEstate\Http\Resources\TransactionResource;
use Botble\RealEstate\Models\Package;
use Botble\RealEstate\Repositories\Interfaces\AccountActivityLogInterface;
use Botble\RealEstate\Repositories\Interfaces\AccountInterface;
use Botble\RealEstate\Repositories\Interfaces\AccountPackageInterface;
use Botble\RealEstate\Repositories\Interfaces\PackageInterface;
use Botble\RealEstate\Repositories\Interfaces\TransactionInterface;
use EmailHandler;
use Exception;
use File;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Botble\Base\Enums\BaseStatusEnum;
use RvMedia;
use SeoHelper;
use Theme;
use RealEstateHelper;
use Botble\RealEstate\Models\Review;

class PublicAccountController extends Controller
{
    /**
     * @var AccountInterface
     */
    protected $accountRepository;

    /**
     * @var AccountActivityLogInterface
     */
    protected $activityLogRepository;

    /**
     * @var MediaFileInterface
     */
    protected $fileRepository;

    /**
     * @var AccountPackageInterface
     */
    protected $accountPackageRepository;

    /**
     * PublicController constructor.
     * @param Repository $config
     * @param AccountInterface $accountRepository
     * @param AccountActivityLogInterface $accountActivityLogRepository
     * @param MediaFileInterface $fileRepository
     * @param AccountPackageInterface $accountPackageRepository
     */
    public function __construct(
        Repository $config,
        AccountInterface $accountRepository,
        AccountActivityLogInterface $accountActivityLogRepository,
        MediaFileInterface $fileRepository,
        AccountPackageInterface $accountPackageRepository
    )
    {
        $this->accountRepository = $accountRepository;
        $this->activityLogRepository = $accountActivityLogRepository;
        $this->fileRepository = $fileRepository;
        $this->accountPackageRepository = $accountPackageRepository;

        Assets::setConfig($config->get('plugins.real-estate.assets'));
    }

    /**
     * @return Application|Factory|\Illuminate\Contracts\View\View|JsonResponse|View|\Response
     */
    public function getDashboard()
    {
        SeoHelper::setTitle(auth('account')->user()->name);
        Assets::addScriptsDirectly('vendor/core/plugins/real-estate/js/components.js');

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.dashboard.index'))) {
            return Theme::scope('real-estate.account.dashboard.index')->render();
        }

        return view('plugins/real-estate::account.dashboard.index');
    }

    /**
     * @return Factory|View|\Response
     */
    public function getSettings()
    {
        SeoHelper::setTitle(trans('plugins/real-estate::account.account_settings'));

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.settings.index'))) {
            return Theme::scope('real-estate.account.settings.index')->render();
        }

        return view('plugins/real-estate::account.settings.index');
    }

    /**
     * @param SettingRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|RedirectResponse
     */
    public function postSettings(SettingRequest $request, BaseHttpResponse $response)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');

        if ($year && $month && $day) {
            $request->merge(['dob' => implode('-', [$year, $month, $day])]);

            $validator = Validator::make($request->input(), [
                'dob' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return redirect()->route('public.account.settings');
            }
        }

        $this->accountRepository->createOrUpdate($request->except('email'),
            ['id' => auth('account')->id()]);

        $this->activityLogRepository->createOrUpdate(['action' => 'update_setting']);

        return $response
            ->setNextUrl(route('public.account.settings'))
            ->setMessage(trans('plugins/real-estate::account.update_profile_success'));
    }

    /**
     * @return Factory|View|\Response
     */
    public function getSecurity()
    {
        SeoHelper::setTitle(trans('plugins/real-estate::account.security'));

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.settings.security'))) {
            return Theme::scope('real-estate.account.settings.security')->render();
        }

        return view('plugins/real-estate::account.settings.security');
    }

    /**
     * @return Factory|View|\Response
     */
    public function getPackages()
    {
        if (!RealEstateHelper::isEnabledCreditsSystem()) {
            abort(404);
        }

        SeoHelper::setTitle(trans('plugins/real-estate::account.packages'));

        Assets::addScriptsDirectly('vendor/core/plugins/real-estate/js/components.js');

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.settings.package'))) {
            return Theme::scope('real-estate.account.settings.package')->render();
        }

        return view('plugins/real-estate::account.settings.package');
    }

    /**
     * @return Factory|View
     */
    public function getTransactions()
    {
        if (!RealEstateHelper::isEnabledCreditsSystem()) {
            abort(404);
        }

        SeoHelper::setTitle(trans('plugins/real-estate::account.transactions'));

        Assets::addScriptsDirectly('vendor/core/plugins/real-estate/js/components.js');

        return view('plugins/real-estate::account.settings.transactions');
    }

    /**
     * @param PackageInterface $packageRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ajaxGetPackages(PackageInterface $packageRepository, BaseHttpResponse $response)
    {
        if (!RealEstateHelper::isEnabledCreditsSystem()) {
            abort(404);
        }

        $account = $this->accountRepository->findOrFail(auth('account')->id(),
            ['packages']);
        $packages = $packageRepository->advancedGet([
            'order_by' => ['order' => 'DESC'],
            'condition' => ['status' => BaseStatusEnum::PUBLISHED],
        ]);
        // $packages = $packages->filter(function ($package) use ($account) {
        //     return $package->account_limit === null || $account->packages->where('id',
        //             $package->id)->count() < $package->account_limit;
        // });

        return $response->setData([
            'packages' => PackageResource::collection($packages),
            'account'  => new AccountResource($account),
        ]);
    }

    /**
     * @param Request $request
     * @param PackageInterface $packageRepository
     * @param BaseHttpResponse $response
     */
    public function ajaxSubscribePackage(Request $request, PackageInterface $packageRepository, BaseHttpResponse $response, TransactionInterface $transactionRepository) 
	{
        if (!RealEstateHelper::isEnabledCreditsSystem()) {
            abort(404);
        }

        $package = $packageRepository->findOrFail($request->input('id'));
        $account = $this->accountRepository->findOrFail(auth('account')->id());

        $activePackage = $this->accountPackageRepository->getActivePackage(auth('account')->id());

        if (!empty($activePackage)) {
            if ($activePackage['package_id'] == $package->id) {
                return $response
                ->setError(true)
                ->setNextUrl(route('public.account.packages'))
                ->setMessage(__('You have already :package_name membership plan. Purchase another plan.', ['package_name' => $activePackage['name']]));
            }
        }
        // if ($package->account_limit && $account->packages()->where('package_id',
        //         $package->id)->count() >= $package->account_limit) {
        //     abort(403);
        // }

        if ((float)$package->price) {
            session(['subscribed_packaged_id' => $package->id]);

            return $response->setData(['next_page' => route('public.account.package.subscribe', $package->id)]);
        }

        $this->savePayment($package, null, $transactionRepository, true);

        return $response
            ->setData(['account' => new AccountResource($account->refresh()), 'dashboard_page' => route('public.account.dashboard')])
            ->setMessage(trans('plugins/real-estate::package.purchase_package_success'));
    }

    /**
     * @param Package $package
     * @param string|null $chargeId
     * @param TransactionInterface $transactionRepository
     * @param bool $force
     * @return bool
     */
    protected function savePayment(Package $package, ?string $chargeId, TransactionInterface $transactionRepository, bool $force = false)
    {
        if (!RealEstateHelper::isEnabledCreditsSystem()) {
            abort(404);
        }
        $payment = app(PaymentInterface::class)->getFirstBy(['charge_id' => $chargeId]);

        if (!$payment && !$force) {
            return false;
        }

        $account = auth('account')->user();
        
        if (($payment && $payment->status == PaymentStatusEnum::COMPLETED) || $force) {
            $account->credits += $package->credits;
            $account->save();

            app(AccountPackageInterface::class)->update(['account_id' => auth('account')->id()], [
                'status' => 0
            ]);
            
            app(AccountPackageInterface::class)->createOrUpdate([
                'account_id'   => auth('account')->id(),
                'package_id'   => $package->id,
                'expired_day'  => $package->number_of_days,
                'expired_date' => $package->number_of_days != -1 ? now()->addDays($package->number_of_days) : null,
                'status'       => 1
            ]);

            // $account->packages()->attach($package);
        }

        $transactionRepository->createOrUpdate([
            'user_id'    => 0,
            'account_id' => auth('account')->id(),
            'package_id' => $package->id,
            'credits'    => $package->credits,
            'payment_id' => $payment ? $payment->id : null,
        ]);
        
        $price = $package->is_promotion && strtotime($package->promotion_time) > strtotime('now') ? $package->promotion_price : $package->price;
        
        // if (!$price) {
        //     EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
        //         ->setVariableValues([
        //             'account_name'  => $account->name,
        //             'account_email' => $account->email,
        //         ])
        //         ->sendUsingTemplate('free-credit-claimed');
        // } else {
        //     EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
        //         ->setVariableValues([
        //             'account_name'     => $account->name,
        //             'account_email'    => $account->email,
        //             'package_name'     => $package->name,
        //             'package_price'    => format_price($package->price),
        //             'package_total'    => format_price($package->price) . ' for ' . $package->credits . ' credits',
        //         ])
        //         ->sendUsingTemplate('payment-received');
        // }
        

        // EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
        //     ->setVariableValues([
        //         'account_name'     => $account->name,
        //         'package_name'     => $package->name,
        //         'package_price'    => format_price($price),
        //         'package_total'    => format_price($price) . ' for ' . $package->credits . ' credits',
        //     ])
        //     ->sendUsingTemplate('payment-receipt', auth('account')->user()->email);

        return true;
    }

    /**
     * @param int $id
     * @param PackageInterface $packageRepository
     * @return Factory|View|\Response
     */
    public function getSubscribePackage($id, PackageInterface $packageRepository, BaseHttpResponse $response, TransactionInterface $transactionRepository)
    {
        if (!RealEstateHelper::isEnabledCreditsSystem()) {
            abort(404);
        }

        $package = $packageRepository->findOrFail($id);
        $account = $this->accountRepository->findOrFail(auth('account')->id());
        
        $activePackage = $this->accountPackageRepository->getActivePackage(auth('account')->id());

        if (!empty($activePackage)) {
            if ($activePackage['package_id'] == $package->id) {
                return $response
                ->setError(true)
                ->setNextUrl(route('public.account.packages'))
                ->setMessage(__('You have already :package_name membership plan. Purchase another plan.', ['package_name' => $activePackage['name']]));
            }
        }
        
        if ($package->price == 0) {
            $this->savePayment($package, null, $transactionRepository, true);

            return $response
                ->setData(new AccountResource($account->refresh()))
                ->setNextUrl(route('public.account.packages'))
                ->setMessage(trans('plugins/real-estate::package.purchase_package_success'));
        }

        SeoHelper::setTitle(trans('plugins/real-estate::package.subscribe_package', ['name' => $package->name]));

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.settings.security'))) {
            return Theme::scope('real-estate.account.checkout', compact('package'))->render();
        }
        return view('plugins/real-estate::account.checkout', compact('package'));
    }

    /**
     * @param int $packageId
     * @param Request $request
     * @param PayPalPaymentService $payPalService
     * @param \Botble\RealEstate\Repositories\Interfaces\PackageInterface $packageRepository
     * @param \Botble\RealEstate\Repositories\Interfaces\TransactionInterface $transactionRepository
     * @return BaseHttpResponse
     */
    public function getPackageSubscribeCallback(
        $packageId,
        Request $request,
        PayPalPaymentService $payPalService,
        PackageInterface $packageRepository,
        TransactionInterface $transactionRepository,
        BaseHttpResponse $response
    ) {
        if (!RealEstateHelper::isEnabledCreditsSystem()) {
            abort(404);
        }

        $package = $packageRepository->findOrFail($packageId);

        if ($request->input('type') == PaymentMethodEnum::PAYPAL) {
            $validator = Validator::make($request->input(), [
                'amount'   => 'required|numeric',
                'currency' => 'required',
            ]);

            if ($validator->fails()) {
                return $response->setError()->setMessage($validator->getMessageBag()->first());
            }

            $paymentStatus = $payPalService->getPaymentStatus($request);
            if ($paymentStatus) {
                $chargeId = session('paypal_payment_id');

                $payPalService->afterMakePayment($request);

                $this->savePayment($package, $chargeId, $transactionRepository);

                return $response
                    ->setNextUrl(route('public.account.packages'))
                    ->setMessage(trans('plugins/real-estate::package.purchase_package_success'));
            }

            return $response
                ->setError()
                ->setNextUrl(route('public.account.packages'))
                ->setMessage($payPalService->getErrorMessage());
        }

        $this->savePayment($package, $request->input('charge_id'), $transactionRepository);

        if (!$request->has('success') || $request->input('success') != false) {
            return $response
                ->setNextUrl(route('public.account.packages'))
                ->setMessage(trans('plugins/real-estate::package.purchase_package_success'));
        }

        return $response
            ->setError()
            ->setNextUrl(route('public.account.packages'))
            ->setMessage(__('Payment failed!'));
    }

    /**
     * @param UpdatePasswordRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postSecurity(UpdatePasswordRequest $request, BaseHttpResponse $response)
    {
        $this->accountRepository->update(['id' => auth('account')->id()], [
            'password' => bcrypt($request->input('password')),
        ]);

        $this->activityLogRepository->createOrUpdate(['action' => 'update_security']);

        //return $response->setMessage(trans('plugins/real-estate::dashboard.password_update_success'));
        return $response->setMessage(trans('core/acl::users.password_update_success'));
    }

    /**
     * @param AvatarRequest $request
     * @param ThumbnailService $thumbnailService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postAvatar(AvatarRequest $request, ThumbnailService $thumbnailService, BaseHttpResponse $response)
    {
        try {
            $account = auth('account')->user();

            $result = RvMedia::handleUpload($request->file('avatar_file'), 0, 'accounts');

            if ($result['error'] != false) {
                return $response->setError()->setMessage($result['message']);
            }

            $avatarData = json_decode($request->input('avatar_data'));

            $file = $result['data'];

            $thumbnailService
                ->setImage(RvMedia::getRealPath($file->url))
                ->setSize((int)$avatarData->width, (int)$avatarData->height)
                ->setCoordinates((int)$avatarData->x, (int)$avatarData->y)
                ->setDestinationPath(File::dirname($file->url))
                ->setFileName(File::name($file->url) . '.' . File::extension($file->url))
                ->save('crop');

            $this->fileRepository->forceDelete(['id' => $account->avatar_id]);

            $account->avatar_id = $file->id;

            $this->accountRepository->createOrUpdate($account);

            $this->activityLogRepository->createOrUpdate([
                'action' => 'changed_avatar',
            ]);

            return $response
                ->setMessage(trans('plugins/real-estate::dashboard.update_avatar_success'))
                ->setData(['url' => Storage::url($file->url)]);
        } catch (Exception $ex) {
            return $response
                ->setError()
                ->setMessage($ex->getMessage());
        }
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getActivityLogs(BaseHttpResponse $response)
    {
        $activities = $this->activityLogRepository->getAllLogs(auth('account')->id());

        Assets::addScriptsDirectly('vendor/core/plugins/real-estate/js/components.js');

        return $response->setData(ActivityLogResource::collection($activities))->toApiResponse();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|JsonResponse
     */
    public function postUpload(Request $request, BaseHttpResponse $response)
    {
        if (setting('media_chunk_enabled') != '1') {
            $validator = Validator::make($request->all(), [
                'file.0' => 'required|image|mimes:jpg,jpeg,png,webp',
            ]);

            if ($validator->fails()) {
                return $response->setError()->setMessage($validator->getMessageBag()->first());
            }

            $result = RvMedia::handleUpload(Arr::first($request->file('file')), 0, 'accounts');

            if ($result['error']) {
                return $response->setError(true)->setMessage($result['message']);
            }

            return $response->setData($result['data']);
        }

        try {
            // Create the file receiver
            $receiver = new FileReceiver('file', $request, DropZoneUploadHandler::class);
            // Check if the upload is success, throw exception or return response you need
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException;
            }
            // Receive the file
            $save = $receiver->receive();
            // Check if the upload has finished (in chunk mode it will send smaller files)
            if ($save->isFinished()) {
                $result = RvMedia::handleUpload($save->getFile(), 0, 'accounts');

                if ($result['error'] == false) {
                    return $response->setData($result['data']);
                }

                return $response->setError(true)->setMessage($result['message']);
            }
            // We are in chunk mode, lets send the current progress
            $handler = $save->handler();
            return response()->json([
                'done'   => $handler->getPercentageDone(),
                'status' => true,
            ]);
        } catch (Exception $exception) {
            return $response->setError(true)->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function postUploadFromEditor(Request $request)
    {
        return RvMedia::uploadFromEditor($request, 0, 'accounts');
    }

    /**
     * @param \Botble\RealEstate\Repositories\Interfaces\TransactionInterface $transactionRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ajaxGetTransactions(TransactionInterface $transactionRepository, BaseHttpResponse $response)
    {
        $transactions = $transactionRepository->advancedGet([
            'condition' => [
                'account_id' => auth('account')->user()->id,
            ],
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => 1,
            ],
            'order_by'  => ['created_at' => 'DESC'],
            'with'      => ['payment', 'user'],
        ]);
        return $response->setData(TransactionResource::collection($transactions))->toApiResponse();
    }

    public function myReview()
    {
        // dd('ohhhh Yeh, My Review Working!! Great.');
        SeoHelper::setTitle(auth('account')->user()->name);
        Assets::addScriptsDirectly('vendor/core/plugins/real-estate/js/components.js');

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.my-review.listing'))) {
            return Theme::scope('real-estate.account.my-review.listing')->render();
        }
    }

    public function editMyReview($id)
    {
        SeoHelper::setTitle(auth('account')->user()->name);
        Assets::addScriptsDirectly('vendor/core/plugins/real-estate/js/components.js');

        $reviewId = $id;

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.my-review.edit'))) {
            return Theme::scope('real-estate.account.my-review.edit', compact('reviewId'))->render();
        }
    }

    public function deleteMyReview($id)
    {
        Review::find($id)->delete();
        return redirect()->back();
    }

    public function clientReview()
    {
        SeoHelper::setTitle(auth('account')->user()->name);
        Assets::addScriptsDirectly('vendor/core/plugins/real-estate/js/components.js');

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.client-review.listing'))) {
            return Theme::scope('real-estate.account.client-review.listing')->render();
        }
    }
}
