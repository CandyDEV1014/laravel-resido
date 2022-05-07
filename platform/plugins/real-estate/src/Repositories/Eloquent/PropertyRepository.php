<?php

namespace Botble\RealEstate\Repositories\Eloquent;

use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\RealEstate\Enums\PropertyTypeEnum;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Language;

class PropertyRepository extends RepositoriesAbstract implements PropertyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRelatedProperties(int $propertyId, $limit = 4, array $with = [])
    {
        $currentProperty = $this->findById($propertyId);

        $this->model = $this->originalModel;
        $this->model = $this->model->where('id', '<>', $propertyId)
            ->notExpired()
            ->soldNotExpired()
            ->available();

        if ($currentProperty) {
            $this->model
                ->where('category_id', $currentProperty->category_id)
                ->where('type_id', $currentProperty->type_id);
        }

        $params = [
            'condition' => [
			    ['status', 'NOT_IN', [PropertyStatusEnum::NOT_AVAILABLE]],
                're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            'order_by'  => [
                're_properties.created_at' => 'desc',
            ],
            'take'      => $limit,
            'paginate'  => [
                'per_page'      => 12,
                'current_paged' => 1,
            ],
            'select'    => [
                're_properties.*',
            ],
            'with'      => $with,
        ];

        return $this->advancedGet($params);
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties($filters = [], $params = [])
    {
        $filters = array_merge([
            'keyword'     => null,
            'type'        => null,
            'bedroom'     => null,
            'bathroom'    => null,
            'floor'       => null,
            'min_square'  => null,
            'max_square'  => null,
            'min_price'   => null,
            'max_price'   => null,
            'category_id' => null,
            'city_id'     => null,
            'location'    => null,
            'features'    => null,
            'sort_by'     => null,
            'is_featured' => null,
            'author_id'   => null,
            'is_top_property' => null,
			'is_urgent' => null,
        ], $filters);

        $orderBy = [
            're_properties.created_at' => 'desc',
        ];
        $condition = [
            're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
        ];

        if($filters['sort_by'] == 'featured' || $filters['sort_by'] == 'top_property' || $filters['sort_by'] == 'urgent')
		{
            switch ($filters['sort_by']) {
                case 'featured':
                    $condition = [
                        're_properties.is_featured' => '1',
                    ];
                    break;
                case 'top_property':
                    $condition = [
                        're_properties.is_top_property' => '1',
                    ];
                    break;
				case 'urgent':
                    $condition = [
                        're_properties.is_urgent' => '1',
                    ];
                    break;
                default:
                    $condition = [
                        're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
                    ];
                    break;
            }
        }else{
            switch ($filters['sort_by']) {
                case 'date_asc':
                    $orderBy = [
                        're_properties.created_at' => 'asc',
                    ];
                    break;
                case 'date_desc':
                    $orderBy = [
                        're_properties.created_at' => 'desc',
                    ];
                    break;
                case 'price_asc':
                    $orderBy = [
                        're_properties.price' => 'asc',
                    ];
                    break;
                case 'price_desc':
                    $orderBy = [
                        're_properties.price' => 'desc',
                    ];
                    break;
                case 'name_asc':
                    $orderBy = [
                        're_properties.name' => 'asc',
                    ];
                    break;
                case 'name_desc':
                    $orderBy = [
                        're_properties.name' => 'desc',
                    ];
                    break;
                default:
                    $orderBy = [
                        're_properties.created_at' => 'desc',
                    ];
                    break;
            }
        }

        $params = array_merge([
            'condition' => [
			    ['re_properties.status', 'NOT_IN', [PropertyStatusEnum::NOT_AVAILABLE]],
                're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            'order_by'  => [
                're_properties.created_at' => 'desc',
            ],
            'take'      => null,
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => 1,
            ],
            'select'    => [
                're_properties.*',
            ],
            'with'      => [],
        ], $params);

        $withCount = [];
        if (is_review_enabled()) {
            $withCount = [
                'reviews',
                'reviews as reviews_avg' => function ($query) {
                    $query->select(DB::raw('avg(star)'));
                },
            ];
        }

        $params['withCount'] = $withCount;

        $params['order_by'] = $orderBy;

        $params['condition'] = $condition;

        $this->model = $this->originalModel->notExpired()
            ->soldNotExpired()
            ->available();

        if ($filters['keyword'] !== null) {
            $this->model = $this->model
                ->where(function (Builder $query) use ($filters) {
                    return $query
                        ->where('re_properties.name', 'LIKE', '%' . $filters['keyword'] . '%')
                        ->orWhere('re_properties.location', 'LIKE', '%' . $filters['keyword'] . '%');
                });
        }

        if ($filters['is_top_property'] !== null) {
            $this->model = $this->model
                ->where(function (Builder $query) use ($filters) {
                    return $query
                        ->where('re_properties.is_top_property',$filters['is_top_property']);
                });
        }
		
		if ($filters['is_urgent'] !== null) {
            $this->model = $this->model
                ->where(function (Builder $query) use ($filters) {
                    return $query
                        ->where('re_properties.is_urgent',$filters['is_urgent']);
                });
        }

        if ($filters['type'] !== null) {
            $this->model = $this->model->whereHas('type', function (Builder $q) use ($filters) {
                if (is_array($filters['type'])) {
                    $q->whereIn('re_property_types.slug', $filters['type']);
                } else {
                    $q->where('re_property_types.slug', $filters['type']);
                }
            });
        }

        if ($filters['min_price'] !== null || $filters['max_price'] !== null) {
            $this->model = $this->model
                ->where(function ($query) use ($filters) {

                    $minPrice = Arr::get($filters, 'min_price');
                    $maxPrice = Arr::get($filters, 'max_price');

                    /**
                     * @var Builder $query
                     */
                    if ($minPrice !== null) {
                        $query = $query->where('re_properties.price', '>=', $minPrice);
                    }

                    if ($maxPrice !== null) {
                        $query = $query->where('re_properties.price', '<=', $maxPrice);
                    }

                    return $query;
                });
        }

        if (!empty($filters['category_id'])) {
            $this->model = $this->model->where('re_properties.category_id', $filters['category_id']);
        }

        if (!empty($filters['subcategory_id'])) {
            $this->model = $this->model->where('re_properties.subcategory_id', $filters['subcategory_id']);
        }

        if ($filters['is_featured'] !== null) {
            $this->model = $this->model->where('re_properties.is_featured', $filters['is_featured']);
        }

        if ($filters['author_id'] !== null) {
            $this->model = $this->model->where('re_properties.author_id', $filters['author_id']);
        }

        if ($filters['features'] !== null) {
            $this->model = $this->model->whereHas('features', function (Builder $q) use ($filters) {
                $q->whereIn('re_features.id', $filters['features']);
            });
        }

        if (!empty($filters['city_id'])) {
            $this->model = $this->model->where('re_properties.city_id', $filters['city_id']);
        }

        if (!empty($filters['state_id'])) {
            $this->model = $this->model->where('state_id', $filters['state_id']);
        }

        if (!empty($filters['country_id'])) {
            $this->model = $this->model->where('country_id', $filters['country_id']);
        }

        if ($filters['location']) {
            $locationData = explode(',', $filters['location']);
            if (count($locationData) > 1) {
                $this->model = $this->model
                    ->leftJoin('cities', 'cities.id', '=', 're_properties.city_id')
                    ->leftJoin('states', 'states.id', '=', 'cities.state_id')
                    ->where(function ($query) use ($locationData) {
                        return $query
                            ->where('cities.name', 'LIKE', '%' . trim($locationData[0]) . '%')
                            ->orWhere('states.name', 'LIKE', '%' . trim($locationData[0]) . '%');
                    });
            } else {
                $this->model = $this->model
                    ->leftJoin('cities', 'cities.id', '=', 're_properties.city_id')
                    ->leftJoin('states', 'states.id', '=', 'cities.state_id')
                    ->where(function ($query) use ($filters) {
                        return $query
                            ->where('cities.name', 'LIKE', '%' . trim($filters['location']) . '%')
                            ->orWhere('states.name', 'LIKE', '%' . trim($filters['location']) . '%');
                    });
            }
        }

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperty(int $propertyId, array $with = [])
    {
        $params = [
            'condition' => [
                're_properties.id'                => $propertyId,
                're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            'with'      => $with,
            'take'      => 1,
        ];
        $withCount = [];
        if (is_review_enabled()) {
            $withCount = [
                'reviews',
                'reviews as reviews_avg' => function ($query) {
                    $query->select(DB::raw('avg(star)'));
                },
            ];
        }
        $params['withCount'] = $withCount;
        $this->model = $this->originalModel->notExpired()
            ->soldNotExpired()
            ->available();

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertiesByConditions(array $condition, $limit, array $with = [], array $withCount = [], array $orderBy = [], $paginate = [])
    {
        $this->model = $this->originalModel->notExpired()
            ->soldNotExpired()
            ->available();

        $params = [
            'condition' => $condition,
            'with'      => $with,
            'take'      => $limit,
            'withCount' => $withCount,
            'order_by'  => $orderBy,
            'paginate'  => $paginate,
        ];

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getAccountPropertiesCount($authorId)
    {
        $this->model = $this->originalModel->notExpired()
            ->soldNotExpired();
        $count = $this->model->where('author_id', $authorId)->count();
        return $count;
    }

    /**
     * {@inheritDoc}
     */
    public function getAccountFeaturedPropertiesCount($authorId)
    {
        $this->model = $this->originalModel->notExpired()
            ->soldNotExpired()
            ->available();
        $count = $this->model->where('author_id', $authorId)->where('is_featured', 1)->count();
        return $count;
    }

    /**
     * {@inheritDoc}
     */
    public function getAccountTopPropertiesCount($authorId)
    {
        $this->model = $this->originalModel->notExpired()
            ->soldNotExpired()
            ->available();
        $count = $this->model->where('author_id', $authorId)->where('is_top_property', 1)->count();
        return $count;
    }

    /**
     * {@inheritDoc}
     */
    public function getAccountUrgentPropertiesCount($authorId)
    {
        $this->model = $this->originalModel->notExpired()
            ->soldNotExpired()
            ->available();
        $count = $this->model->where('author_id', $authorId)->where('is_urgent', 1)->count();
        return $count;
    }

    /**
     * {@inheritDoc}
     */

    public function getAccountSoldProperties(array $condition, $limit, array $with = [], array $withCount = [], array $orderBy = [], array $paginate = [])
    {
        $this->model = $this->originalModel->soldExpired();

        $params = [
            'condition' => $condition,
            'with'      => $with,
            'take'      => $limit,
            'withCount' => $withCount,
            'order_by'  => $orderBy,
            'paginate'  => $paginate,
        ];

        return $this->advancedGet($params);
    }
}
