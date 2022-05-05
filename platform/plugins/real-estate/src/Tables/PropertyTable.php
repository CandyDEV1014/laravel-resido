<?php

namespace Botble\RealEstate\Tables;

use BaseHelper;
use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\RealEstate\Exports\PropertyExport;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use RvMedia;
use Throwable;
use Yajra\DataTables\DataTables;
use Botble\RealEstate\Repositories\Interfaces\TypeInterface;

class PropertyTable extends TableAbstract
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
     * @var string
     */
    protected $exportClass = PropertyExport::class;

    /**
     * @var TypeInterface
     */
    protected $typeRepository;

    /**
     * TagTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param TypeInterface $typeRepository
     * @param PropertyInterface $propertyRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        PropertyInterface $propertyRepository,
        TypeInterface $typeRepository
    ) {
        parent::__construct($table, $urlGenerator);

        $this->repository = $propertyRepository;
        $this->typeRepository = $typeRepository;
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
                return Html::link(route('property.edit', $item->id), $item->name);
            })
            ->editColumn('image', function ($item) {

                if ($this->request()->input('action') == 'csv') {
                    return RvMedia::getImageUrl($item->image, null, false, RvMedia::getDefaultImage());
                }

                if ($this->request()->input('action') == 'excel') {
                    return RvMedia::getImageUrl($item->image, 'thumb', false, RvMedia::getDefaultImage());
                }

                return Html::image(RvMedia::getImageUrl($item->image, 'thumb', false, RvMedia::getDefaultImage()),
                    $item->name, ['width' => 50]);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('type', function ($item) {
                return $item->type->name;
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
			->editColumn('status', function ($item) {
                return clean($item->status->toHtml());
            })
            ->editColumn('moderation_status', function ($item) {
                return $item->moderation_status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('property.edit', 'property.destroy', $item);
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
            're_properties.id',
            're_properties.name',
            're_properties.images',
			're_properties.status',
            're_properties.type_id',
            're_properties.moderation_status',
            're_properties.created_at',
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
            'id'                => [
                'name'  => 're_properties.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'image'             => [
                'name'       => 're_properties.image',
                'title'      => trans('core/base::tables.image'),
                'width'      => '60px',
                'class'      => 'no-sort',
                'orderable'  => false,
                'searchable' => false,
            ],
            'name'              => [
                'name'  => 're_properties.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'type'              => [
                'name'  => 're_properties.type_id',
                'title' => trans('plugins/real-estate::property.property_type'),
                'width' => '100px',
                'class' => 'text-start',
            ],
            'created_at'        => [
                'name'  => 're_properties.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-start',
            ],
			'status'            => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
            'moderation_status' => [
                'name'  => 're_properties.moderation_status',
                'title' => trans('plugins/real-estate::property.moderation_status'),
                'width' => '150px',
            ],
        ];
    }

    /**
     * @return array
     *
     * @throws Throwable
     * @since 2.1
     */
    public function buttons()
    {
        return $this->addCreateButton(route('property.create'), 'property.create');
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('property.deletes'), 'property.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        $types = $this->typeRepository->pluck('re_property_types.name', 're_property_types.id');
        return [
            're_properties.name'              => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_properties.type_id' => [
                'title'    => trans('plugins/real-estate::property.property_type'),
                'type'     => 'select',
                'choices'  => $types,
                'validate' => 'required|in:' . implode(',', array_keys($types)),
            ],
			'status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => PropertyStatusEnum::labels(),
                'validate' => 'required|' . implode(',', PropertyStatusEnum::values()),
            ],
            're_properties.moderation_status' => [
                'title'    => trans('plugins/real-estate::property.moderation_status'),
                'type'     => 'select',
                'choices'  => ModerationStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', ModerationStatusEnum::values()),
            ],
            're_properties.created_at'        => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function applyFilterCondition($query, string $key, string $operator, ?string $value)
    {
        switch ($key) {
            case 'status':
                switch ($value) {
                    case 'expired':
                        return $query->expired();
                    case 'active':
                        return $query
                            ->notExpired()
                            ->where('moderation_status', ModerationStatusEnum::APPROVED);
                }
        }

        return parent::applyFilterCondition($query, $key, $operator, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultButtons(): array
    {
        return [
            'export',
            'reload',
        ];
    }
}
