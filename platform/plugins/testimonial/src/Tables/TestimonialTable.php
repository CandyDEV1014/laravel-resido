<?php

namespace Botble\Testimonial\Tables;

use BaseHelper;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Testimonial\Repositories\Interfaces\TestimonialInterface;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TestimonialTable extends TableAbstract
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
     * TestimonialTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param TestimonialInterface $testimonialRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        TestimonialInterface $testimonialRepository
    ) {
        parent::__construct($table, $urlGenerator);

        $this->repository = $testimonialRepository;

        if (!Auth::user()->hasAnyPermission(['testimonial.edit', 'testimonial.destroy'])) {
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
            ->editColumn('image', function ($item) {
                return $this->displayThumbnail($item->image, ['width' => 70]);
            })
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('testimonial.edit')) {
                    return $item->name;
                }

                return Html::link(route('testimonial.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->addColumn('operations', function ($item) {
                return table_actions('testimonial.edit', 'testimonial.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()->select([
            'id',
            'name',
            'created_at',
            'image',
        ]);

        return $this->applyScopes($query);
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
            'image'      => [
                'title' => trans('core/base::tables.image'),
                'width' => '100px',
            ],
            'name'       => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        return $this->addCreateButton(route('testimonial.create'), 'testimonial.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('testimonial.deletes'), 'testimonial.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
