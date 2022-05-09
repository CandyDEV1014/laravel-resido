<?php

namespace Botble\RealEstate\Http\Controllers;

use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Forms\AccountPropertyForm;
use Botble\RealEstate\Http\Requests\AccountPropertyRequest;
use Botble\RealEstate\Http\Requests\AccountPropertyAddRequest;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Models\AccountPackage;
use Botble\RealEstate\Repositories\Interfaces\AccountActivityLogInterface;
use Botble\RealEstate\Repositories\Interfaces\AccountInterface;
use Botble\RealEstate\Repositories\Interfaces\AccountPackageInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\RealEstate\Http\Resources\AccountResource;
use Botble\RealEstate\Services\SaveFacilitiesService;
use Botble\RealEstate\Tables\AccountPropertyTable;
use EmailHandler;
use Exception;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RealEstateHelper;
use SeoHelper;
use Theme;

class AccountPropertyController extends Controller
{
    /**
     * @var AccountInterface
     */
    protected $accountRepository;

    /**
     * @var PropertyInterface
     */
    protected $propertyRepository;

    /**
     * @var AccountActivityLogInterface
     */
    protected $activityLogRepository;

    /**
     * @var AccountPackageInterface
     */
    protected $accountPackageRepository;
    /**
     * PublicController constructor.
     * @param Repository $config
     * @param AccountInterface $accountRepository
     * @param PropertyInterface $propertyRepository
     * @param AccountActivityLogInterface $accountActivityLogRepository
     */
    public function __construct(
        Repository $config,
        AccountInterface $accountRepository,
        AccountPackageInterface $accountPackageRepository,
        PropertyInterface $propertyRepository,
        AccountActivityLogInterface $accountActivityLogRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->accountPackageRepository = $accountPackageRepository;
        $this->propertyRepository = $propertyRepository;
        $this->activityLogRepository = $accountActivityLogRepository;

        Assets::setConfig($config->get('plugins.real-estate.assets'));
    }

    /**
     * @param Request $request
     * @param AccountPropertyTable $propertyTable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View|\Response
     * @throws \Throwable
     */
    public function index(Request $request, AccountPropertyTable $propertyTable)
    {
        SeoHelper::setTitle(trans('plugins/real-estate::account-property.properties'));

        $user = auth('account')->user();

        if ($request->isMethod('get') && view()->exists(Theme::getThemeNamespace('views.real-estate.account.table.index'))) {
            return Theme::scope('real-estate.account.table.index', ['user' => $user, 'propertyTable' => $propertyTable])
                ->render();
        }

        return $propertyTable->render('plugins/real-estate::account.table.base');
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @throws \Throwable
     */
    public function create(FormBuilder $formBuilder)
    {
        //if (!auth('account')->user()->canPost()) {
        //    return back()->with(['error_msg' => trans('plugins/real-estate::package.add_credit_alert')]);
        //}
        if ($this->accountPackageRepository->isExpired(auth('account')->id())) {
            return back()->with(['error_msg' => trans('plugins/real-estate::package.expired_package_alert')]);
        }
        
        $propertiesCount = $this->propertyRepository->getAccountPropertiesCount(auth('account')->id());

        $activePackage = $this->accountPackageRepository->getActivePackage(auth('account')->id());

        if (empty($activePackage)) {
            return back()->with(['error_msg' => trans('plugins/real-estate::package.no_package_alert')]);
        } 

        if ($activePackage['number_of_properties'] != -1 && $propertiesCount >= $activePackage['number_of_properties']) {
            return back()->with(['error_msg' => trans('plugins/real-estate::package.over_properties_alert')]);
        }
        
        SeoHelper::setTitle(trans('plugins/real-estate::account-property.write_property'));

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.forms.index'))) {
            $user = auth('account')->user();
            $form = $formBuilder->create(AccountPropertyForm::class);
            $form->setFormOption('template', Theme::getThemeNamespace() . '::views.real-estate.account.forms.base');
            return Theme::scope('real-estate.account.forms.index',
                ['user' => $user, 'package' => $activePackage, 'formBuilder' => $formBuilder, 'form' => $form])->render();
        }

        return $formBuilder->create(AccountPropertyForm::class)->renderForm();
    }

