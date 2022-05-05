<?php

namespace Botble\SimpleSlider\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\SimpleSlider\Http\Requests\SimpleSliderRequest;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Tables\SimpleSliderItemTable;
use Botble\Table\TableBuilder;

class SimpleSliderForm extends FormAbstract
{

    /**
     * @var TableBuilder
     */
    protected $tableBuilder;

    /**
     * SimpleSliderForm constructor.
     * @param TableBuilder $tableBuilder
     */
    public function __construct(TableBuilder $tableBuilder)
    {
        parent::__construct();
        $this->tableBuilder = $tableBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new SimpleSlider)
            ->setValidatorClass(SimpleSliderRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 120,
                ],
            ])
            ->add('key', 'text', [
                'label'      => trans('plugins/simple-slider::simple-slider.key'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label'      => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 4,
                    'placeholder'  => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->addMetaBoxes([
                'slider-items' => [
                    'title'   => trans('plugins/simple-slider::simple-slider.slide_items'),
                    'content' => $this->tableBuilder->create(SimpleSliderItemTable::class)
                        ->setAjaxUrl(route('simple-slider-item.index',
                            $this->getModel()->id ?: 0))
                        ->renderTable(),
                ],
            ])
            ->setBreakFieldPoint('status');
    }
}
