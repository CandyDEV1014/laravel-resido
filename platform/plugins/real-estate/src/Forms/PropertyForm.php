<?php

namespace Botble\RealEstate\Forms;

use Assets;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyPeriodEnum;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\RealEstate\Enums\PropertyTypeEnum;
use Botble\RealEstate\Forms\Fields\CategoryMultiField;
use Botble\RealEstate\Http\Requests\PropertyRequest;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Repositories\Interfaces\CategoryInterface;
use Botble\RealEstate\Repositories\Interfaces\CurrencyInterface;
use Botble\RealEstate\Repositories\Interfaces\FacilityInterface;
use Botble\RealEstate\Repositories\Interfaces\FeatureInterface;
use Botble\RealEstate\Repositories\Interfaces\DetailInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\RealEstate\Repositories\Interfaces\TypeInterface;
use RealEstateHelper;
use Throwable;

class PropertyForm extends FormAbstract
{
    /**
     * @var FacilityInterface
     */
    protected $facilityRepository;

    /**
     * @var PropertyInterface
     */
    protected $propertyRepository;

    /**
     * @var DetailInterface
     */
    protected $detailRepository;

    /**
     * @var FeatureInterface
     */
    protected $featureRepository;

    /**
     * @var CurrencyInterface
     */
    protected $currencyRepository;

    /**
     * @var CountryInterface
     */
    protected $countryRepository;

    /**
     * @var StateInterface
     */
    protected $stateRepository;

    /**
     * @var CityInterface
     */
    protected $cityRepository;

    /**
     * @var CategoryInterface
     */
    protected $categoryRepository;

    /**
     * @var TypeInterface
     */
    protected $typeRepository;

    /**
     * PropertyForm constructor.
     * @param PropertyInterface $propertyRepository
     * @param DetailInterface $featureRepository
     * @param FeatureInterface $featureRepository
     * @param CurrencyInterface $currencyRepository
     * @param CountryInterface $countryRepository
     * @param StateInterface $stateRepository
     * @param CityInterface $cityRepository
     * @param CategoryInterface $categoryRepository
     * @param TypeInterface $typeRepository
     * @param FacilityInterface $facilityRepository
     */
    public function __construct(
        PropertyInterface $propertyRepository,
        DetailInterface $detailRepository,
        FeatureInterface $featureRepository,
        CurrencyInterface $currencyRepository,
        CountryInterface $countryRepository,
        StateInterface $stateRepository,
        CityInterface $cityRepository,
        CategoryInterface $categoryRepository,
        TypeInterface $typeRepository,
        FacilityInterface $facilityRepository
    ) {
        parent::__construct();
        $this->propertyRepository = $propertyRepository;
        $this->detailRepository = $detailRepository;
        $this->featureRepository = $featureRepository;
        $this->currencyRepository = $currencyRepository;
        $this->countryRepository = $countryRepository;
        $this->stateRepository = $stateRepository;
        $this->cityRepository = $cityRepository;
        $this->categoryRepository = $categoryRepository;
        $this->facilityRepository = $facilityRepository;
        $this->typeRepository = $typeRepository;
    }

