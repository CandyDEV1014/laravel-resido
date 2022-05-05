<?php

namespace Botble\SimpleSlider\Tables;

use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\SimpleSlider\Repositories\Interfaces\SimpleSliderInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class SimpleSliderTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * SimpleSliderTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param SimpleSliderInterface $simpleSliderRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        SimpleSliderInterface $simpleSliderRepository
    ) {
        parent::__construct($table, $urlGenerator);

        $this->repository = $simpleSliderRepository;

        if (!Auth::user()->hasAnyPermission(['simple-slider.edit', 'simple-slider.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('simple-slider.edit')) {
                    return $item->name;
                }

                return Html::link(route('simple-slider.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('simple-slider.edit', 'simple-slider.destroy', $item);
            });

        if (function_exists('shortcode')) {
            $data = $data->editColumn('key', function ($item) {
                return shortcode()->generateShortcode('simple-slider', ['key' => $item->key]);
            });
        }

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $select = [
            'simple_sliders.id',
            'simple_sliders.name',
            'simple_sliders.key',
            'simple_sliders.status',
            'simple_sliders.created_at',
        ];

        $query = $model->select($select);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name'       => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'key'        => [
                'title' => trans('plugins/simple-slider::simple-slider.key'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        return $this->addCreateButton(route('simple-slider.create'), 'simple-slider.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('simple-slider.deletes'), 'simple-slider.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'simple_sliders.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'simple_sliders.key'        => [
                'title'    => trans('plugins/simple-slider::simple-slider.key'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'simple_sliders.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(BaseStatusEnum::values()),
            ],
            'simple_sliders.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
