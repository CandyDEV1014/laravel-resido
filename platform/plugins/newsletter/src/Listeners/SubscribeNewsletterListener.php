<?php

namespace Botble\Newsletter\Listeners;

use Botble\Newsletter\Events\SubscribeNewsletterEvent;
use EmailHandler;
use Html;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;
use URL;

class SubscribeNewsletterListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param SubscribeNewsletterEvent $event
     * @return void
     * @throws Throwable
     */
    public function handle(SubscribeNewsletterEvent $event)
    {
        $mailer = EmailHandler::setModule(NEWSLETTER_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'newsletter_name'             => $event->newsletter->name ?? 'N/A',
                'newsletter_email'            => $event->newsletter->email,
                'newsletter_unsubscribe_link' => Html::link(
                    URL::signedRoute('public.newsletter.unsubscribe',
                        ['user' => $event->newsletter->id]),
                    __('here')
                )->toHtml(),
            ]);

        $mailchimpApiKey = setting('newsletter_mailchimp_api_key');
        $mailchimpListId = setting('newsletter_mailchimp_list_id');

        if (!$mailchimpApiKey || !$mailchimpListId) {
            $mailer->sendUsingTemplate('subscriber_email', $event->newsletter->email);
        }

        $mailer->sendUsingTemplate('admin_email');
    }
}
