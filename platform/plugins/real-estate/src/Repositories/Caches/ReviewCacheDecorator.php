<?php

namespace Botble\RealEstate\Repositories\Caches;

use Botble\RealEstate\Repositories\Interfaces\ReviewInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class ReviewCacheDecorator extends CacheAbstractDecorator implements ReviewInterface
{
}
