<?php

namespace Botble\RealEstate\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Forms\PropertyForm;
use Botble\RealEstate\Http\Requests\PropertyRequest;
use Botble\RealEstate\Http\Requests\PropertyAddRequest;
use Botble\RealEstate\Repositories\Interfaces\DetailInterface;
use Botble\RealEstate\Repositories\Interfaces\FeatureInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\RealEstate\Services\SaveFacilitiesService;
use Botble\RealEstate\Tables\PropertyTable;
use Botble\RealEstate\Models\Account;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RealEstateHelper;
use Throwable;
use Botble\RealEstate\Models\Review;

class PropertyController extends BaseController
{
    /**
     * @var PropertyInterface $propertyRepository
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
     * PropertyController constructor.
     * @param PropertyInterface $propertyRepository
     * @param DetailInterface $detailRepository
     * @param FeatureInterface $featureRepository
     */
    public function __construct(
        PropertyInterface $propertyRepository,
        DetailInterface $detailRepository,
        FeatureInterface $featureRepository
    ) {
        $this->propertyRepository = $propertyRepository;
        $this->detailRepository = $detailRepository;
        $this->featureRepository = $featureRepository;
    }

    /**
     * @param PropertyTable $dataTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function index(PropertyTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/real-estate::property.name'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/real-estate::property.create'));

        return $formBuilder->create(PropertyForm::class)->renderForm();
    }

    /**
     * @param PropertyRequest $request
     * @param BaseHttpResponse $response
     * @param SaveFacilitiesService $saveFacilitiesService
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     */
    public function store(PropertyAddRequest $request, BaseHttpResponse $response, SaveFacilitiesService $saveFacilitiesService)
    {
        $request->merge([
            'expire_date' => now()->addDays(RealEstateHelper::propertyExpiredDays()),
            'images'      => json_encode(array_filter($request->input('images', []))),
            'author_type' => Account::class
        ]);

        $property = $this->propertyRepository->getModel();
        $property = $property->fill($request->input());
        $property->moderation_status = $request->input('moderation_status');
        $property->never_expired = $request->input('never_expired');
        $property->save();

        event(new CreatedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));

        if ($property) {
            $property->features()->sync($request->input('features', []));
            $property->details()->sync($request->input('details', []));
            
            $saveFacilitiesService->execute($property, $request->input('facilities', []));
        }

        return $response
            ->setPreviousUrl(route('property.index'))
            ->setNextUrl(route('property.edit', $property->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, Request $request, FormBuilder $formBuilder)
    {
        $property = $this->propertyRepository->findOrFail($id, ['features', 'author']);
        page_title()->setTitle(trans('plugins/real-estate::property.edit') . ' "' . $property->name . '"');

        event(new BeforeEditContentEvent($request, $property));

        return $formBuilder->create(PropertyForm::class, ['model' => $property])->renderForm();
    }

    /**
     * @param int $id
     * @param PropertyRequest $request
     * @param BaseHttpResponse $response
     * @param SaveFacilitiesService $facilitiesService
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     */
    public function update($id, PropertyAddRequest $request, BaseHttpResponse $response, SaveFacilitiesService $saveFacilitiesService)
    {
        $property = $this->propertyRepository->findOrFail($id);

        do_action(ACTION_BEFORE_UPDATE_PROPERTY, $request, $property);

        $property->fill($request->except(['expire_date']));
        $property->author_type = Account::class;
        $property->images = json_encode(array_filter($request->input('images', [])));
        $property->moderation_status = $request->input('moderation_status');
        $property->never_expired = $request->input('never_expired');

        $this->propertyRepository->createOrUpdate($property);

        event(new UpdatedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));

        $property->features()->sync($request->input('features', []));
        $property->details()->sync($request->input('details', []));
        
        $saveFacilitiesService->execute($property, $request->input('facilities', []));

        return $response
            ->setPreviousUrl(route('property.index'))
            ->setNextUrl(route('property.edit', $property->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy($id, Request $request, BaseHttpResponse $response)
    {
        try {
            Review::where('reviewable_id', $id)->delete();
            $property = $this->propertyRepository->findOrFail($id);
            $property->features()->detach();
            $this->propertyRepository->delete($property);

            event(new DeletedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.cannot_delete'));
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $property = $this->propertyRepository->findOrFail($id);
            $property->features()->detach();
            $this->propertyRepository->delete($property);

            event(new DeletedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
