<?php

namespace Botble\RealEstate\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\RealEstate\Forms\AccountForm;
use Botble\RealEstate\Http\Requests\AccountCreateRequest;
use Botble\RealEstate\Http\Requests\AccountEditRequest;
use Botble\RealEstate\Http\Resources\AccountResource;
use Botble\RealEstate\Repositories\Interfaces\AccountInterface;
use Botble\RealEstate\Repositories\Interfaces\AccountPackageInterface;
use Botble\RealEstate\Repositories\Interfaces\PackageInterface;
use Botble\RealEstate\Tables\AccountTable;
use Exception;
use Illuminate\Http\Request;

class AccountController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var AccountInterface
     */
    protected $accountRepository;

    /**
     * @param AccountInterface $accountRepository
     */
    public function __construct(AccountInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param AccountTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(AccountTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/real-estate::account.name'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/real-estate::account.create'));

        return $formBuilder
            ->create(AccountForm::class)
            ->remove('is_change_password')
            ->renderForm();
    }

    /**
     * @param AccountCreateRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(AccountCreateRequest $request, BaseHttpResponse $response)
    {
        $request->merge([
            'password' => bcrypt($request->input('password')),
        ]);

        if ($request->input('avatar_image')) {
            $image = app(MediaFileInterface::class)->getFirstBy(['url' => $request->input('avatar_image')]);
            if ($image) {
                $request->merge(['avatar_id' => $image->id]);
            }
        }

        $account = $this->accountRepository->getModel();
        $account->fill($request->input());
        $account->is_featured = $request->input('is_featured');
        $account->confirmed_at = now();
        $account = $this->accountRepository->createOrUpdate($account);

        event(new CreatedContentEvent(ACCOUNT_MODULE_SCREEN_NAME, $request, $account));

        return $response
            ->setPreviousUrl(route('account.index'))
            ->setNextUrl(route('account.edit', $account->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        $account = $this->accountRepository->findOrFail($id);

        page_title()->setTitle(trans('plugins/real-estate::account.edit', ['name' => $account->name]));

        $account->password = null;

        return $formBuilder
            ->create(AccountForm::class, ['model' => $account])
            ->renderForm();
    }

    /**
     * @param int $id
     * @param AccountEditRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, AccountEditRequest $request, BaseHttpResponse $response)
    {
        if ($request->input('avatar_image')) {
            $image = app(MediaFileInterface::class)->getFirstBy(['url' => $request->input('avatar_image')]);
            if ($image) {
                $request->merge(['avatar_id' => $image->id]);
            }
        }

        if ($request->input('package_id')) {
            $package = app(PackageInterface::class)->findOrFail($request->input('package_id'));

            app(AccountPackageInterface::class)->update(['account_id' => $id], [
                'status' => 0
            ]);
            
            app(AccountPackageInterface::class)->createOrUpdate([
                'account_id'   => $id,
                'package_id'   => $request->input('package_id'),
                'expired_day'  => $package->number_of_days,
                'expired_date' => $package->number_of_days != -1 ? now()->addDays($package->number_of_days) : null,
                'status'       => 1
            ]);
        } else {
            app(AccountPackageInterface::class)->update(['account_id' => $id], [
                'status' => 0
            ]);
        }

        if ($request->input('is_change_password') == 1) {
            $request->merge(['password' => bcrypt($request->input('password'))]);
            $data = $request->input();
        } else {
            $data = $request->except('password');
        }

        $account = $this->accountRepository->findOrFail($id);

        $account->fill($data);
        $account->is_featured = $request->input('is_featured');
        $account = $this->accountRepository->createOrUpdate($account);

        event(new UpdatedContentEvent(ACCOUNT_MODULE_SCREEN_NAME, $request, $account));

        return $response
            ->setPreviousUrl(route('account.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $account = $this->accountRepository->findOrFail($id);
            $this->accountRepository->delete($account);
            event(new DeletedContentEvent(ACCOUNT_MODULE_SCREEN_NAME, $request, $account));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.cannot_delete'));
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->accountRepository, ACCOUNT_MODULE_SCREEN_NAME);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     */
    public function getList(Request $request, BaseHttpResponse $response)
    {
        $keyword = $request->input('q');

        if (!$keyword) {
            return $response->setData([]);
        }

        $data = $this->accountRepository->getModel()
            ->where('re_accounts.first_name', 'LIKE', '%' . $keyword . '%')
            ->orWhere('re_accounts.last_name', 'LIKE', '%' . $keyword . '%')
            ->select(['re_accounts.id', 're_accounts.first_name', 're_accounts.last_name'])
            ->take(10)
            ->get();

        return $response->setData(AccountResource::collection($data));
    }
}
