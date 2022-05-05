<?php

namespace Botble\RealEstate\Repositories\Caches;

use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class PropertyCacheDecorator extends CacheAbstractDecorator implements PropertyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRelatedProperties(int $propertyId, $limit = 4, array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties($filters = [], $params = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty(int $propertyId, array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertiesByConditions(array $condition, $limit, array $with = [], array $withCount = [], array $orderBy = [], array $paginate = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getAccountPropertiesCount(int $accountId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getAccountFeaturedPropertiesCount(int $accountId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getAccountTopPropertiesCount(int $accountId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getAccountUrgentPropertiesCount(int $accountId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getAccountSoldProperties(array $condition, $limit, array $with = [], array $withCount = [], array $orderBy = [], array $paginate = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
