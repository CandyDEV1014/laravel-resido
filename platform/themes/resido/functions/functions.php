<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Location\Models\City;
use Botble\Location\Models\Country;
use Botble\Location\Models\State;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Repositories\Interfaces\FeatureInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Theme\Events\RenderingSiteMapEvent;
use Theme\Resido\Http\Requests\CityRequest;
use Theme\Resido\Http\Requests\CountryRequest;
use Theme\Resido\Http\Requests\StateRequest;
use Botble\Theme\Supports\Youtube;
use Theme\Resido\Forms\Fields\ThemeIconField;
use Botble\RealEstate\Repositories\Interfaces\AccountInterface;
use Botble\Base\Supports\RepositoryHelper;

register_page_template([
    'homepage' => __('Homepage'),
]);

register_sidebar([
    'id'          => 'footer_sidebar_1',
    'name'        => __('Footer sidebar 1'),
    'description' => __('Footer sidebar 1 for Resido theme'),
]);

register_sidebar([
    'id'          => 'footer_sidebar_2',
    'name'        => __('Footer sidebar 2'),
    'description' => __('Footer sidebar 2 for Resido theme'),
]);

register_sidebar([
    'id'          => 'footer_sidebar_3',
    'name'        => __('Footer sidebar 3'),
    'description' => __('Footer sidebar 3 for Resido theme'),
]);

register_sidebar([
    'id'          => 'footer_sidebar_4',
    'name'        => __('Footer sidebar 4'),
    'description' => __('Footer sidebar 4 for Resido theme'),
]);

register_sidebar([
    'id'          => 'property_sidebar',
    'name'        => __('Property sidebar'),
    'description' => __('Property sidebar for Resido theme'),
]);

