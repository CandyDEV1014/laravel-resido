<?php

namespace Botble\RealEstate\Tables;

use Auth;
use BaseHelper;
use Botble\RealEstate\Repositories\Interfaces\TypeInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Throwable;
use Yajra\DataTables\DataTables;

class TypeTable extends TableAbstract
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
     * TypeTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param TypeInterface $typeRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, TypeInterface $typeRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $typeRepository;

        if (!Auth::user()->hasAnyPermission(['property_type.edit', 'property_type.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * Display ajax response.
     *
     * @return JsonResponse
     * @since 2.1
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('property_type.edit')) {
                    return $item->name;
                }

                return Html::link(route('property_type.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('property_type.edit', 'property_type.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * Get the query object to be processed by table.
     *
     * @return \Illuminate\Database\Query\Builder|Builder
     * @since 2.1
     */
    public function query()
    {
        $query = $this->repository->getModel()->select([
            're_property_types.id',
            're_property_types.name',
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
            'id'         => [
                'name'  => 're_property_types.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name'       => [
                'name'  => 're_property_types.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
        ];
    }

    /**
     * @return array
     * @throws Throwable
     * @since 2.1
     */
    public function buttons()
    {
        return $this->addCreateButton(route('property_type.create'), 'property_type.create');
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('property_type.deletes'), 'property_type.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            're_property_types.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
        ];
    }
}
