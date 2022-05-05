<?php

namespace Botble\SimpleSlider\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\SimpleSlider\Forms\SimpleSliderItemForm;
use Botble\SimpleSlider\Http\Requests\SimpleSliderItemRequest;
use Botble\SimpleSlider\Repositories\Interfaces\SimpleSliderItemInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\SimpleSlider\Tables\SimpleSliderItemTable;

class SimpleSliderItemController extends BaseController
{
    /**
     * @var SimpleSliderItemInterface
     */
    protected $simpleSliderItemRepository;

    /**
     * SimpleSliderItemController constructor.
     * @param SimpleSliderItemInterface $simpleSliderItemRepository
     */
    public function __construct(SimpleSliderItemInterface $simpleSliderItemRepository)
    {
        $this->simpleSliderItemRepository = $simpleSliderItemRepository;
    }

    /**
     * @param SimpleSliderItemTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(SimpleSliderItemTable $dataTable)
    {
        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        return $formBuilder->create(SimpleSliderItemForm::class)
            ->setTitle(trans('plugins/simple-slider::simple-slider.create_new_slide'))
            ->setUseInlineJs(true)
            ->renderForm();
    }

    /**
     * @param SimpleSliderItemRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(SimpleSliderItemRequest $request, BaseHttpResponse $response)
    {
        $simpleSlider = $this->simpleSliderItemRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SIMPLE_SLIDER_ITEM_MODULE_SCREEN_NAME, $request, $simpleSlider));

        return $response->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $simpleSliderItem = $this->simpleSliderItemRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $simpleSliderItem));

        return $formBuilder->create(SimpleSliderItemForm::class, ['model' => $simpleSliderItem])
            ->setTitle(trans('plugins/simple-slider::simple-slider.edit_slide', ['id' => $simpleSliderItem->id]))
            ->setUseInlineJs(true)
            ->renderForm();
    }

    /**
     * @param $id
     * @param SimpleSliderItemRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, SimpleSliderItemRequest $request, BaseHttpResponse $response)
    {
        $simpleSlider = $this->simpleSliderItemRepository->findOrFail($id);
        $simpleSlider->fill($request->input());

        $this->simpleSliderItemRepository->createOrUpdate($simpleSlider);

        event(new UpdatedContentEvent(SIMPLE_SLIDER_ITEM_MODULE_SCREEN_NAME, $request, $simpleSlider));

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function destroy($id)
    {
        $slider = $this->simpleSliderItemRepository->findOrFail($id);

        return view('plugins/simple-slider::partials.delete', compact('slider'))->render();
    }

    /**
     * @param Request $request
     * @param $id
     * @param BaseHttpResponse $response
     * @return array|BaseHttpResponse
     */
    public function postDelete(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $simpleSlider = $this->simpleSliderItemRepository->findOrFail($id);
            $this->simpleSliderItemRepository->delete($simpleSlider);

            event(new DeletedContentEvent(SIMPLE_SLIDER_ITEM_MODULE_SCREEN_NAME, $request, $simpleSlider));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
