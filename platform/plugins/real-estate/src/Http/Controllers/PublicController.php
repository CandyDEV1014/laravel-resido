<?php

namespace Botble\RealEstate\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Http\Requests\SendConsultRequest;
use Botble\RealEstate\Models\Category;
use Botble\RealEstate\Models\Consult;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Repositories\Interfaces\CategoryInterface;
use Botble\RealEstate\Repositories\Interfaces\ConsultInterface;
use Botble\RealEstate\Repositories\Interfaces\CurrencyInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\SeoHelper\SeoOpenGraph;
use Botble\Slug\Repositories\Interfaces\SlugInterface;
use EmailHandler;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Mimey\MimeTypes;
use RssFeed;
use RvMedia;
use SeoHelper;
use SlugHelper;
use Spatie\Feed\Feed;
use Spatie\Feed\FeedItem;
use Theme;
use Throwable;

class PublicController extends Controller
{

    /**
     * @param SendConsultRequest $request
     * @param BaseHttpResponse $response
     * @param ConsultInterface $consultRepository
     * @param PropertyInterface $propertyRepository
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function postSendConsult(
        SendConsultRequest $request,
        BaseHttpResponse $response,
        ConsultInterface $consultRepository,
        PropertyInterface $propertyRepository
    ) 
    {
    	try {
            /**
             * @var Consult $consult
             */
            $consult = $consultRepository->getModel();

            $sendTo = null;
            $link = null;
            $subject = null;

            $request->merge(['property_id' => $request->input('data_id')]);
           
            $property = $propertyRepository->findById($request->input('data_id'), ['author']);
            if ($property) {
                $link = $property->url;
                $subject = $property->name;
               
                if ($property->author->email) {
                    $sendTo = $property->author->email;
                }
            }

            $consult->fill($request->input());
            $consultRepository->createOrUpdate($consult);

            EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'consult_name'    => $consult->name ?? 'N/A',
                    'consult_email'   => $consult->email ?? 'N/A',
                    'consult_phone'   => $consult->phone ?? 'N/A',
                    'consult_content' => $consult->content ?? 'N/A',
                    'consult_link'    => $link ?? 'N/A',
                    'consult_subject' => $subject ?? 'N/A',
                ])
                ->sendUsingTemplate('notice', $sendTo);

            return $response->setMessage(trans('plugins/real-estate::consult.email.success'));
        } catch (Exception $exception) {
            info($exception->getMessage());
            return $response
                ->setError()
                ->setMessage(trans('plugins/real-estate::consult.email.failed'));
        }
    }

    /**
     * @param string $key
     * @param SlugInterface $slugRepository
     * @param PropertyInterface $propertyRepository
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getProperty(string $key, SlugInterface $slugRepository, PropertyInterface $propertyRepository)
    {
        $slug = $slugRepository->getFirstBy([
            'slugs.key'      => $key,
            'reference_type' => Property::class,
            'prefix'         => SlugHelper::getPrefix(Property::class),
        ]);

        if (!$slug) {
            abort(404);
        }

        $property   = $propertyRepository->getProperty($slug->reference_id);

        $propertyGet = Property::find($slug->reference_id);

        $propertyGet->views = $propertyGet->views + 1;
        $propertyGet->save();

        $with       = config('plugins.real-estate.real-estate.properties.relations');
        $number_of_related_properties = (int) theme_option('number_of_related_properties', 4);
        $limit = $number_of_related_properties > 1 ? $number_of_related_properties : 4;
        $relatedProperty    = $propertyRepository->getRelatedProperties($property->id, $limit, $with);

        if (!$property) {
            abort(404);
        }

        $property->loadMissing(config('plugins.real-estate.real-estate.properties.relations'));

        if ($property->slugable->key !== $key) {
            return redirect()->to($property->url);
        }

        SeoHelper::setTitle($property->name)->setDescription(Str::words($property->description, 120));

        $meta = new SeoOpenGraph;
        if ($property->image) {
            $meta->setImage(RvMedia::getImageUrl($property->image));
        }
        $meta->setDescription($property->description);
        $meta->setUrl($property->url);
        $meta->setTitle($property->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add($property->name, $property->url);

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PROPERTY_MODULE_SCREEN_NAME, $property);

        if (function_exists('admin_bar')) {
            admin_bar()->registerLink(__('Edit this property'), route('property.edit', $property->id));
        }

        $images = [];
        foreach ($property->images as $image) {
            $images[] = RvMedia::getImageUrl($image, null, false, RvMedia::getDefaultImage());
        }

        return Theme::scope('real-estate.property', compact('property', 'images', 'relatedProperty'))->render();
    }

    /**
     * @param Request $request
     * @param PropertyInterface $propertyRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Response
     */
    public function getProperties(
        Request $request,
        PropertyInterface $propertyRepository,
        CategoryInterface $categoryRepository,
        BaseHttpResponse $response
    ) {
        SeoHelper::setTitle(__('Properties'));

        $perPage = (int)$request->input('per_page') ? (int)$request->input('per_page') : (int)theme_option('number_of_properties_per_page',
            12);

        if($request->input('type') == 'all'){
            $filters = [
                'keyword'     => $request->input('k'),
                'type'        => null,
                'bedroom'     => $request->input('bedroom'),
                'bathroom'    => $request->input('bathroom'),
                'floor'       => $request->input('floor'),
                'min_price'   => $request->input('min_price'),
                'max_price'   => $request->input('max_price'),
                'min_square'  => $request->input('min_square'),
                'max_square'  => $request->input('max_square'),
                'category_id' => $request->input('category_id'),
                'subcategory_id' => $request->input('subcategory_id'),
                'city'        => $request->input('city'),
                'country_id'     => $request->input('country_id'),
                'state_id'       => $request->input('state_id'),
                'city_id'     => $request->input('city_id'),
                'location'    => $request->input('location'),
                'features'    => $request->input('features'),
                'sort_by'     => $request->input('sort_by'),
                'author_id'   => $request->input('author_id'),
                'is_featured' => $request->input('is_featured'),
            ];
        }else{
            $filters = [
                'keyword'     => $request->input('k'),
                'type'        => $request->input('type'),
                'bedroom'     => $request->input('bedroom'),
                'bathroom'    => $request->input('bathroom'),
                'floor'       => $request->input('floor'),
                'min_price'   => $request->input('min_price'),
                'max_price'   => $request->input('max_price'),
                'min_square'  => $request->input('min_square'),
                'max_square'  => $request->input('max_square'),
                'category_id' => $request->input('category_id'),
                'subcategory_id' => $request->input('subcategory_id'),
                'city'        => $request->input('city'),
                'country_id'     => $request->input('country_id'),
                'state_id'       => $request->input('state_id'),
                'city_id'     => $request->input('city_id'),
                'location'    => $request->input('location'),
                'features'    => $request->input('features'),
                'sort_by'     => $request->input('sort_by'),
                'author_id'   => $request->input('author_id'),
                'is_featured' => $request->input('is_featured'),
            ];
        }

        $params = [
            'paginate' => [
                'per_page'      => $perPage ?: 12,
                'current_paged' => (int)$request->input('page', 1),
            ],
            'order_by' => ['re_properties.created_at' => 'DESC'],
            'with'     => config('plugins.real-estate.real-estate.properties.relations'),
        ];

        $properties = $propertyRepository->getProperties($filters, $params);

        if ($request->ajax()) {
            return $response->setData(Theme::partial('real-estate.properties.items', ['properties' => $properties]));
        }

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Properties'), route('public.properties'));

        $categories = $categoryRepository->pluck('name', 'id');

        return Theme::scope('real-estate.properties', compact('properties', 'categories'))->render();
    }

    public function getTopProperties(
        Request $request,
        PropertyInterface $propertyRepository,
        CategoryInterface $categoryRepository,
        BaseHttpResponse $response
    ) {
        SeoHelper::setTitle(__('Top Properties'));

        $perPage = (int)$request->input('per_page') ? (int)$request->input('per_page') : (int)theme_option('number_of_properties_per_page',
            12);

        $filters = [
            'keyword'     => $request->input('k'),
            'type'        => $request->input('type'),
            'bedroom'     => $request->input('bedroom'),
            'bathroom'    => $request->input('bathroom'),
            'floor'       => $request->input('floor'),
            'min_price'   => $request->input('min_price'),
            'max_price'   => $request->input('max_price'),
            'min_square'  => $request->input('min_square'),
            'max_square'  => $request->input('max_square'),
            'category_id' => $request->input('category_id'),
            'subcategory_id' => $request->input('subcategory_id'),
            'city'        => $request->input('city'),
            'country_id'     => $request->input('country_id'),
            'state_id'       => $request->input('state_id'),
            'city_id'     => $request->input('city_id'),
            'location'    => $request->input('location'),
            'features'    => $request->input('features'),
            'sort_by'     => $request->input('sort_by'),
            'author_id'   => $request->input('author_id'),
            'is_top_property' => '1',
        ];

        $params = [
            'paginate' => [
                'per_page'      => $perPage ?: 12,
                'current_paged' => (int)$request->input('page', 1),
            ],
            'order_by' => ['re_properties.created_at' => 'DESC'],
            'with'     => config('plugins.real-estate.real-estate.properties.relations'),
        ];

        $properties = $propertyRepository->getProperties($filters, $params);

        if ($request->ajax()) {
            return $response->setData(Theme::partial('real-estate.properties.items', ['properties' => $properties]));
        }

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Properties'), route('public.properties'));

        $categories = $categoryRepository->pluck('name', 'id');

        return Theme::scope('real-estate.properties', compact('properties', 'categories'))->render();
    }

    public function getFeaturedProperties(
        Request $request,
        PropertyInterface $propertyRepository,
        CategoryInterface $categoryRepository,
        BaseHttpResponse $response
    ) {
        SeoHelper::setTitle(__('Featured Properties'));

        $perPage = (int)$request->input('per_page') ? (int)$request->input('per_page') : (int)theme_option('number_of_properties_per_page',
            12);

        $filters = [
            'keyword'     => $request->input('k'),
            'type'        => $request->input('type'),
            'bedroom'     => $request->input('bedroom'),
            'bathroom'    => $request->input('bathroom'),
            'floor'       => $request->input('floor'),
            'min_price'   => $request->input('min_price'),
            'max_price'   => $request->input('max_price'),
            'min_square'  => $request->input('min_square'),
            'max_square'  => $request->input('max_square'),
            'category_id' => $request->input('category_id'),
            'subcategory_id' => $request->input('subcategory_id'),
            'city'        => $request->input('city'),
            'country_id'     => $request->input('country_id'),
            'state_id'       => $request->input('state_id'),
            'city_id'     => $request->input('city_id'),
            'location'    => $request->input('location'),
            'features'    => $request->input('features'),
            'sort_by'     => $request->input('sort_by'),
            'author_id'   => $request->input('author_id'),
            'is_featured' => '1',
        ];

        $params = [
            'paginate' => [
                'per_page'      => $perPage ?: 12,
                'current_paged' => (int)$request->input('page', 1),
            ],
            'order_by' => ['re_properties.created_at' => 'DESC'],
            'with'     => config('plugins.real-estate.real-estate.properties.relations'),
        ];

        $properties = $propertyRepository->getProperties($filters, $params);

        if ($request->ajax()) {
            return $response->setData(Theme::partial('real-estate.properties.items', ['properties' => $properties]));
        }

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Properties'), route('public.properties'));

        $categories = $categoryRepository->pluck('name', 'id');

        return Theme::scope('real-estate.properties', compact('properties', 'categories'))->render();
    }

    public function getNewProperties(
        Request $request,
        PropertyInterface $propertyRepository,
        CategoryInterface $categoryRepository,
        BaseHttpResponse $response
    ) {
        SeoHelper::setTitle(__('New Properties'));

        $perPage = (int)$request->input('per_page') ? (int)$request->input('per_page') : (int)theme_option('number_of_properties_per_page',
            12);

        $filters = [
            'keyword'     => $request->input('k'),
            'type'        => $request->input('type'),
            'bedroom'     => $request->input('bedroom'),
            'bathroom'    => $request->input('bathroom'),
            'floor'       => $request->input('floor'),
            'min_price'   => $request->input('min_price'),
            'max_price'   => $request->input('max_price'),
            'min_square'  => $request->input('min_square'),
            'max_square'  => $request->input('max_square'),
            'category_id' => $request->input('category_id'),
            'subcategory_id' => $request->input('subcategory_id'),
            'city'        => $request->input('city'),
            'country_id'     => $request->input('country_id'),
            'state_id'       => $request->input('state_id'),
            'city_id'     => $request->input('city_id'),
            'location'    => $request->input('location'),
            'features'    => $request->input('features'),
            'sort_by'     => $request->input('sort_by'),
            'author_id'   => $request->input('author_id'),
            'is_featured' => $request->input('is_featured'),
        ];

        $params = [
            'paginate' => [
                'per_page'      => $perPage ?: 12,
                'current_paged' => (int)$request->input('page', 1),
            ],
            'order_by' => ['re_properties.created_at' => 'DESC'],
            'with'     => config('plugins.real-estate.real-estate.properties.relations'),
        ];

        $properties = $propertyRepository->getProperties($filters, $params);

        if ($request->ajax()) {
            return $response->setData(Theme::partial('real-estate.properties.items', ['properties' => $properties]));
        }

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Properties'), route('public.properties'));

        $categories = $categoryRepository->pluck('name', 'id');

        return Theme::scope('real-estate.properties', compact('properties', 'categories'))->render();
    }

    /**
     * @param Request $request
     * @param PropertyInterface $propertyRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Response
     */
    public function getPropertyCategory(
        $key,
        Request $request,
        SlugInterface $slugRepository,
        PropertyInterface $propertyRepository,
        CategoryInterface $categoryRepository
    ) 
    {
        $slug = $slugRepository->getFirstBy([
            'slugs.key'      => $key,
            'reference_type' => Category::class,
            'prefix'         => SlugHelper::getPrefix(Category::class),
        ]);

        if (!$slug) {
            abort(404);
        }

        $category = $categoryRepository->getFirstBy(
            ['id' => $slug->reference_id],
            ['*'],
            ['slugable']
        );

        if (!$category) {
            abort(404);
        }

        SeoHelper::setTitle($category->name)->setDescription(Str::words($category->description, 120));

        $meta = new SeoOpenGraph;
        $meta->setDescription($category->description);
        $meta->setUrl($category->url);
        $meta->setTitle($category->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add($category->name, $category->url);

        $filters = [
            'category_id' => $category->id,
        ];

        $perPage = (int)theme_option('number_of_properties_per_page', 12);

        $params = [
            'paginate' => [
                'per_page'      => $perPage ?: 12,
                'current_paged' => (int)$request->input('page', 1),
            ],
            'order_by' => ['re_properties.created_at' => 'DESC'],
            'with'     => config('plugins.real-estate.real-estate.properties.relations'),
        ];

        $properties = $propertyRepository->getProperties($filters, $params);

        return Theme::scope('real-estate.property-category', compact('category', 'properties'))->render();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param null $title
     * @param CurrencyInterface $currencyRepository
     * @return BaseHttpResponse
     */
    public function changeCurrency(
        Request $request,
        BaseHttpResponse $response,
        CurrencyInterface $currencyRepository,
        $title = null
    ) 
    {        
    if (empty($title)) {
            $title = $request->input('currency');
        }

        if (!$title) {
            return $response;
        }

        $currency = $currencyRepository->getFirstBy(['title' => $title]);

        if ($currency) {
            cms_currency()->setApplicationCurrency($currency);
        }

        return $response;
    }

    /**
     * @param PropertyInterface $propertyRepository
     * @return Feed
     */
    public function getPropertyFeeds(PropertyInterface $propertyRepository)
    {
        if (!is_plugin_active('rss-feed')) {
            abort(404);
        }

        $data = $propertyRepository->getProperties([], [
            'take' => 20,
            'with' => ['slugable', 'category', 'author'],
        ]);

        $feedItems = collect([]);

        foreach ($data as $item) {
            $imageURL = RvMedia::getImageUrl($item->image, null, false, RvMedia::getDefaultImage());

            $feedItems[] = FeedItem::create()
                ->id($item->id)
                ->title(clean($item->name))
                ->summary(clean($item->description))
                ->updated($item->updated_at)
                ->enclosure($imageURL)
                ->enclosureType((new MimeTypes)->getMimeType(File::extension($imageURL)))
                ->enclosureLength(RssFeed::remoteFilesize($imageURL))
                ->category($item->category->name)
                ->link((string)$item->url)
                ->author($item->author_id ? $item->author->name : '');
        }

        return RssFeed::renderFeedItems($feedItems, 'Properties feed',
            'Latest properties from ' . theme_option('site_title'));
    }
}
