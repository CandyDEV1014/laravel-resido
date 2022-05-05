<?php

namespace Botble\Newsletter\Http\Controllers;

use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\Newsletter\Repositories\Interfaces\NewsletterInterface;
use Botble\Newsletter\Tables\NewsletterTable;
use Exception;
use Illuminate\Http\Request;
use EmailHandler;
use Botble\Newsletter\Models\Newsletter;

class NewsletterController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var NewsletterInterface
     */
    protected $newsletterRepository;

    /**
     * NewsletterController constructor.
     * @param NewsletterInterface $newsletterRepository
     */
    public function __construct(NewsletterInterface $newsletterRepository)
    {
        $this->newsletterRepository = $newsletterRepository;
    }

    /**
     * @param NewsletterTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(NewsletterTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/newsletter::newsletter.name'));

        return $dataTable->renderTable();
    }

    /**
     * @param Request $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $newsletter = $this->newsletterRepository->findOrFail($id);
            $this->newsletterRepository->delete($newsletter);

            event(new DeletedContentEvent(NEWSLETTER_MODULE_SCREEN_NAME, $request, $newsletter));

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
        return $this->executeDeleteItems($request, $response, $this->newsletterRepository,
            NEWSLETTER_MODULE_SCREEN_NAME);
    }

    public function email()
    {
        return view('plugins/newsletter::email');
    }

    public function emailSend(BaseHttpResponse $response, Request $request)
    {

        $subscribers = Newsletter::get();

        $data = $request->message;

        foreach ($subscribers as $key => $value) {
            EmailHandler::send(
                view('core/acl::email',compact('data')),
                $request->subject,
                $value->email,
            );
        }

        return $response->setMessage(trans('core/setting::setting.test_email_send_success'));
    }
}