    /**
     * @param AccountPropertyRequest $request
     * @param BaseHttpResponse $response
     * @param AccountInterface $accountRepository
     * @param SaveFacilitiesService $saveFacilitiesService
     * @return BaseHttpResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(
        AccountPropertyAddRequest $request,
        BaseHttpResponse $response,
        AccountInterface $accountRepository,
        SaveFacilitiesService $saveFacilitiesService
    ) {
        if (!auth('account')->user()->canPost()) {
            return back()->with(['error_msg' => trans('plugins/real-estate::package.add_credit_alert')]);
        }

        $request->merge(['expire_date' => now()->addDays(RealEstateHelper::propertyExpiredDays())]);

        $property = $this->propertyRepository->getModel();

        $property->fill(array_merge($request->input(), [
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]));

        if (setting('enable_post_approval', 1) == 0) {
            $property->moderation_status = ModerationStatusEnum::APPROVED;
        }

        $property = $this->propertyRepository->createOrUpdate($property);

        if ($property) {
            $property->features()->sync($request->input('features', []));
            $property->details()->sync($request->input('details', []));

            $saveFacilitiesService->execute($property, $request->input('facilities', []));
        }

        event(new CreatedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'create_property',
            'reference_name' => $property->name,
            'reference_url'  => route('public.account.properties.edit', $property->id),
        ]);

        if (RealEstateHelper::isEnabledCreditsSystem()) {
            $account = $accountRepository->findOrFail(auth('account')->id());
            $account->credits--;
            $account->save();
        }

        EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'post_name'   => $property->name,
                'post_url'    => route('property.edit', $property->id),
                'post_author' => $property->author->name,
            ])
            ->sendUsingTemplate('new-pending-property');

        return $response
            ->setPreviousUrl(route('public.account.properties.index'))
            ->setNextUrl(route('public.account.properties.edit', $property->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     *
     * @throws \Throwable
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $property = $this->propertyRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]);

        if (!$property) {
            abort(404);
        }

        if ($property->status == 'sold') {
            return back()->with(['error_msg' => trans('plugins/real-estate::property.sold_expired_alert')]);
        }

        event(new BeforeEditContentEvent($request, $property));

        SeoHelper::setTitle(trans('plugins/real-estate::property.edit') . ' "' . $property->name . '"');

        if (view()->exists(Theme::getThemeNamespace('views.real-estate.account.forms.index'))) {
            $user = auth('account')->user();
            $form = $formBuilder->create(AccountPropertyForm::class, ['model' => $property]);
            $form->setFormOption('template', Theme::getThemeNamespace() . '::views.real-estate.account.forms.base');
            return Theme::scope('real-estate.account.forms.index',
                ['user' => $user, 'formBuilder' => $formBuilder, 'form' => $form])->render();
        }

        return $formBuilder
            ->create(AccountPropertyForm::class, ['model' => $property])
            ->renderForm();
    }

    /**
     * @param int $id
     * @param AccountPropertyRequest $request
     * @param BaseHttpResponse $response
     * @param SaveFacilitiesService $saveFacilitiesService
     * @return BaseHttpResponse
     *
     */
    public function update(
        $id,
        AccountPropertyAddRequest $request,
        BaseHttpResponse $response,
        SaveFacilitiesService $saveFacilitiesService
    ) {
        $property = $this->propertyRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]);

        if (!$property) {
            abort(404);
        }

        if ($property->sold_expire_date == null && $request->input('status') == 'sold') {
            $request->merge(['sold_expire_date' => now()->addDays(RealEstateHelper::soldpropertyExpiredDays())]);
        } 

        if ($request->input('status') != 'sold') {
            $request->merge(['sold_expire_date' => null]);
        }

        $property->fill($request->except(['expire_date']));

        $this->propertyRepository->createOrUpdate($property);

        $property->features()->sync($request->input('features', []));
        $property->details()->sync($request->input('details', []));
        
        $saveFacilitiesService->execute($property, $request->input('facilities', []));

        event(new UpdatedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'update_property',
            'reference_name' => $property->name,
            'reference_url'  => route('public.account.properties.edit', $property->id),
        ]);

        return $response
            ->setPreviousUrl(route('public.account.properties.index'))
            ->setNextUrl(route('public.account.properties.edit', $property->id))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function destroy($id, BaseHttpResponse $response)
    {
        $property = $this->propertyRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth('account')->id(),
            'author_type' => Account::class,
        ]);

        if (!$property) {
            abort(404);
        }

        $this->propertyRepository->delete($property);

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'delete_property',
            'reference_name' => $property->name,
        ]);

        return $response->setMessage(__('Delete property successfully!'));
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getProperty($id, BaseHttpResponse $response)
    {
        $property = $this->propertyRepository->findOrFail($id);
        $expire_date = date('Y-m-d', strtotime($property->expire_date));
        $now = date('Y-m-d');

        if ($expire_date >= $now || $property->never_expired) {
            return $response->setError(true)->setMessage(__('This property is not expired!'));
        }

        return $response->setData($property)->toApiResponse();
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function renew($id, BaseHttpResponse $response)
    {
        $job = $this->propertyRepository->findOrFail($id);

        $account = auth('account')->user();

        if (RealEstateHelper::isEnabledCreditsSystem() && $account->credits < RealEstateHelper::propertyRenewPrice()) {
            return $response->setError(true)->setMessage(__('You don\'t have enough credit to renew this property!'));
        }

        $job->expire_date = now()->addDays(RealEstateHelper::propertyExpiredDays());
        $job->save();

        if (RealEstateHelper::isEnabledCreditsSystem()) {
            $account->credits = $account->credits - RealEstateHelper::propertyRenewPrice();
            $account->save();
        }

        return $response
                ->setData(new AccountResource($account->refresh()))
                ->setMessage(trans('plugins/real-estate::package.add_credit_success'));
        return $response->setMessage(__('Renew property successfully'));
    }
}
