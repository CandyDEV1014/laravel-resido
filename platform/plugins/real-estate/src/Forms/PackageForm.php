<?php

namespace Botble\RealEstate\Forms;

use Assets;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\CurrencyInterface;
use Botble\RealEstate\Http\Requests\PackageRequest;
use Botble\RealEstate\Models\Package;
use Throwable;
use RealEstateHelper;

class PackageForm extends FormAbstract
{
    /**
     * @var CurrencyInterface
     */
    protected $currencyRepository;

    /**
     * PackageForm constructor.
     * @param CurrencyInterface $currencyRepository
     */
    public function __construct(CurrencyInterface $currencyRepository)
    {
        parent::__construct();
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @var string
     */
    protected $template = 'plugins/real-estate::account.admin.form';

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {

        Assets::addScripts(['input-mask']);
        Assets::addScriptsDirectly('vendor/core/plugins/real-estate/js/package-admin.js');

        $currencies = $this->currencyRepository->pluck('re_currencies.title', 're_currencies.id');

        $this
            ->setupModel(new Package)
            ->setValidatorClass(PackageRequest::class)
            ->withCustomFields()
            ->add('addNote', 'html', [
                'html' => '<div class="note note-warning"><p>Use -1 for Unlimited</p></div>',
            ])
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('price', 'text', [
                'label'      => trans('plugins/real-estate::package.price'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr'       => [
                    'id'          => 'price-number',
                    'placeholder' => trans('plugins/real-estate::package.price'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('currency_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::package.currency'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => $currencies,
            ])
            ->add('credits', 'text', [
                'label'      => trans('plugins/real-estate::package.credits'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr'       => [
                    'id'          => 'credits-number',
                    'placeholder' => trans('plugins/real-estate::package.credits'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">'
            ])
            ->add('number_of_days', 'text', [
                'label'      => trans('plugins/real-estate::package.number_of_days'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr'       => [
                    'id'          => 'number-of-days',
                    'placeholder' => '',
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('number_of_properties', 'text', [
                'label'      => trans('plugins/real-estate::package.number_of_properties'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr'       => [
                    'id'          => 'number-of-properties',
                    'placeholder' => '',
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('number_of_aminities', 'text', [
                'label'      => trans('plugins/real-estate::package.number_of_aminities'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr'       => [
                    'id'          => 'number-of-aminities',
                    'placeholder' => '',
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen3', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('number_of_nearestplace', 'text', [
                'label'      => trans('plugins/real-estate::package.number_of_nearestplace'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr'       => [
                    'id'          => 'number-of-nearestplace',
                    'placeholder' => '',
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('number_of_photo', 'text', [
                'label'      => trans('plugins/real-estate::package.number_of_photo'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr'       => [
                    'id'          => 'number-of-photo',
                    'placeholder' => '',
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('rowClose3', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen4', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('is_allow_featured', 'onOff', [
                'label'         => trans('plugins/real-estate::package.is_allow_featured'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper'    => [
                    'class' => 'form-group mb-3 py-2 col-md-4',
                ],
                'attr'          => [
                    'id'             => 'is_allow_featured'
                ]
            ])
            ->add('number_of_featured', 'text', [
                'label'      => false,
                'label_attr' => false,
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-4 ' . ($this->getModel()->is_allow_featured == 1 ? null : 'hidden'),
                ],
                'attr'       => [
                    'id'          => 'number_of_featured',
                    'placeholder' => trans('plugins/real-estate::package.number_of_featured'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('rowClose4', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen5', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('is_allow_top', 'onOff', [
                'label'         => trans('plugins/real-estate::package.is_allow_top'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper'    => [
                    'class' => 'form-group mb-3 py-2 col-md-4',
                ],
                'attr'          => [
                    'id'             => 'is_allow_top'
                ]
            ])
            ->add('number_of_top', 'text', [
                'label'      => false,
                'label_attr' => false,
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-4 ' . ($this->getModel()->is_allow_top == 1 ? null : 'hidden'),
                ],
                'attr'       => [
                    'id'          => 'number_of_top',
                    'placeholder' => trans('plugins/real-estate::package.number_of_top'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('rowClose5', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen6', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('is_allow_urgent', 'onOff', [
                'label'         => trans('plugins/real-estate::package.is_allow_urgent'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper'    => [
                    'class' => 'form-group mb-3 py-2 col-md-4',
                ],
                'attr'          => [
                    'id'             => 'is_allow_urgent'
                ]
            ])
            ->add('number_of_urgent', 'text', [
                'label'      => false,
                'label_attr' => false,
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-4 ' . ($this->getModel()->is_allow_urgent == 1 ? null : 'hidden'),
                ],
                'attr'       => [
                    'id'          => 'number_of_urgent',
                    'placeholder' => trans('plugins/real-estate::package.number_of_urgent'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('rowClose6', 'html', [
                'html' => '</div>',
            ])
            ->add('is_promotion', 'onOff', [
                'label'      => trans('plugins/real-estate::package.is_promotion'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper'    => [
                    'class' => 'form-group mb-3'
                ]
            ])
            ->add('rowOpen7', 'html', [
                'html' => '<div class="row promotion ' . ($this->getModel()->is_promotion == 1 ? null : 'hidden') . '">',
            ])
            ->add('promotion_time', 'text', [
                'label'         => trans('plugins/real-estate::package.promotion_time'),
                'label_attr'    => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                'attr'          => [
                    'id'               => 'promotion_time',
                    'placeholder' => trans('plugins/real-estate::package.promotion_time'),
                    'class'            => 'form-control datepicker',
                    'data-date-format' => 'yyyy-mm-dd',
                ],
                'default_value' => '',
            ])
            ->add('promotion_price', 'text', [
                'label'      => trans('plugins/real-estate::package.promotion_price'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                'attr'       => [
                    'id'          => 'promotion_price',
                    'placeholder' => trans('plugins/real-estate::package.promotion_price'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('rowClose7', 'html', [
                'html' => '</div>',
            ])
            
            ->add('rowOpen8', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('is_auto_renew', 'onOff', [
                'label'         => trans('plugins/real-estate::package.is_auto_renew'),
                'label_attr'    => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                'default_value' => false,
            ])

            ->add('rowClose8', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen9', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('is_agent', 'onOff', [
                'label'         => trans('plugins/real-estate::package.is_agent'),
                'label_attr'    => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                'default_value' => false,
            ])
            ->add('rowClose9', 'html', [
                'html' => '</div>',
            ])
            ->add('order', 'number', [
                'label'         => trans('core/base::forms.order'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'placeholder' => trans('core/base::forms.order_by_placeholder'),
                ],
                'default_value' => 0,
            ])
            ->add('features', 'repeater', [
                'label'      => __('Features'),
                'label_attr' => ['class' => 'control-label'],
                'fields' => [
                    [
                        'type'       => 'text',
                        'label'      => __('Title'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'text',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 255,
                                'placeholder' => __('Ex: 60-Day Job Postings'),
                            ],
                        ],
                    ],
                ],
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
