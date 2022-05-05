<?php

namespace Botble\Newsletter\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static NewsletterStatusEnum SUBSCRIBED()
 * @method static NewsletterStatusEnum UNSUBSCRIBED()
 */
class NewsletterStatusEnum extends Enum
{
    public const SUBSCRIBED = 'subscribed';
    public const UNSUBSCRIBED = 'unsubscribed';

    /**
     * @var string
     */
    public static $langPath = 'plugins/newsletter::newsletter.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::SUBSCRIBED:
                return Html::tag('span', self::SUBSCRIBED()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::UNSUBSCRIBED:
                return Html::tag('span', self::UNSUBSCRIBED()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}
