<?php

namespace Botble\Newsletter\Repositories\Caches;

use Botble\Newsletter\Repositories\Interfaces\NewsletterInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class NewsletterCacheDecorator extends CacheAbstractDecorator implements NewsletterInterface
{
}
