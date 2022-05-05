<?php

namespace Botble\RealEstate;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('re_consults');
        Schema::dropIfExists('re_properties');
        Schema::dropIfExists('re_features');
        Schema::dropIfExists('re_property_features');
        Schema::dropIfExists('re_categories');
        Schema::dropIfExists('re_currencies');
        Schema::dropIfExists('re_facilities_distances');
        Schema::dropIfExists('re_facilities');
        Schema::dropIfExists('re_accounts');
        Schema::dropIfExists('re_account_password_resets');
        Schema::dropIfExists('re_account_activity_logs');
        Schema::dropIfExists('re_packages');
        Schema::dropIfExists('re_accounts_packages');
        Schema::dropIfExists('re_reviews');
        Schema::dropIfExists('re_reviews_meta');
    }
}
