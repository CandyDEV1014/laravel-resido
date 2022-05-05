<?php

use Botble\Widget\AbstractWidget;

class ShortcodeWidget extends AbstractWidget
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
    protected $frontendTemplate = 'frontend';

    /**
     * @var string
     */
    protected $backendTemplate = 'backend';

    /**
     * @var string
     */
    protected $widgetDirectory = 'shortcode';

    /**
     * Widget constructor.
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct()
    {
        parent::__construct([
            'name'        =>  __('Shortcode widget'),
            'content'     =>  null,
            'description' => __('Adds a text-like widget that allows you to write shortcode in it.'),
        ]);
    }
}
