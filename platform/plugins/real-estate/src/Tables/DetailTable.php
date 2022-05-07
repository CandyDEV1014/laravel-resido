<?php

namespace Botble\RealEstate\Tables;

use Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\DetailInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class DetailTable extends TableAbstract
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
     * DetailTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param DetailInterface $detailRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, DetailInterface $detailRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $detailRepository;

        if (!Auth::user()->hasAnyPermission(['property_detail.edit', 'property_detail.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     * @since 2.1
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('property_detail.edit')) {
                    return $item->name;
                }
                return Html::link(route('property_detail.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('property_detail.edit', 'property_detail.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * Get the query object to be processed by table.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     * @since 2.1
     */
    public function query()
    {
        $query = $this->repository->getModel()->select([
            're_details.id',
            're_details.name',
            're_details.status',
        ]);

        return $this->applyScopes($query);
    }

    /**
     * @return array
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id' => [
                'name'  => 're_details.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'name'  => 're_details.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'status' => [
                'name'  => 're_details.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * @return array
     * @since 2.1
     * @throws \Throwable
     */
    public function buttons()
    {
        return $this->addCreateButton(route('property_detail.create'), 'property_detail.create');
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('property_detail.deletes'), 'property_detail.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            're_details.name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_details.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
        ];
    }
}
