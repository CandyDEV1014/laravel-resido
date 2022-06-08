<?php

namespace Botble\RealEstate\Forms;

use Assets;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\RealEstate\Forms\Fields\CustomEditorField;
use Botble\RealEstate\Forms\Fields\MultipleUploadField;
use Botble\RealEstate\Http\Requests\AccountPropertyRequest;
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
use Botble\RealEstate\Models\AccountPackage;
use Botble\RealEstate\Repositories\Interfaces\CategoryInterface;
use Botble\RealEstate\Repositories\Interfaces\CurrencyInterface;
use Botble\RealEstate\Repositories\Interfaces\FacilityInterface;
use Botble\RealEstate\Repositories\Interfaces\FeatureInterface;
use Botble\RealEstate\Repositories\Interfaces\DetailInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\RealEstate\Repositories\Interfaces\TypeInterface;
use Botble\RealEstate\Repositories\Interfaces\AccountPackageInterface;
use RealEstateHelper;
use Throwable;

class AccountPropertyForm extends FormAbstract
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
     * @var AccountPackageInterface
     */
    protected $accountPackageRepository;

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
     * @param AccountPackageInterface $accountPackageRepository
     * 
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
        FacilityInterface $facilityRepository,
        AccountPackageInterface $accountPackageRepository
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
        $this->accountPackageRepository = $accountPackageRepository;
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
            ->addStylesDirectly('vendor/core/plugins/real-estate/css/real-estate.css')
            ->addScriptsDirectly('vendor/core/core/base/libraries/tinymce/tinymce.min.js');
        

        if (!$this->formHelper->hasCustomField('customEditor')) {
            $this->formHelper->addCustomField('customEditor', CustomEditorField::class);
        }
        
        if (!$this->formHelper->hasCustomField('multipleUpload')) {
            $this->formHelper->addCustomField('multipleUpload', MultipleUploadField::class);
        }

        $currencies = $this->currencyRepository->pluck('re_currencies.title', 're_currencies.id');

        // get all countries
        $countries = $this->countryRepository->allBy(['status' => BaseStatusEnum::PUBLISHED]);

        $countryChoices = [];
        foreach ($countries as $country) {
            $countryChoices[$country->id] = $country->name ? $country->name : '';
        }

        // get states by country
        $stateChoices = [];
        if ($this->getModel() && isset($this->getModel()->country_id)) {
            $states = $this->stateRepository->getStatesByCountry($this->getModel()->country_id ? $this->getModel()->country_id : 0);
            foreach ($states as $state) {
                $stateChoices[$state->id] = $state->name ? $state->name : '';
            }
        } else {
            $states = $this->stateRepository->getStatesByCountry(array_key_first($countryChoices) ? array_key_first($countryChoices) : 0);
            foreach ($states as $state) {
                $stateChoices[$state->id] = $state->name ? $state->name : '';
            }
        }

        // get cities by state
        
        $cityChoices = [];
        if ($this->getModel() && isset($this->getModel()->state_id)) {
            $cities = $this->cityRepository->getCitiesByState($this->getModel()->state_id ? $this->getModel()->state_id : 0);
            foreach ($cities as $city) {
                $cityChoices[$city->id] = $city->name ? $city->name : '';
            }
        } else {
            $cities = $this->cityRepository->getCitiesByState(array_key_first($stateChoices) ? array_key_first($stateChoices) : 0);
            foreach ($cities as $city) {
                $cityChoices[$city->id] = $city->name ? $city->name : '';
            }
        }
        
        // get all categories
        $categories = $this->categoryRepository->allBy(['parent_id' => 0, 'status' => BaseStatusEnum::PUBLISHED]);
        $categoryChoices = [];
        foreach ($categories as $category) {
            $categoryChoices[$category->id] = $category->name ? $category->name : '';
        }

        // get subcategories by category
        $subcategoryChoices = [];

        if ($this->getModel() && isset($this->getModel()->category_id)) {
            $subcategories = $this->categoryRepository->getSubcategories($this->getModel()->category_id ? $this->getModel()->category_id : 0);
            foreach ($subcategories as $subcategory) {
                $subcategoryChoices[$subcategory->id] = $subcategory->name ? $subcategory->name : '';
            }
        } else {
            $subcategories = $this->categoryRepository->getSubcategories(array_key_first($categoryChoices) ? array_key_first($categoryChoices) : 0);
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
            // $selectedDetails = $this->getModel()->details()->pluck('re_property_details.value', 're_details.id')->all();
            $selectedAllDetails = $this->getModel()->details()->get();
            foreach ($selectedAllDetails as $detail) {
                $id = $detail->id;
                $value = [
                    'value' => $detail->pivot->value,
                    'value2' => $detail->pivot->value2
                ];
                $selectedDetails[$id] = $value;
            }
        }

        // $details = $this->detailRepository->allBy([], [], ['re_details.id', 're_details.name', 're_details.type', 're_details.order', 're_details.features']);
        if ($this->getModel()){
            $details = $this->detailRepository->getDetailsByCategory($this->getModel()->category_id);
        } else {
            $details = $this->detailRepository->getDetailsByCategory(array_key_first($categoryChoices));
        }
        
        $selectedFeatures = [];
        if ($this->getModel()) {
            $selectedFeatures = $this->getModel()->features()->pluck('re_features.id')->all();
        }

        $features = $this->featureRepository->allBy([], [], ['re_features.id', 're_features.name']);

        $activePackage = $this->accountPackageRepository->getActivePackage(auth('account')->id());
        $limit_features = isset($activePackage['number_of_aminities']) ? $activePackage['number_of_aminities'] : 0;
        $limit_facilities = isset($activePackage['number_of_nearestplace']) ? $activePackage['number_of_nearestplace'] : 0;
        $limit_photo = isset($activePackage['number_of_photo']) ? $activePackage['number_of_photo'] : 0;
        
        $limit_featured = !isset($activePackage['is_allow_featured']) ? 0 : ($activePackage['number_of_featured'] == -1 ? null : $activePackage['number_of_featured']);
        $limit_top = !isset($activePackage['is_allow_top']) ? 0 : ($activePackage['number_of_top'] == -1 ? null : $activePackage['number_of_top']);
        $limit_urgent = !isset($activePackage['is_allow_urgent']) ? 0 : ($activePackage['number_of_urgent'] == -1 ? null : $activePackage['number_of_urgent']);
        $count_featured = $this->propertyRepository->getAccountFeaturedPropertiesCount(auth('account')->id());
        $count_top = $this->propertyRepository->getAccountTopPropertiesCount(auth('account')->id());
        $count_urgent = $this->propertyRepository->getAccountUrgentPropertiesCount(auth('account')->id());
        $is_auto_renew = isset($activePackage['is_auto_renew']) && $activePackage['is_auto_renew'] ? $activePackage['is_auto_renew'] : 0;

        $facilities = $this->facilityRepository->allBy([], [], ['re_facilities.id', 're_facilities.name']);
        $selectedFacilities = [];
        if ($this->getModel()) {
            $selectedFacilities = $this->getModel()->facilities()->select('re_facilities.id', 'distance')->get();
        }
        
        $this
            ->setupModel(new Property)
            ->setFormOption('template', 'plugins/real-estate::account.forms.base')
            ->setFormOption('enctype', 'multipart/form-data')
            ->setValidatorClass(AccountPropertyRequest::class)
            ->setActionButtons(view('plugins/real-estate::account.forms.actions')->render())
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
            ->add('content', 'customEditor', [
                'label'      => trans('core/base::forms.content'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'rows' => 4,
                ],
            ])
            ->add('images', 'multipleUpload', [
                'label'      => trans('plugins/real-estate::property.form.images'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'data-max' => $limit_photo
                ]
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
            ->add('never_expired', 'hidden', [
                'label'         => trans('plugins/real-estate::property.never_expired'),
                'label_attr'    => ['class' => 'control-label'],
                'value'         => isset($is_auto_renew) && $is_auto_renew ? 1 : 0,
            ])
            
            ->add('divOpen', 'html', [
                'html' => '<div style="display: flex;">'
            ])
            ->add('auto_renew', 'onOff', [
                'label'         => trans('plugins/real-estate::property.renew_notice', ['days' => RealEstateHelper::propertyExpiredDays()]),
                'label_attr'    => ['class' => ''],
                'default_value' => false,
                // 'attr'       => [
                //     'disabled' => $activePackage['is_auto_renew'] ? false : true,
                // ],
                'wrapper' => [
                    'class' => 'form-group ' . (isset($is_auto_renew) && $is_auto_renew ? 'hidden' : '')
                ]
            ])
            ->add('creditCostLabel', 'html', [
                'html' => '<p>' . trans('plugins/real-estate::property.renew_cost', ['credits' => RealEstateHelper::propertyRenewPrice()]) . '</p>',
                'wrapper' => [
                    'class' => 'renew_cost ' . ($this->getModel()->auto_renew == 1 ? ' ' : 'hidden')
                ]
            ])
            
            ->add('divClose', 'html', [
                'html' => '</div>'
            ])
            ->add('is_urgent', 'onOff', [
                'label'         => trans('core/base::forms.is_urgent'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper' => [
                    'class' => 'form-group ' . (isset($activePackage['is_allow_urgent']) && $activePackage['is_allow_urgent'] == 1 ? ($limit_urgent == null || $limit_urgent > $count_urgent ? '' : 'hidden') : 'hidden')
                ],
            ])
            ->add('is_featured', 'onOff', [
                'label'         => trans('core/base::forms.is_featured'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper' => [
                    'class' => 'form-group ' . (isset($activePackage['is_allow_featured']) && $activePackage['is_allow_featured'] == 1 ? ($limit_featured == null || $limit_featured > $count_featured ? '' : 'hidden') : 'hidden')
                ],
            ])
            ->add('is_top_property', 'onOff', [
                'label'         => trans('core/base::forms.is_top_property'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper' => [
                    'class' => 'form-group ' . (isset($activePackage['is_allow_top']) && $activePackage['is_allow_top'] == 1 ? ($limit_top == null || $limit_top > $count_top ? '' : 'hidden') : 'hidden')
                ],
            ])
            ->add('selected_detail', 'hidden', [
                'value' => json_encode($selectedDetails),
                'attr' => [
                    'id' => 'selected_detail'
                ]
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
                        compact('selectedFeatures', 'features', 'limit_features'))->render(),
                    'priority' => 2,
                ],
                'facilities' => [
                    'title'    => trans('plugins/real-estate::property.distance_key'),
                    'content'  => view('plugins/real-estate::partials.form-facilities',
                        compact('facilities', 'selectedFacilities', 'limit_facilities')),
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
            ->add('category_id', 'customSelect', [
                'label'      => trans('plugins/real-estate::property.form.category'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'form-control select-search-full',
                    'data-url' => route('public.ajax.details'),
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
            ->setBreakFieldPoint('status');
    }
}