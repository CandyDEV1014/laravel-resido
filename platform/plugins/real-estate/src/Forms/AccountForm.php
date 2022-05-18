<?php

namespace Botble\RealEstate\Forms;

use Assets;
use BaseHelper;
use Botble\Base\Forms\FormAbstract;
use Botble\RealEstate\Http\Requests\AccountCreateRequest;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Repositories\Interfaces\AccountPackageInterface;
use Botble\RealEstate\Repositories\Interfaces\PackageInterface;
use Botble\Base\Enums\BaseStatusEnum;
use Throwable;
use RealEstateHelper;

class AccountForm extends FormAbstract
{

    /**
     * @var AccountPackageInterface
     */
    protected $accountPackageRepository;

    /**
     * @var PackageInterface
     */
    protected $packageRepository;

    /**
     * AccountForm constructor.
     * @param AccountPackageInterface $accountPackageRepository
     * @param PackageInterface $packageRepository
     * 
     */
    public function __construct(
        AccountPackageInterface $accountPackageRepository,
        PackageInterface $packageRepository
    ) {
        parent::__construct();
        $this->accountPackageRepository = $accountPackageRepository;
        $this->packageRepository = $packageRepository;
    }

    /**
     * @var string
     */
    protected $template = 'plugins/real-estate::account.admin.form';

    /**
     * @return mixed|void
     * @throws Throwable
     */
    public function buildForm()
    {
        Assets::addStylesDirectly('vendor/core/plugins/real-estate/css/account-admin.css')
            ->addScriptsDirectly(['/vendor/core/plugins/real-estate/js/account-admin.js']);
        
        if ($this->getModel()) {
            $activePackage = $this->accountPackageRepository->getActivePackage($this->getModel()->id);
            $this->getModel()->package_id = !empty($activePackage) ? $activePackage['package_id'] : 0;
        }
        
        $packages = $this->packageRepository->advancedGet([
            'order_by' => ['order' => 'DESC'],
            'condition' => ['status' => BaseStatusEnum::PUBLISHED],
        ]);
        
        $packageChoices = [];
        if (!empty($packages)) {
            foreach ($packages as $package) {
                $packageChoices[$package->id] = $package->name ? $package->name : '';
            }
        }

        $this
            ->setupModel(new Account)
            ->setValidatorClass(AccountCreateRequest::class)
            ->withCustomFields()
            ->add('first_name', 'text', [
                'label'      => trans('plugins/real-estate::account.first_name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::account.first_name'),
                    'data-counter' => 120,
                ],
            ])
            ->add('last_name', 'text', [
                'label'      => trans('plugins/real-estate::account.last_name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::account.last_name'),
                    'data-counter' => 120,
                ],
            ])
            ->add('username', 'text', [
                'label'      => trans('plugins/real-estate::account.username'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::account.username_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('phone', 'text', [
                'label'      => trans('plugins/real-estate::account.phone'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::account.phone_placeholder'),
                    'data-counter' => 20,
                ],
            ])
            ->add('email', 'text', [
                'label'      => trans('plugins/real-estate::account.form.email'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::account.email_placeholder'),
                    'data-counter' => 60,
                ],
            ])
            ->add('package_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::account.membership_plan'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                    'placeholder' => __('Member'),
                ],
                'choices'    => $packageChoices,
            ])
            ->add('is_featured', 'onOff', [
                'label'         => trans('core/base::forms.is_featured'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('confirmed_at', 'onOff', [
                'label'         => 'Is Verified',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('is_change_password', 'checkbox', [
                'label'      => trans('plugins/real-estate::account.form.change_password'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'hrv-checkbox',
                ],
                'value'      => 1,
            ])
            ->add('password', 'password', [
                'label'      => trans('plugins/real-estate::account.form.password'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 60,
                ],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ($this->getModel()->id ? ' hidden' : null),
                ],
            ])
            ->add('password_confirmation', 'password', [
                'label'      => trans('plugins/real-estate::account.form.password_confirmation'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 60,
                ],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ($this->getModel()->id ? ' hidden' : null),
                ],
            ])
            ->add('avatar_image', 'mediaImage', [
                'label'      => trans('core/base::forms.image'),
                'label_attr' => ['class' => 'control-label'],
                'value'      => $this->getModel()->avatar->url,
            ])
            ->setBreakFieldPoint('avatar_image');


        if ($this->getModel()->id && RealEstateHelper::isEnabledCreditsSystem()) {
            $this->addMetaBoxes([
                'credits' => [
                    'title'   => null,
                    'content' => view('plugins/real-estate::account.admin.credits', [
                        'account'      => $this->model,
                        'transactions' => $this->model->transactions()->orderBy('created_at', 'DESC')->get(),
                    ])->render(),
                    'wrap'    => false,
                ],
            ]);
        }
    }
}
