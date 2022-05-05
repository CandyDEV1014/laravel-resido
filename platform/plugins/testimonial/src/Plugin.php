<?php

namespace Botble\Testimonial;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('testimonials_translations');
    }
}