Event::listen(RenderingSiteMapEvent::class, function () {
    if (is_plugin_active('real-estate')) {
        $properties = app(PropertyInterface::class)->advancedGet([
            'condition' => [
			    ['re_properties.status', 'NOT_IN', [PropertyStatusEnum::NOT_AVAILABLE]],
                're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            'with'      => ['slugable'],
        ]);

        SiteMapManager::add(route('public.properties'), '2021-07-25 00:00:00', '0.4', 'monthly');

        foreach ($properties as $property) {
            SiteMapManager::add($property->url, $property->updated_at, '0.8');
        }
    }
});

RvMedia::setUploadPathAndURLToPublic();
RvMedia::addSize('large', 1024)
    ->addSize('medium_large', 640)
    ->addSize('property_large', 'auto', 610)
    ->addSize('medium', 400);

if (!function_exists('get_repeat_field')) {
    /**
     * @return string|array
     */
    function get_repeat_field($fieldName)
    {
        $field = json_decode(theme_option($fieldName), true);

        return is_array($field) ? $field : [];
    }
}

if (!function_exists('get_featured_properties')) {
    function get_featured_properties($limit)
    {
        $withCount = [];
        if (is_review_enabled()) {
            $withCount = [
                'reviews',
                'reviews as reviews_avg' => function ($query) {
                    $query->select(DB::raw('avg(star)'));
                },
            ];
        }

        return app(PropertyInterface::class)->getPropertiesByConditions(
            [
                're_properties.is_featured'       => true,
                // 're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            (int)$limit,
            config('plugins.real-estate.real-estate.properties.relations'),
            $withCount
        );
    }
}

/**
 * @return Object
 */
function get_object_property_map()
{
    return (object)[
        'name'            => '__name__',
        'type_name'       => '__type_name__',
        'type_slug'       => '__type_slug__',
        'url'             => '__url__',
        'city_name'       => '__city_name__',
        'square_text'     => '__square_text__',
        'number_bedroom'  => '__number_bedroom__',
        'number_bathroom' => '__number_bathroom__',
        'image_thumb'     => '__image_thumb__',
        'price_html'      => '__price_html__',
    ];
}

app()->booted(function () {
    if (is_plugin_active('real-estate')) {
        $videoSupportModels = [Property::class];
        add_action(BASE_ACTION_META_BOXES, function ($context, $object) use ($videoSupportModels) {
            if (in_array(get_class($object), $videoSupportModels) && $context == 'advanced') {
                MetaBox::addMetaBox('additional_property_fields', __('Addition Information'), function () {
                    $videoThumbnail = null;
                    $videoUrl = null;
                    $args = func_get_args();
                    if (!empty($args[0])) {
                        $videoThumbnail = $args[0]->video_thumbnail;
                        $videoUrl = $args[0]->video_url;
                    }

                    return Theme::partial('additional-property-fields', compact('videoThumbnail', 'videoUrl'));
                }, get_class($object), $context);
            }
        }, 28, 2);

        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, function ($type, $request, $object) use ($videoSupportModels) {
            if (in_array(get_class($object), $videoSupportModels) && $request->has('video')) {
                $data = Arr::only((array)$request->input('video', []), ['url']);
                if (!empty($request->input('video_thumbnail'))) {
                    $data['thumbnail'] = $request->input('video_thumbnail');
                }

                if ($request->hasFile('thumbnail_input')) {
                    $result = RvMedia::handleUpload($request->file('thumbnail_input'), 0, 'properties');
                    if ($result['error'] == false) {
                        $file = $result['data'];
                        $data['thumbnail'] = $file->url;
                    }
                }

                MetaBox::saveMetaBoxData($object, 'video', $data);
            }
        }, 280, 3);

        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, function ($type, $request, $object) use ($videoSupportModels) {
            if (in_array(get_class($object), $videoSupportModels) && $request->has('video')) {
                $data = Arr::only((array)$request->input('video', []), ['url']);
                if (!empty($request->input('video_thumbnail'))) {
                    $data['thumbnail'] = $request->input('video_thumbnail');
                }

                if ($request->hasFile('thumbnail_input')) {
                    $result = RvMedia::handleUpload($request->file('thumbnail_input'), 0, 'properties');
                    if ($result['error'] == false) {
                        $file = $result['data'];
                        $data['thumbnail'] = $file->url;
                    }
                }

                MetaBox::saveMetaBoxData($object, 'video', $data);
            }
        }, 281, 3);

        // yes or no is okay
        add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, function ($screen, $object) use ($videoSupportModels) {
            if (in_array(get_class($object), $videoSupportModels)) {
                $object->loadMissing(['meta_boxes']);
            }
        }, 56, 2);

        foreach ($videoSupportModels as $supportModel) {
            $supportModel::resolveRelationUsing('meta_boxes', function ($model) {
                return $model->morphMany(MetaBoxModel::class, 'reference')
                    ->select(['reference_id', 'meta_key', 'meta_value']);
            });

            MacroableModels::addMacro($supportModel, 'getVideoThumbnailAttribute', function () {
                /**
                 * @var BaseModel $this
                 */
                if ($this->meta_boxes) {
                    $fistMeta = $this->meta_boxes->firstWhere('meta_key', 'video');

                    if ($fistMeta) {
                        return Arr::get(Arr::first($fistMeta->meta_value), 'thumbnail');
                    }
                }

                return '';
            });

            MacroableModels::addMacro($supportModel, 'getVideoUrlAttribute', function () {
                /**
                 * @var BaseModel $this
                 */
                if ($this->meta_boxes) {
                    $fistMeta = $this->meta_boxes->firstWhere('meta_key', 'video');

                    if ($fistMeta) {
                        $url = Arr::get(Arr::first($fistMeta->meta_value), 'url');

                        if ($url) {
                            return Youtube::getYoutubeWatchURL($url);
                        }
                    }
                }

                return '';
            });
        }


        add_action(BASE_ACTION_META_BOXES, 'add_addition_fields_in_property_screen', 30, 3);

        function add_addition_fields_in_property_screen($context, $object)
        {
            if (get_class($object) == Property::class && $context == 'top') {
                MetaBox::addMetaBox(
                    'additional_property_fields',
                    __('Custom layout'),
                    function () {
                        $headerLayout = null;
                        $args = func_get_args();
                        if (!empty($args[0])) {
                            $headerLayout = MetaBox::getMetaData($args[0], 'header_layout', true);
                        }

                        return Theme::partial('additional-property-fields-top', compact('headerLayout'));
                    },
                    get_class($object),
                    $context
                );
            }
        }

        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, 'save_addition_property_fields', 230, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, 'save_addition_property_fields', 231, 3);

        function save_addition_property_fields($type, $request, $object)
        {
            if (get_class($object) == Property::class && $request->has('header_layout')) {
                MetaBox::saveMetaBoxData($object, 'header_layout', $request->input('header_layout'));
            }
        }
    }

    if (setting('social_login_enable', false)) {
        remove_filter(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM);
        add_filter(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, 'addLoginOptionsByTheme', 25);
    }
});

/**
 * @param Property $property
 * @return string
 */
function get_image_from_video_property(BaseModel $object)
{
    $video = $object->getMetaData('video', true);
    $videoUrl = $video['url'] ?? '';
    $videoThumbnail = $video['thumbnail'] ?? '';

    if ($videoThumbnail) {
        return RvMedia::getImageUrl($videoThumbnail);
    }

    $videoID = Youtube::getYoutubeVideoID($videoUrl);

    if ($videoID) {
        return 'https://img.youtube.com/vi/' . $videoID . '/hqdefault.jpg';
    }

    return RvMedia::getDefaultImage();
}

