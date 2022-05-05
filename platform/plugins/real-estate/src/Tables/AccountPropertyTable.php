<?php

namespace Botble\RealEstate\Tables;

use BaseHelper;
use Botble\RealEstate\Models\Account;
use Html;
use Illuminate\Support\Arr;
use RvMedia;

class AccountPropertyTable extends PropertyTable
{
    /**
     * @var bool
     */
    public $hasActions = false;

    /**
     * @var bool
     */
    public $hasCheckbox = false;

    /**
     * @var bool
     */
    public $hasFilter = false;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @since 2.1
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                return Html::link(route('public.account.properties.edit', $item->id), $item->name);
            })
            ->editColumn('image', function ($item) {
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
            ->editColumn('expire_date', function ($item) {
                if ($item->never_expired) {
                    return trans('plugins/real-estate::property.never_expired_label');
                }

                if ($item->expire_date) {
                    if ($item->expire_date && $item->expire_date->isPast()) {
                        return Html::tag('span', $item->expire_date->toDateString(), ['class' => 'text-danger'])->toHtml();
                    }

                    if (now()->diffInDays($item->expire_date) < 3) {
                        return Html::tag('span', $item->expire_date->toDateString(), ['class' => 'text-warning'])->toHtml();
                    }

                    return $item->expire_date->toDateString();
                }

                return '';
            })
			->editColumn('status', function ($item) {
                return clean($item->status->toHtml());
            })
            ->editColumn('moderation_status', function ($item) {
                return $item->moderation_status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                $edit = 'public.account.properties.edit';
                $delete = 'public.account.properties.destroy';

                return view('plugins/real-estate::account.table.actions', compact('edit', 'delete', 'item'))->render();
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
            ->select([
                're_properties.id',
                're_properties.name',
                're_properties.images',
                're_properties.created_at',
                're_properties.status',
                're_properties.type_id',
                're_properties.moderation_status',
                're_properties.expire_date',
            ])
            ->where([
                're_properties.author_id'   => auth('account')->id(),
                're_properties.author_type' => Account::class,
            ]);

        return $this->applyScopes($query);
    }

    /**
     * {@inheritdoc}
     */
    public function buttons()
    {
        $buttons = [];
        if (auth('account')->user()->canPost()) {
            $buttons = $this->addCreateButton(route('public.account.properties.create'));
        }

        return $buttons;
    }

    /**
     * @return array
     */
    public function columns()
    {
        $columns  = [
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
                'width' => '50px',
            ],
            'moderation_status' => [
                'name'  => 're_properties.moderation_status',
                'title' => trans('plugins/real-estate::property.moderation_status'),
                'width' => '120px',
            ],
        ];
        Arr::forget($columns, 'author_id');

        $columns['expire_date'] = [
            'name'  => 're_properties.expire_date',
            'title' => trans('plugins/real-estate::property.expire_date'),
            'width' => '100px',
        ];

        return $columns;
    }

    /**
     * @return array
     */
    public function getDefaultButtons(): array
    {
        return ['reload'];
    }
}
