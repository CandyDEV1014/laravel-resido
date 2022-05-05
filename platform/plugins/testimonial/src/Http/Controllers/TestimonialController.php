<?php

namespace Botble\Testimonial\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\Testimonial\Http\Requests\TestimonialRequest;
use Botble\Testimonial\Repositories\Interfaces\TestimonialInterface;
use Botble\Base\Http\Controllers\BaseController;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Botble\Testimonial\Tables\TestimonialTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Testimonial\Forms\TestimonialForm;
use Botble\Base\Forms\FormBuilder;
use Illuminate\View\View;
use Throwable;

class TestimonialController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var TestimonialInterface
     */
    protected $testimonialRepository;

    /**
     * TestimonialController constructor.
     * @param TestimonialInterface $testimonialRepository
     */
    public function __construct(TestimonialInterface $testimonialRepository)
    {
        $this->testimonialRepository = $testimonialRepository;
    }

    /**
     * @param TestimonialTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(TestimonialTable $table)
    {

        page_title()->setTitle(trans('plugins/testimonial::testimonial.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/testimonial::testimonial.create'));

        return $formBuilder->create(TestimonialForm::class)->renderForm();
    }

    /**
     * @param TestimonialRequest $request
     * @return BaseHttpResponse
     */
    public function store(TestimonialRequest $request, BaseHttpResponse $response)
    {
        $testimonial = $this->testimonialRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(TESTIMONIAL_MODULE_SCREEN_NAME, $request, $testimonial));

        return $response
            ->setPreviousUrl(route('testimonial.index'))
            ->setNextUrl(route('testimonial.edit', $testimonial->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $testimonial = $this->testimonialRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $testimonial));

        page_title()->setTitle(trans('plugins/testimonial::testimonial.edit') . ' "' . $testimonial->name . '"');

        return $formBuilder->create(TestimonialForm::class, ['model' => $testimonial])->renderForm();
    }

    /**
     * @param $id
     * @param TestimonialRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, TestimonialRequest $request, BaseHttpResponse $response)
    {
        $testimonial = $this->testimonialRepository->findOrFail($id);

        $testimonial->fill($request->input());

        $this->testimonialRepository->createOrUpdate($testimonial);

        event(new UpdatedContentEvent(TESTIMONIAL_MODULE_SCREEN_NAME, $request, $testimonial));

        return $response
            ->setPreviousUrl(route('testimonial.index'))
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
            $testimonial = $this->testimonialRepository->findOrFail($id);

            $this->testimonialRepository->delete($testimonial);

            event(new DeletedContentEvent(TESTIMONIAL_MODULE_SCREEN_NAME, $request, $testimonial));

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
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->testimonialRepository,
            TESTIMONIAL_MODULE_SCREEN_NAME);
    }
}