    /**
     * @return mixed|void
     * @throws Throwable
     */
    public function buildForm()
    {
        Assets::addStyles(['datetimepicker'])
            ->addScripts(['input-mask'])
            ->addScriptsDirectly([
                'vendor/core/plugins/real-estate/js/real-estate.js',
                'vendor/core/plugins/real-estate/js/components.js',
            ])
            ->addStylesDirectly('vendor/core/plugins/real-estate/css/real-estate.css');

        $currencies = $this->currencyRepository->pluck('re_currencies.title', 're_currencies.id');
        
        // get all countries
        $countries = $this->countryRepository->all();

        $countryChoices = [];
        foreach ($countries as $country) {
            $countryChoices[$country->id] = $country->name ? $country->name : '';
        }

        // get states by country
        $stateChoices = [];
        if ($this->getModel()) {
            $states = $this->stateRepository->getStatesByCountry($this->getModel()->country_id ? $this->getModel()->country_id : 0);
            foreach ($states as $state) {
                $stateChoices[$state->id] = $state->name ? $state->name : '';
            }
        } 

        // get cities by state
        
        $cityChoices = [];
        if ($this->getModel()) {
            $cities = $this->cityRepository->getCitiesByState($this->getModel()->state_id ? $this->getModel()->state_id : 0);
            foreach ($cities as $city) {
                $cityChoices[$city->id] = $city->name ? $city->name : '';
            }
        }
        
        // get all categories
        $categories = $this->categoryRepository->all();
        $categoryChoices = [];
        foreach ($categories as $category) {
            $categoryChoices[$category->id] = $category->name ? $category->name : '';
        }

        // get subcategories by category
        $subcategoryChoices = [];

        if ($this->getModel()) {
            $subcategories = $this->categoryRepository->getSubcategories($this->getModel()->category_id ? $this->getModel()->category_id : 0);
            foreach ($subcategories as $subcategory) {
                $subcategoryChoices[$subcategory->id] = $subcategory->name ? $subcategory->name : '';
            }
        } else {
            $subcategories = $this->categoryRepository->getSubcategories(array_key_first($categoryChoices));
            foreach ($subcategories as $subcategory) {
                $subcategoryChoices[$subcategory->id] = $subcategory->name ? $subcategory->name : '';
            }
        }

        $types = $this->typeRepository->all();
        $type_id = null;
        if ($this->getModel()) {
            $type_id = $this->getModel()->type()->pluck('re_property_types.id')->first();
        }

        $selectedDetails = [];
        if ($this->getModel()) {
            $selectedDetails = $this->getModel()->details()->pluck('re_property_details.value', 're_details.id')->all();
        }

        $details = $this->detailRepository->allBy([], [], ['re_details.id', 're_details.name', 're_details.type', 're_details.order', 're_details.features']);

        $selectedFeatures = [];
        if ($this->getModel()) {
            $selectedFeatures = $this->getModel()->features()->pluck('re_features.id')->all();
        }

        $features = $this->featureRepository->allBy([], [], ['re_features.id', 're_features.name']);

        $facilities = $this->facilityRepository->allBy([], [], ['re_facilities.id', 're_facilities.name']);
        $selectedFacilities = [];
        if ($this->getModel()) {
            $selectedFacilities = $this->getModel()->facilities()->select('re_facilities.id', 'distance')->get();
        }

        $this
            ->setupModel(new Property)
            ->setValidatorClass(PropertyRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('plugins/real-estate::property.form.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::property.form.name'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label'      => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 4,
                    'placeholder'  => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 350,
                ],
            ])
            ->add('content', 'editor', [
                'label'      => trans('core/base::forms.content'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'rows'            => 4,
                    'with-short-code' => true,
                ],
            ])
            ->add('images[]', 'mediaImages', [
                'label'      => trans('plugins/real-estate::property.form.images'),
                'label_attr' => ['class' => 'control-label'],
                'values'     => $this->getModel()->id ? $this->getModel()->images : [],
            ])
			->add('country_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.country'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => $countryChoices,
            ])
			->add('state_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.state'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                    'data-url' => route('public.ajax.stateses-by-country'),
                    'data-placeholder' => __('State'),
                    'placeholder' => __('State'),
                ],
                'choices'    => $stateChoices,
            ])
            ->add('city_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.city'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                    'data-url' => route('public.ajax.cities-by-state'),
                    'data-placeholder' => __('City'),
                    'placeholder' => __('City'),
                ],
                'choices'    => $cityChoices,
            ])
            ->add('location', 'text', [
                'label'      => trans('plugins/real-estate::property.form.location'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/real-estate::property.form.location'),
                    'data-counter' => 300,
                ],
            ])
            ->add('rowOpen', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('latitude', 'text', [
                'label'      => trans('plugins/real-estate::property.form.latitude'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => 'Ex: 1.462260',
                    'data-counter' => 25,
                ],
                'help_block' => [
                    'tag'  => 'a',
                    'text' => trans('plugins/real-estate::property.form.latitude_helper'),
                    'attr' => [
                        'href'   => 'https://www.latlong.net/convert-address-to-lat-long.html',
                        'target' => '_blank',
                        'rel'    => 'nofollow',
                    ],
                ],
            ])
            ->add('longitude', 'text', [
                'label'      => trans('plugins/real-estate::property.form.longitude'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                'attr'       => [
                    'placeholder'  => 'Ex: 103.812530',
                    'data-counter' => 25,
                ],
                'help_block' => [
                    'tag'  => 'a',
                    'text' => trans('plugins/real-estate::property.form.longitude_helper'),
                    'attr' => [
                        'href'   => 'https://www.latlong.net/convert-address-to-lat-long.html',
                        'target' => '_blank',
                        'rel'    => 'nofollow',
                    ],
                ],
            ])
            ->add('rowClose', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('price', 'text', [
                'label'      => trans('plugins/real-estate::property.form.price'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr'       => [
                    'id'          => 'price-number',
                    'placeholder' => trans('plugins/real-estate::property.form.price'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('currency_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.currency'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => $currencies,
            ])
            ->add('period', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.period'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group period-form-group mb-3 col-md-4' . ($this->getModel()->type->slug != PropertyTypeEnum::RENT ? ' hidden' : null),
                ],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => PropertyPeriodEnum::labels(),
            ])
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])
            ->add('never_expired', 'onOff', [
                'label'         => trans('plugins/real-estate::property.never_expired'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => true,
            ])
            ->add('auto_renew', 'onOff', [
                'label'         => trans('plugins/real-estate::property.renew_notice',
                    ['days' => RealEstateHelper::propertyExpiredDays()]),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper'       => [
                    'class' => 'form-group auto-renew-form-group' . (!$this->getModel()->id || $this->getModel()->never_expired == true ? ' hidden' : null),
                ],
            ])
            ->add('is_urgent', 'onOff', [
                'label'         => trans('core/base::forms.is_urgent'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('is_featured', 'onOff', [
                'label'         => trans('core/base::forms.is_featured'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('is_top_property', 'onOff', [
                'label'         => trans('core/base::forms.is_top_property'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->addMetaBoxes([
                'details'   => [
                    'title'    => trans('plugins/real-estate::property.form.details'),
                    'content'  => view('plugins/real-estate::partials.form-details',
                        compact('selectedDetails', 'details'))->render(),
                    'priority' => 0,
                ],
                'type_id'   => [
                    'title'    => trans('plugins/real-estate::property.form.type'),
                    'content'  => view('plugins/real-estate::partials.form-types',
                        compact('types','type_id'))->render(),
                    'priority' => 1,
                ],
                'features'   => [
                    'title'    => trans('plugins/real-estate::property.form.features'),
                    'content'  => view('plugins/real-estate::partials.form-features',
                        compact('selectedFeatures', 'features'))->render(),
                    'priority' => 2,
                ],
                'facilities' => [
                    'title'    => trans('plugins/real-estate::property.distance_key'),
                    'content'  => view('plugins/real-estate::partials.form-facilities',
                        compact('facilities', 'selectedFacilities')),
                    'priority' => 3,
                ],
            ])
			->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => PropertyStatusEnum::labels(),
            ])
            ->add('moderation_status', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.moderation_status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => ModerationStatusEnum::labels(),
            ])
            ->add('category_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.category'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                ],
                'choices'    => $categoryChoices,
            ])
			->add('subcategory_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.subcategory'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                    'data-url' => route('public.ajax.sub-categories'),
                    'data-placeholder' => __('Subcategory'),
                    'placeholder' => __('Subcategory'),
                ],
                'choices'    => $subcategoryChoices,
            ])
            ->setBreakFieldPoint('status')
            ->add('author_id', 'autocomplete', [
                'label'      => trans('plugins/real-estate::property.account'),
                'label_attr' => [
                    'class' => 'control-label',
                ],
                'attr'       => [
                    'id'       => 'author_id',
                    'data-url' => route('account.list'),
                ],
                'choices'    => $this->getModel()->author_id ?
                    [
                        $this->model->author->id => $this->model->author->name,
                    ]
                    :
                    ['' => trans('plugins/real-estate::property.select_account')],
            ]);
    }
}