if (is_plugin_active('location')) {
    SeoHelper::registerModule(City::class);

    add_filter(BASE_FILTER_BEFORE_RENDER_FORM, 'add_addition_fields_into_form', 127, 2);

    /**
     * @param \Botble\Base\Forms\FormAbstract $form
     * @param $data
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    function add_addition_fields_into_form($form, $data)
    {
        switch (get_class($data)) {
            case City::class:
                $image = MetaBox::getMetaData($data, 'image', true);
                $form
                    ->setValidatorClass(CityRequest::class)
                    ->addAfter('status', 'image', 'mediaImage', [
                        'label'      => trans('core/base::forms.image'),
                        'label_attr' => ['class' => 'control-label'],
                        'value'      => $image,
                    ]);
                break;
            case \Botble\RealEstate\Models\Facility::class:
            case \Botble\RealEstate\Models\Feature::class:
            case \Botble\RealEstate\Models\Detail::class:
                $form
                    ->addCustomField('themeIcon', ThemeIconField::class)
                    ->modify('icon', 'themeIcon', ['label' => __('Icon')], true);
                break;
        }

        return $form;
    }

    add_action(BASE_ACTION_AFTER_CREATE_CONTENT, function ($type, $request, $object) {
        if (get_class($object) == City::class && $request->has('image')) {
            MetaBox::saveMetaBoxData($object, 'image', $request->input('image'));
        }
    }, 230, 3);

    add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, function ($type, $request, $object) {
        if (get_class($object) == City::class && $request->has('image')) {
            MetaBox::saveMetaBoxData($object, 'image', $request->input('image'));
        }
    }, 231, 3);

    City::resolveRelationUsing('properties', function ($model) {
        return $model->hasMany(Property::class);
    });
}


if (!function_exists('get_single_header_templates')) {
    /**
     * @return array
     */
    function get_single_header_layout(): array
    {
        return [
            'layout-1' => __('Layout 1'),
            'layout-2' => __('Layout 2'),
            'layout-3' => __('Layout 3'),
        ];
    }
}

if (!function_exists('get_properties_page_layout')) {
    /**
     * @return array
     */
    function get_properties_page_layout(): array
    {
        return [
            'sidebar'  => __('Left sidebar'),
            'full'     => __('Full width'),
            'map'      => __('Full map'),
            'half_map' => __('Half map'),
            'grid_map' => __('Grid map'),
            'grid_full' => __('Grid full'),
        ];
    }
}

if (!function_exists('get_feature_all')) {
    /**
     * @return array
     */
    function get_feature_all(): array
    {
        return app(FeatureInterface::class)->allBy([], [], ['re_features.id', 're_features.name'])->toArray();
    }
}

if (!function_exists('get_properties_featured')) {
    /**
     * @return object
     */
    function get_properties_featured($limit = 6, $type = ''): object
    {
        $condition = [
            'is_featured' => true,
            // 'moderation_status' => ModerationStatusEnum::APPROVED,
        ];

        if($type != '') {
            $condition['type_id'] = $type;
        }

        $withCount = [];
        if (is_review_enabled()) {
            $withCount = [
                'reviews',
                'reviews as reviews_avg' => function ($query) {
                    $query->select(DB::raw('avg(star)'));
                },
            ];
        }

        return app(PropertyInterface::class)->getPropertiesByConditions(
            $condition,
            (int)$limit,
            config('plugins.real-estate.real-estate.properties.relations'),
            $withCount,
            ['re_properties.updated_at' => 'desc']
        );
    }
}

