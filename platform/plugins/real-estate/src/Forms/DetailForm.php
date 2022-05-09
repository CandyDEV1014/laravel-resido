<?php

namespace Botble\RealEstate\Forms;

use Assets;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract; 
use Botble\RealEstate\Http\Requests\DetailRequest;
use Botble\RealEstate\Models\Detail;
use Botble\RealEstate\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Forms\Fields\CategoryMultiField;
use Botble\RealEstate\Enums\DetailTypeEnum;
use Throwable;

class DetailForm extends FormAbstract
{
    /**
     * @var CategoryInterface
     */
    protected $categoryRepository;

    /**
     * PropertyForm constructor.
     * @param CategoryInterface $categoryRepository
     */

    public function __construct(
        CategoryInterface $categoryRepository
    ) {
        parent::__construct();
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return mixed|void
     * @throws Throwable
     */
    public function buildForm()
    {
        Assets::addScriptsDirectly('vendor/core/plugins/real-estate/js/real-estate.js');

        $categories = $this->categoryRepository->allBy(['parent_id' => 0], [], ['id', 'name', 'parent_id']);
        // $categoryChoices = [];
        // foreach ($categories as $category) {
        //     $categoryChoices[] = $category->name ? $category->name : '';
        //     $categoryChoices[] = [
        //         'id' => $category->id,
        //         'name' => $category->name,
        //         'parent_id' => $category->parent_id
        //     ]
        // }

        $selectedCategories = [];
        if ($this->getModel()) {
            $selectedCategories = $this->getModel()->categories()->pluck('category_id')->all();
        }

        if (!$this->formHelper->hasCustomField('categoryMulti')) {
            $this->formHelper->addCustomField('categoryMulti', CategoryMultiField::class);
        }

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
            ->add('title', 'text', [
                'label'      => trans('plugins/real-estate::detail.form.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::detail.form.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('alt', 'text', [
                'label'      => trans('plugins/real-estate::detail.form.alt'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::detail.form.alt'),
                    'data-counter' => 60,
                ],
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
                    'id'    => 'detail_type'
                ],
                'choices'    => DetailTypeEnum::labels(),
            ])
            ->add('rowOpen', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('is_featured', 'onOff', [
                'label'      => trans('plugins/real-estate::detail.form.is_featured'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper'    => [
                    'class' => 'form-group col-md-6 mb-3'
                ]
            ])
            ->add('is_required', 'onOff', [
                'label'      => trans('plugins/real-estate::detail.form.is_required'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper'    => [
                    'class' => 'form-group col-md-6 mb-3'
                ]
            ])
            ->add('rowClose', 'html', [
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
                'wrapper' => [
                    'class' => 'form-group features-form-group ' . ($this->getModel()->type == DetailTypeEnum::SELECTBOX ? null : 'hidden')
                ]
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->add('categories[]', 'categoryMulti', [
                'label'      => trans('plugins/real-estate::detail.form.categories'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $categories,
                'value'      => old('categories', $selectedCategories),
            ])
            ->setBreakFieldPoint('status');
    }
}
