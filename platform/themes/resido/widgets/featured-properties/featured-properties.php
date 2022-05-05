<?php

use Botble\Widget\AbstractWidget;

class FeaturedPropertiesWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $widgetDirectory = 'featured-properties';

    /**
     * FeaturedPropertiesWidget constructor.
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct()
    {
        parent::__construct([
            'name'           => __('Featured properties'),
            'description'    => __('Featured properties widget.'),
            'number_display' => 5,
        ]);
    }
}