if (!function_exists('get_properties')) {
    /**
     * @return object
     */
    function get_properties($limit = 6): object
    {
        $withCount = [];
        if (is_review_enabled()) {
            $withCount = [
                'reviews',
                'reviews as reviews_avg' => function ($query) {
                    $query->select(DB::raw('avg(star)'));
                },
            ];
        }

        return app(PropertyInterface::class)->getPropertiesByConditions(
            [
                'is_top_property' => true,
                // 'moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            (int)$limit,
            config('plugins.real-estate.real-estate.properties.relations'),
            $withCount,
            ['re_properties.updated_at' => 'desc']
        );
    }
}

if (!function_exists('get_properties_new')) {
    /**
     * @return object
     */
    function get_properties_new($limit = 6): object
    {
        $withCount = [];
        if (is_review_enabled()) {
            $withCount = [
                'reviews',
                'reviews as reviews_avg' => function ($query) {
                    $query->select(DB::raw('avg(star)'));
                },
            ];
        }

        return app(PropertyInterface::class)->getPropertiesByConditions(
            [
                // 'moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            (int)$limit,
            config('plugins.real-estate.real-estate.properties.relations'),
            $withCount,
            ['re_properties.updated_at' => 'desc']
        );
    }
}

if (!function_exists('get_properties_sale')) {
    /**
     * @return object
     */
    function get_properties_sale($limit = 3): object
    {
        $withCount = [];
        if (is_review_enabled()) {
            $withCount = [
                'reviews',
                'reviews as reviews_avg' => function ($query) {
                    $query->select(DB::raw('avg(star)'));
                },
            ];
        }

        return app(PropertyInterface::class)->getPropertiesByConditions(
            [
                'type_id' => 1,
                // 'moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            (int)$limit,
            config('plugins.real-estate.real-estate.properties.relations'),
            $withCount,
            ['re_properties.updated_at' => 'desc']
        );
    }
}

if (!function_exists('get_properties_rent')) {
    /**
     * @return object
     */
    function get_properties_rent($limit = 3): object
    {
        $withCount = [];
        if (is_review_enabled()) {
            $withCount = [
                'reviews',
                'reviews as reviews_avg' => function ($query) {
                    $query->select(DB::raw('avg(star)'));
                },
            ];
        }

        return app(PropertyInterface::class)->getPropertiesByConditions(
            [
                'type_id' => 2,
                // 'moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            (int)$limit,
            config('plugins.real-estate.real-estate.properties.relations'),
            $withCount,
            ['re_properties.updated_at' => 'desc']
        );
    }
}

if (!function_exists('get_agents')) {
    /**
     * @return object
     */
    function get_agents(): object
    {
        $number_of_featured_agents = (int) theme_option('number_of_featured_agents');
        $params = [
            'take' => (int) theme_option('number_of_featured_agents', 4),
            'withCount' => [
                'properties' => function ($query) {
                    return RepositoryHelper::applyBeforeExecuteQuery($query, $query->getModel());
                }
            ],

        ];
        
        

        // return app(AccountInterface::class)->advancedGet($params);
        // $getCountData       = count(app(AccountInterface::class)->advancedGet());
        // $getAllData         = app(AccountInterface::class)->advancedGet();
        // $getRandomData      = collect($getAllData);
        // return $getRandomData->shuffle();

        $accounts = app(AccountInterface::class)->getAgents($params);
        $getRandomData = collect($accounts);
        return $getRandomData->shuffle();
    }
}

Form::component('themeIcon', Theme::getThemeNamespace() . '::partials.forms.fields.icons-field', [
    'name',
    'value'      => null,
    'attributes' => [],
]);

/**
 * @param string $html
 * @param string $module
 * @return null|string
 * @throws \Throwable
 */
function addLoginOptionsByTheme($html)
{
    if (Route::currentRouteName() == 'access.login') {
        if (defined('THEME_OPTIONS_MODULE_SCREEN_NAME')) {
            Theme::asset()
                ->usePath(false)
                ->add('social-login-css', asset('vendor/core/plugins/social-login/css/social-login.css'), [], [],
                    '1.0.0');
        }

        return $html . view('plugins/social-login::login-options')->render();
    }

    return $html . Theme::partial('login-options');
}

/**
 * @return null|string
 * @throws \Throwable
 */
function get_image_loading()
{
    return RvMedia::getImageUrl(theme_option('img_loading'));
}

if (!function_exists('get_countries')) {
    function get_countries()
    {
        return app(CountryInterface::class)->allBy(['status' => BaseStatusEnum::PUBLISHED]);
    }
}

if (!function_exists('get_states_by_country')) {
    function get_states_by_country($countryId)
    {
        return app(StateInterface::class)->allBy([
            'country_id' => $countryId,
            'status' => BaseStatusEnum::PUBLISHED
        ]);
    }
}

if (!function_exists('get_cities_by_state')) {
    function get_cities_by_state($stateId)
    {
        return app(CityInterface::class)->allBy([
            'state_id' => $stateId,
            'status' => BaseStatusEnum::PUBLISHED
        ]);
    }
}

if (!function_exists('get_re_categories')) {
    function get_re_categories($parentId = 0)
    {
        return app(Botble\RealEstate\Repositories\Interfaces\CategoryInterface::class)
            ->allBy([
                'parent_id' => $parentId,
                'status' => BaseStatusEnum::PUBLISHED
            ]);
    }
}

if (!function_exists('get_re_category')) {
    function get_re_category($id)
    {
        return app(Botble\RealEstate\Repositories\Interfaces\CategoryInterface::class)
            ->findById($id);
    }
}

if (!function_exists('get_account_package')) {
    /**
     * @return object
     */
    function get_account_package(): array
    {
        return app(Botble\RealEstate\Repositories\Interfaces\AccountPackageInterface::class)
            ->getActivePackage(auth('account')->id());
    }
}
