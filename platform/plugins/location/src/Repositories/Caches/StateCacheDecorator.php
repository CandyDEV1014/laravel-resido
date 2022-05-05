<?php

namespace Botble\Location\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Location\Repositories\Interfaces\StateInterface;

class StateCacheDecorator extends CacheAbstractDecorator implements StateInterface
{
  /**
   * {@inheritDoc}
   */
  public function getStatesByCountry(int $country_id)
  {
      return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
  }
}
