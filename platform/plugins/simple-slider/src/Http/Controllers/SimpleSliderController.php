<?php

namespace Botble\SimpleSlider\Http\Controllers;

use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\SimpleSlider\Forms\SimpleSliderForm;
use Botble\SimpleSlider\Http\Requests\SimpleSliderRequest;
use Botble\SimpleSlider\Repositories\Interfaces\SimpleSliderInterface;
use Botble\Base\Http\Controllers\BaseController;
use Botble\SimpleSlider\Repositories\Interfaces\SimpleSliderItemInterface;
use Illuminate\Http\Request;
use Exception;
use Botble\SimpleSlider\Tables\SimpleSliderTable;

class SimpleSliderController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var SimpleSliderInterface
     */
    protected $simpleSliderRepository;

    /**
     * @var SimpleSliderItemInterface
     */
    protected $simpleSliderItemRepository;

    /**
     * SimpleSliderController constructor.
     * @param SimpleSliderInterface $simpleSliderRepository
     * @param SimpleSliderItemInterface $simpleSliderItemRepository
     */
    public function __construct(
        SimpleSliderInterface $simpleSliderRepository,
        SimpleSliderItemInterface $simpleSliderItemRepository
    )
    {
        $this->simpleSliderRepository = $simpleSliderRepository;
        $this->simpleSliderItemRepository = $simpleSliderItemRepository;
    }

    /**
     * @param SimpleSliderTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(SimpleSliderTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/simple-slider::simple-slider.menu'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/simple-slider::simple-slider.create'));

        return $formBuilder
            ->create(SimpleSliderForm::class)
            ->removeMetaBox('slider-items')
            ->renderForm();
    }

    /**
     * @param SimpleSliderRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(SimpleSliderRequest $request, BaseHttpResponse $response)
    {
        $simpleSlider = $this->simpleSliderRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SIMPLE_SLIDER_MODULE_SCREEN_NAME, $request, $simpleSlider));

        return $response
            ->setPreviousUrl(route('simple-slider.index'))
            ->setNextUrl(route('simple-slider.edit', $simpleSlider->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        Assets::addScripts(['blockui', 'sortable'])
            ->addScriptsDirectly(['vendor/core/plugins/simple-slider/js/simple-slider-admin.js']);

        $simpleSlider = $this->simpleSliderRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $simpleSlider));

        page_title()->setTitle(trans('plugins/simple-slider::simple-slider.edit') . ' "' . $simpleSlider->name . '"');

        return $formBuilder
            ->create(SimpleSliderForm::class, ['model' => $simpleSlider])
            ->renderForm();
    }

    /**
     * @param $id
     * @param SimpleSliderRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, SimpleSliderRequest $request, BaseHttpResponse $response)
    {
        $simpleSlider = $this->simpleSliderRepository->findOrFail($id);
        $simpleSlider->fill($request->input());

        $this->simpleSliderRepository->createOrUpdate($simpleSlider);

        event(new UpdatedContentEvent(SIMPLE_SLIDER_MODULE_SCREEN_NAME, $request, $simpleSlider));

        return $response
            ->setPreviousUrl(route('simple-slider.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param $id
     * @param BaseHttpResponse $response
     * @return array|BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $simpleSlider = $this->simpleSliderRepository->findOrFail($id);
            $this->simpleSliderRepository->delete($simpleSlider);

            event(new DeletedContentEvent(SIMPLE_SLIDER_MODULE_SCREEN_NAME, $request, $simpleSlider));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return array|BaseHttpResponse|\Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->simpleSliderRepository, SIMPLE_SLIDER_MODULE_SCREEN_NAME);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postSorting(Request $request, BaseHttpResponse $response)
    {
        foreach ($request->input('items', []) as $key => $id) {
            $this->simpleSliderItemRepository->createOrUpdate(['order' => ($key + 1)], ['id' => $id]);
        }

        return $response->setMessage(trans('plugins/simple-slider::simple-slider.update_slide_position_success'));
    }
}
