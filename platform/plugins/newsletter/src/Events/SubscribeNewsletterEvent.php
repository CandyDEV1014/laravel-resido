<?php

namespace Botble\Newsletter\Events;

use Botble\Newsletter\Models\Newsletter;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscribeNewsletterEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Newsletter
     */
    public $newsletter;

    /**
     * Create a new event instance.
     *
     * @param Newsletter $newsletter
     */
    public function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }
}
