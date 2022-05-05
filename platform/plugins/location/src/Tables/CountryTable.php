<?php

namespace Botble\Location\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class CountryTable extends TableAbstract
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
     * CountryTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param CountryInterface $countryRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, CountryInterface $countryRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $countryRepository;

        if (!Auth::user()->hasAnyPermission(['country.edit', 'country.destroy'])) {
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
                if (!Auth::user()->hasPermission('country.edit')) {
                    return $item->name;
                }
                return Html::link(route('country.edit', $item->id), $item->name);
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
                return $this->getOperations('country.edit', 'country.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()->select([
            'countries.id',
            'countries.name',
            'countries.nationality',
            'countries.created_at',
            'countries.status',
        ]);

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'          => [
                'name'  => 'countries.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name'        => [
                'name'  => 'countries.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'nationality' => [
                'name'  => 'countries.nationality',
                'title' => trans('plugins/location::country.nationality'),
                'class' => 'text-start',
            ],
            'created_at'  => [
                'name'  => 'countries.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'      => [
                'name'  => 'countries.status',
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
        return $this->addCreateButton(route('country.create'), 'country.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('country.deletes'), 'country.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'countries.name'        => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'countries.nationality' => [
                'title'    => trans('plugins/location::country.nationality'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'countries.status'      => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'countries.created_at'  => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
