<?php

namespace Botble\RealEstate\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\RealEstate\Http\Requests\TypeRequest;
use Botble\RealEstate\Repositories\Interfaces\TypeInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Exception;
use Botble\RealEstate\Tables\TypeTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Forms\TypeForm;
use Botble\Base\Forms\FormBuilder;
use Illuminate\View\View;
use Throwable;

class TypeController extends BaseController
{
    /**
     * @var TypeInterface
     */
    protected $typeRepository;

    /**
     * TypeController constructor.
     * @param TypeInterface $typeRepository
     */
    public function __construct(TypeInterface $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    /**
     * @param TypeTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(TypeTable $table)
    {
        page_title()->setTitle(trans('plugins/real-estate::type.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/real-estate::type.create'));

        return $formBuilder->create(TypeForm::class)->renderForm();
    }

    /**
     * Insert new Type into database
     *
     * @param TypeRequest $request
     * @return BaseHttpResponse
     */
    public function store(TypeRequest $request, BaseHttpResponse $response)
    {
        $type = $this->typeRepository->getModel();

        $type->fill($request->input());

        $type->slug = $this->typeRepository->createSlug($request->get('slug'), 0);

        $type = $this->typeRepository->createOrUpdate($type);

        event(new CreatedContentEvent(PROPERTY_TYPE_MODULE_SCREEN_NAME, $request, $type));

        return $response
            ->setPreviousUrl(route('property_type.index'))
            ->setNextUrl(route('property_type.edit', $type->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * Show edit form
     *
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $type = $this->typeRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $type));

        page_title()->setTitle(trans('plugins/real-estate::type.edit') . ' "' . $type->name . '"');

        return $formBuilder->create(TypeForm::class, ['model' => $type])->renderForm();
    }

    /**
     * @param $id
     * @param TypeRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, TypeRequest $request, BaseHttpResponse $response)
    {
        $type = $this->typeRepository->findOrFail($id);

        $type->fill($request->input());

        $this->typeRepository->createOrUpdate($type);

        event(new UpdatedContentEvent(PROPERTY_TYPE_MODULE_SCREEN_NAME, $request, $type));

        return $response
            ->setPreviousUrl(route('property_type.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $type = $this->typeRepository->findOrFail($id);

            $this->typeRepository->delete($type);

            event(new DeletedContentEvent(PROPERTY_TYPE_MODULE_SCREEN_NAME, $request, $type));

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
            $type = $this->typeRepository->findOrFail($id);
            $this->typeRepository->delete($type);

            event(new DeletedContentEvent(PROPERTY_TYPE_MODULE_SCREEN_NAME, $request, $type));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
