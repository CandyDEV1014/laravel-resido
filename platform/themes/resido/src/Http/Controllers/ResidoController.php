<?php

namespace Theme\Resido\Http\Controllers;

use App;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\RepositoryHelper;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Location\Models\City;
use Botble\Location\Models\State;
use Botble\Location\Models\Country;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\RealEstate\Enums\PropertyTypeEnum;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Models\Property;
use Botble\Blog\Models\Post;
use Botble\RealEstate\Repositories\Interfaces\AccountInterface;
use Botble\RealEstate\Repositories\Interfaces\CategoryInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\RealEstate\Repositories\Interfaces\ReviewInterface;
use Botble\RealEstate\Repositories\Interfaces\TypeInterface;
use Botble\Slug\Repositories\Interfaces\SlugInterface;
use Botble\Testimonial\Repositories\Interfaces\TestimonialInterface;
use Botble\Theme\Http\Controllers\PublicController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SeoHelper;
use SlugHelper;
use Theme;
use Theme\Resido\Http\Resources\AgentHTMLResource;
use Theme\Resido\Http\Resources\PostResource;
use Theme\Resido\Http\Resources\PropertyHTMLResource;
use Theme\Resido\Http\Resources\PropertyResource;
use Theme\Resido\Http\Resources\ReviewResource;
use Theme\Resido\Http\Resources\TestimonialResource;
use Botble\Base\Supports\Language;

class ResidoController extends PublicController
{
    /**
     * @param string            $key
     * @param Request           $request
     * @param SlugInterface     $slugRepository
     * @param CityInterface     $cityRepository
     * @param PropertyInterface $propertyRepository
     * @param CategoryInterface $categoryRepository
     * @return \Response
     */
    public function getPropertiesByCity(
        string            $key,
        Request           $request,
        SlugInterface     $slugRepository,
        CityInterface     $cityRepository,
        PropertyInterface $propertyRepository,
        CategoryInterface $categoryRepository
    )
    {
        $city = $cityRepository->getFirstBy([
            'slug'      => $key,
        ]);
        // dd($key);
        if (!$city) {
            abort(404);
        }

        $filters = [
            'city_id' => $city->id,
        ];

        SeoHelper::setTitle(__('Properties in :city', ['city' => $city->name]));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(SeoHelper::getTitle(), $city->url);

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, CITY_MODULE_SCREEN_NAME, $city);

        $params = [
            'paginate' => [
                'per_page' => (int)theme_option('number_of_properties_per_page', 12),
                'current_paged' => (int)$request->input('page', 1),
            ],
            'order_by' => ['re_properties.created_at' => 'DESC'],
        ];

        $properties = $propertyRepository->getProperties($filters, $params);

        $categories = $categoryRepository->pluck('re_categories.name', 're_categories.id');

