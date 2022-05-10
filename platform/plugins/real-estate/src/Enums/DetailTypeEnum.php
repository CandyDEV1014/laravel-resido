<?php

namespace Botble\RealEstate\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static DetailTypeEnum TEXT()
 * @method static DetailTypeEnum NUMBER()
 * @method static DetailTypeEnum SQUARE()
 * @method static DetailTypeEnum DATE()
 * @method static DetailTypeEnum YEAR()
 * @method static DetailTypeEnum SELECTBOX()
 */
class DetailTypeEnum extends Enum
{
    public const TEXT = 'text';
    public const NUMBER = 'number';
    public const SQUARE = 'square';
    public const DATE = 'date';
    public const YEAR = 'year';
    public const SELECTBOX = 'selectbox';

    /**
     * @var string
     */
    public static $langPath = 'plugins/real-estate::detail.type';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::TEXT:
                return Html::tag('span', self::TEXT()->label(), ['class' => 'label-default status-label'])
                    ->toHtml();
            case self::NUMBER:
                return Html::tag('span', self::NUMBER()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::SQUARE:
                return Html::tag('span', self::SQUARE()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::DATE:
                return Html::tag('span', self::DATE()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::YEAR:
                return Html::tag('span', self::YEAR()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::SELECTBOX:
                return Html::tag('span', self::SELECTBOX()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            default:
                return null;
        }
    }
}
