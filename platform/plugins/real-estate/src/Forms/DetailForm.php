<?php

namespace Botble\RealEstate\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract; 
use Botble\RealEstate\Http\Requests\DetailRequest;
use Botble\RealEstate\Models\Detail;
use Botble\RealEstate\Enums\DetailTypeEnum;
use Throwable;

class DetailForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws Throwable
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Detail)
            ->setValidatorClass(DetailRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('plugins/real-estate::detail.form.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::detail.form.name'),
                    'data-counter' => 120,
                ],
            ])
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('title', 'text', [
                'label'      => trans('plugins/real-estate::detail.form.title'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::detail.form.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('alt', 'text', [
                'label'      => trans('plugins/real-estate::detail.form.alt'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::detail.form.alt'),
                    'data-counter' => 60,
                ],
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])
            ->add('icon', 'text', [
                'label'         => trans('plugins/real-estate::feature.form.icon'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'placeholder'  => trans('plugins/real-estate::feature.form.icon'),
                    'data-counter' => 60,
                ],
                'default_value' => 'fas fa-check',
            ])
            ->add('type', 'customSelect', [
                'label'      => trans('plugins/real-estate::detail.form.type'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => DetailTypeEnum::labels(),
            ])
            ->add('is_featured', 'onOff', [
                'label'      => trans('plugins/real-estate::detail.form.is_featured'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper'    => [
                    'class' => 'form-group mb-3'
                ]
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
                'label'      => trans('plugins/real-estate::detail.form.features'),
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
                                'placeholder' => __('Please enter features option'),
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
