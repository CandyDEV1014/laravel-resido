<?php

namespace Botble\RealEstate\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Forms\DetailForm;
use Botble\RealEstate\Http\Requests\DetailRequest;
use Botble\RealEstate\Repositories\Interfaces\DetailInterface;
use Botble\RealEstate\Tables\DetailTable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class DetailController extends BaseController
{
    /**
     * @var DetailInterface
     */
    protected $detailRepository;

    /**
     * DetailController constructor.
     * @param DetailInterface $detailRepository
     */
    public function __construct(DetailInterface $detailRepository)
    {
        $this->detailRepository = $detailRepository;
    }


    /**
     * @param DetailTable $dataTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function index(DetailTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/real-estate::detail.name'));

        return $dataTable->renderTable();
    }

    /**
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        return $formBuilder->create(DetailForm::class)->renderForm();
    }

    /**
     * @param DetailRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(DetailRequest $request, BaseHttpResponse $response)
    {

        $request->merge(['features' => json_encode($request->input('features'))]);

        $detail = $this->detailRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(PROPERTY_DETAIL_MODULE_SCREEN_NAME, $request, $detail));

        return $response
            ->setPreviousUrl(route('property_detail.index'))
            ->setNextUrl(route('property_detail.edit', $detail->id))
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
        $detail = $this->detailRepository->findOrFail($id);
        page_title()->setTitle(trans('plugins/real-estate::detail.edit') . ' "' . $detail->name . '"');

        event(new BeforeEditContentEvent($request, $detail));

        return $formBuilder->create(DetailForm::class, ['model' => $detail])->renderForm();
    }

    /**
     * @param int $id
     * @param DetailRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, DetailRequest $request, BaseHttpResponse $response)
    {
        $detail = $this->detailRepository->findOrFail($id);

        $detail->fill($request->input());
        $this->detailRepository->createOrUpdate($detail);

        event(new UpdatedContentEvent(PROPERTY_DETAIL_MODULE_SCREEN_NAME, $request, $detail));

        return $response
            ->setPreviousUrl(route('property_detail.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy($id, Request $request, BaseHttpResponse $response)
    {
        try {
            $detail = $this->detailRepository->findOrFail($id);
            $this->detailRepository->delete($detail);

            event(new DeletedContentEvent(PROPERTY_DETAIL_MODULE_SCREEN_NAME, $request, $detail));

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
            $detail = $this->detailRepository->findOrFail($id);
            $this->detailRepository->delete($detail);

            event(new DeletedContentEvent(PROPERTY_DETAIL_MODULE_SCREEN_NAME, $request, $detail));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