        return Theme::scope('real-estate.properties', compact('properties', 'categories'))
            ->render();
    }

    /**
     * @param Request          $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ajaxGetProperties(Request $request, BaseHttpResponse $response)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $properties = [];
        $with = config('plugins.real-estate.real-estate.properties.relations');
        $withCount = [];
        if (is_review_enabled()) {
            $withCount = [
                'reviews',
                'reviews as reviews_avg' => function ($query) {
                    $query->select(DB::raw('avg(star)'));
                },
            ];
        }
        switch ($request->input('type')) {
            case 'related':
                $properties = app(PropertyInterface::class)
                    ->getRelatedProperties(
                        $request->input('property_id'),
                        (int)theme_option('number_of_related_properties', 8),
                        $with
                    );
                break;
            case 'rent':
                $properties = app(PropertyInterface::class)->getPropertiesByConditions(
                    [
                        're_properties.is_featured' => true,
                        're_properties.type' => PropertyTypeEnum::RENT,
						// ['re_properties.status', 'NOT_IN', [PropertyStatusEnum::NOT_AVAILABLE]],
                        // 're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
                    ],
                    (int)theme_option('number_of_properties_for_rent', 8),
                    $with,
                    $withCount
                );
                break;

            case 'sale':
                $properties = app(PropertyInterface::class)->getPropertiesByConditions(
                    [
                        're_properties.is_featured' => true,
                        're_properties.type' => PropertyTypeEnum::SALE,
						// ['re_properties.status', 'NOT_IN', [PropertyStatusEnum::NOT_AVAILABLE]],
                        // 're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
                    ],
                    (int)theme_option('number_of_properties_for_sale', 8),
                    $with,
                    $withCount
                );
                break;

            case 'recently-viewed-properties':
                $cookieName = App::getLocale() . '_recently_viewed_properties';
                $jsonRecentViewProduct = null;

                if (isset($_COOKIE[$cookieName])) {
                    $jsonRecentViewProduct = $_COOKIE[$cookieName];
                }

                if (!empty($jsonRecentViewProduct)) {
                    $ids = collect(json_decode($jsonRecentViewProduct, true))->flatten()->all();

                    $properties = app(PropertyInterface::class)->getPropertiesByConditions(
                        [
                            ['re_properties.id', 'IN', $ids],
							// ['re_properties.status', 'NOT_IN', [PropertyStatusEnum::NOT_AVAILABLE]],
                            // 're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
                        ],
                        (int)theme_option('number_of_recently_viewed_properties', 6),
                        $with,
                        $withCount
                    );

                    $reversed = array_reverse($ids);

                    $properties = $properties->sortBy(function ($model) use ($reversed) {
                        return array_search($model->id, $reversed);
                    });
                }
                break;
        }

        return $response
            ->setData(PropertyHTMLResource::collection($properties))
            ->toApiResponse();
    }

    /**
     * @param Request          $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ajaxGetPropertiesForMap(Request $request, BaseHttpResponse $response)
    {
        $params = [
            'with' => config('plugins.real-estate.real-estate.properties.relations'),
            'paginate' => [
                'per_page' => 20,
                'current_paged' => (int)$request->input('page', 1),
            ],
        ];

        $properties = app(PropertyInterface::class)->getProperties(['type' => $request->input('type')], $params);

        return $response
            ->setData(PropertyResource::collection($properties))
            ->toApiResponse();
    }

    /**
     * @param Request          $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|RedirectResponse|JsonResource
     */
    public function ajaxGetPosts(Request $request, BaseHttpResponse $response)
    {
        if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $posts = app(PostInterface::class)->getFeatured(4, ['slugable', 'categories', 'categories.slugable']);

        return $response
            ->setData(PostResource::collection($posts))
            ->toApiResponse();
    }

    /**
     * @param Request          $request
     * @param AccountInterface $accountRepository
     * @return \Response
     */
    public function getAgents(Request $request, AccountInterface $accountRepository)
    {
        $params = [
            'paginate' => [
                'per_page' => 12,
                'current_paged' => (int)$request->input('page'),
            ],
            'withCount' => [
                'properties' => function ($query) {
                    return RepositoryHelper::applyBeforeExecuteQuery($query, $query->getModel())->available();
                }
            ],
        ];
        
        $accounts = $accountRepository->getAgents($params);

        SeoHelper::setTitle(__('Agents'));
        Theme::breadcrumb()->add(__('Home'), route('public.index'))->add(__('Agents'), route('public.agents'));

        return Theme::scope('real-estate.agents', compact('accounts'))->render();
    }

    /**
     * @param string            $username
     * @param Request           $request
     * @param AccountInterface  $accountRepository
     * @param PropertyInterface $propertyRepository
     * @return \Response
     */
    public function getAgent(
        string $username,
        Request $request,
        AccountInterface $accountRepository,
        PropertyInterface $propertyRepository
    ) 
    {
    	$account = $accountRepository->getFirstBy(['username' => $username]);

        if (!$account) {
            abort(404);
        }

        SeoHelper::setTitle($account->name);

        $propertyTypes = app(TypeInterface::class)->all();
        $propertiesRelated = [];
        $totalProperties = 0;

        foreach ($propertyTypes as $propertyType) {
            $properties = $propertyRepository->getPropertiesByConditions(
                [
                    'author_id' => $account->id,
                    'author_type' => Account::class,
                    'type_id' => $propertyType->id,
                ],
                [],
                config('plugins.real-estate.real-estate.properties.relations'),
                [],
                [],
                [
                    'per_page' => 12,
                    'current_paged' => (int)$request->input('page'),
                ]
            );

            $propertiesRelated[] = [
                'type' => $propertyType,
                'properties' => $properties,
            ];

            $totalProperties += $properties->count();
        }

        $soldProperties = $propertyRepository->getAccountSoldProperties(
            [
                'author_id' => $account->id,
                'author_type' => Account::class,
            ],
            [],
            config('plugins.real-estate.real-estate.properties.relations'),
            [],
            [],
            [
                'per_page' => 12,
                'current_paged' => (int)$request->input('page'),
            ]
        );
        $totalProperties += $soldProperties->count();

        return Theme::scope('real-estate.agent', compact('propertiesRelated', 'soldProperties', 'totalProperties', 'account'))
            ->render();
    }

    /**
     * @param Request          $request
     * @param CityInterface    $cityRepository
     * @param BaseHttpResponse $response
     * @return mixed
     */
    public function ajaxGetCities(Request $request, CityInterface $cityRepository, BaseHttpResponse $response)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $keyword = $request->input('q');

        $cities = $cityRepository->filters($keyword, 10, [], ['cities.*', 'states.name as state_name']);

        return $response->setData($cities)->toApiResponse();
    }

    /**
     * @param Request $request
     * @return Response|\Response
     */
    public function getWishlist(Request $request, PropertyInterface $propertyRepository)
    {
        SeoHelper::setTitle(__('Wishlist'))
            ->setDescription(__('Wishlist'));

        $cookieName = App::getLocale() . '_wishlist';
        $jsonWishlist = null;
        if (isset($_COOKIE[$cookieName])) {
            $jsonWishlist = $_COOKIE[$cookieName];
        }

        $properties = collect([]);

        if (!empty($jsonWishlist)) {
            $arrValue = collect(json_decode($jsonWishlist, true))->flatten()->all();
            $properties = $propertyRepository->advancedGet([
                'condition' => [
                    ['re_properties.id', 'IN', $arrValue],
                ],
                'order_by' => [
                    're_properties.id' => 'DESC',
                ],
                'paginate' => [
                    'per_page' => (int)theme_option('number_of_properties_per_page', 12),
                    'current_paged' => (int)$request->input('page', 1),
                ],
                'with' => config('plugins.real-estate.real-estate.properties.relations'),
            ]);
        }

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Wishlist'));

        return Theme::scope('real-estate.wishlist', compact('properties'))->render();
    }

    /**
     * @param Request          $request
     * @param BaseHttpResponse $response
     * @param AccountInterface $accountRepository
     * @return BaseHttpResponse|\Illuminate\Http\JsonResponse|RedirectResponse|JsonResource
     */
    public function ajaxGetFeaturedAgents(
        Request $request,
        BaseHttpResponse $response,
        AccountInterface $accountRepository
    ) 
    {
    	if (!$request->ajax()) {
            abort(404);
        }

        $accounts = $accountRepository->advancedGet([
            'condition' => [
                're_accounts.is_featured' => true,
            ],
            'order_by' => [
                're_accounts.id' => 'DESC',
            ],
            'take' => 4,
            'withCount' => [
                'properties' => function ($query) {
                    return RepositoryHelper::applyBeforeExecuteQuery($query, $query->getModel());
                }
            ],
        ]);

        return $response
            ->setData(AgentHTMLResource::collection($accounts))
            ->toApiResponse();
    }

    /**
     * @param Request              $request
     * @param BaseHttpResponse     $response
     * @param TestimonialInterface $testimonialRepository
     * @return BaseHttpResponse
     */
    public function ajaxGetTestimonials(
        Request $request,
        BaseHttpResponse $response,
        TestimonialInterface $testimonialRepository
    ) 
    {
    	if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $testimonials = $testimonialRepository->allBy(['status' => BaseStatusEnum::PUBLISHED]);

        return $response->setData(TestimonialResource::collection($testimonials));
    }

    /**
     * @param Request          $request
     * @param BaseHttpResponse $response
     * @param ReviewInterface  $reviewRepository
     * @return BaseHttpResponse
     */
    public function ajaxGetRealEstateReviews(
        $id,
        Request $request,
        BaseHttpResponse $response,
        ReviewInterface $reviewRepository
    ) 
    {
    	$reviews = $reviewRepository->advancedGet([
            'condition' => [
                'status' => BaseStatusEnum::PUBLISHED,
                'reviewable_type' => $request->input('reviewable_type', Property::class),
                'reviewable_id' => $id,
            ],
            'order_by' => ['created_at' => 'desc'],
            'paginate' => [
                'per_page' => (int)$request->input('per_page', 10),
                'current_paged' => (int)$request->input('page', 1),
            ],
        ]);

        return $response->setData(ReviewResource::collection($reviews))->toApiResponse();
    }

    /**
     * @param Request          $request
     * @param BaseHttpResponse $response
     * @param ReviewInterface  $reviewRepository
     * @return BaseHttpResponse
     */
    public function ajaxGetPostReviews(
        $id,
        Request $request,
        BaseHttpResponse $response,
        ReviewInterface $reviewRepository
    ) 
    {
    	$reviews = $reviewRepository->advancedGet([
            'condition' => [
                'status' => BaseStatusEnum::PUBLISHED,
                'reviewable_type' => $request->input('reviewable_type', Post::class),
                'reviewable_id' => $id,
            ],
            'order_by' => ['created_at' => 'desc'],
            'paginate' => [
                'per_page' => (int)$request->input('per_page', 10),
                'current_paged' => (int)$request->input('page', 1),
            ],
        ]);

        return $response->setData(ReviewResource::collection($reviews))->toApiResponse();
    }

    /**
     * @param Request          $request
     * @param BaseHttpResponse $response
     * @param ReviewInterface  $reviewRepository
     * @return BaseHttpResponse
     */
    public function ajaxGetRealEstateRating(
        $id,
        Request $request,
        BaseHttpResponse $response,
        ReviewInterface $reviewRepository
    ) 
    {
    	if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $rating = $reviewRepository->advancedGet([
            'condition' => [
                'status' => BaseStatusEnum::PUBLISHED,
                'reviewable_type' => $request->input('reviewable_type', Property::class),
                'reviewable_id' => $id,
            ],
            'withCount' => [
                'meta as service_avg' => function ($query) {
                    $query->select(DB::raw('avg(value)'))->where('key', 'service');
                },
                'meta as value_avg' => function ($query) {
                    $query->select(DB::raw('avg(value)'))->where('key', 'value');
                },
                'meta as location_avg' => function ($query) {
                    $query->select(DB::raw('avg(value)'))->where('key', 'location');
                },
                'meta as cleanliness_avg' => function ($query) {
                    $query->select(DB::raw('avg(value)'))->where('key', 'cleanliness');
                },
            ],
            'take' => 1,
        ]);
        if (empty($rating)) {
            return $response->setData([
                'message' => __('No review found')
            ])->toApiResponse();
        }
        $dataRating = [
            'summary_avg' => [
                'cleanliness' => $rating['cleanliness_avg'],
                'location' => $rating['location_avg'],
                'service' => $rating['service_avg'],
                'value' => $rating['value_avg'],
            ],
            'star' => $rating['star'],
        ];

        return $response->setData($dataRating)->toApiResponse();
    }

    /**
     * @param Request           $request
     * @param CategoryInterface $categoryRepository
     * @param BaseHttpResponse  $response
     * @return mixed
     */
    public function ajaxGetSubCategories(Request $request, CategoryInterface $categoryRepository, BaseHttpResponse $response)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        // $categories = $categoryRepository->allBy([
        //     'parent_id' => $request->input('id')
        // ]);

        $categories = $categoryRepository->getSubcategories($request->input('id'));
        
        return $response->setData($categories)->toApiResponse();
    }

    /**
     * @param Request          $request
     * @param StateInterface   $stateRepository
     * @param BaseHttpResponse $response
     * @return mixed
     */
    public function ajaxGetStatesByCountry(Request $request, StateInterface $stateRepository, BaseHttpResponse $response)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        // $states = $stateRepository->allBy([
        //     'country_id' => $request->input('id')
        // ]);
        $states = $stateRepository->getStatesByCountry($request->input('id'));

        return $response->setData($states)->toApiResponse();
    }

    /**
     * @param Request          $request
     * @param CityInterface    $cityRepository
     * @param BaseHttpResponse $response
     * @return mixed
     */
    public function ajaxGetCitiesByState(Request $request, CityInterface $cityRepository, BaseHttpResponse $response)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        // $cities = $cityRepository->allBy([
        //     'state_id' => $request->input('id')
        // ]);

        $cities = $cityRepository->getCitiesByState($request->input('id'));

        return $response->setData($cities)->toApiResponse();
    }
}
