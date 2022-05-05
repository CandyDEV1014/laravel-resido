<?php

namespace Botble\Analytics;

use DateTimeInterface;
use Google_Service_Analytics;
use Illuminate\Contracts\Cache\Repository;

class AnalyticsClient
{
    /**
     * @var Google_Service_Analytics
     */
    protected $service;

    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var int
     */
    protected $cacheLifeTimeInMinutes = 0;

    /**
     * AnalyticsClient constructor.
     * @param Google_Service_Analytics $service
     * @param Repository $cache
     */
    public function __construct(Google_Service_Analytics $service, Repository $cache)
    {
        $this->service = $service;

        $this->cache = $cache;
    }

    /**
     * Set the cache time.
     *
     * @param int $cacheLifeTimeInMinutes
     *
     * @return self
     */
    public function setCacheLifeTimeInMinutes(int $cacheLifeTimeInMinutes)
    {
        $this->cacheLifeTimeInMinutes = $cacheLifeTimeInMinutes * 60;

        return $this;
    }

    /**
     * Query the Google Analytics Service with given parameters.
     *
     * @param string $viewId
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @param string $metrics
     * @param array $others
     *
     * @return array|null
     */
    public function performQuery(
        string $viewId,
        DateTimeInterface $startDate,
        DateTimeInterface $endDate,
        string $metrics,
        array $others = []
    ) {
        $cacheName = $this->determineCacheName(func_get_args());

        if ($this->cacheLifeTimeInMinutes == 0) {
            $this->cache->forget($cacheName);
        }

        return $this->cache->remember($cacheName, $this->cacheLifeTimeInMinutes,
            function () use ($viewId, $startDate, $endDate, $metrics, $others) {
                $result = $this->service->data_ga->get(
                    'ga:' . $viewId,
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d'),
                    $metrics,
                    $others
                );

                while ($nextLink = $result->getNextLink()) {
                    if (isset($others['max-results']) && count($result->rows) >= $others['max-results']) {
                        break;
                    }

                    $options = [];

                    parse_str(substr($nextLink, strpos($nextLink, '?') + 1), $options);

                    $response = $this->service->data_ga->call('get', [$options], 'Google_Service_Analytics_GaData');

                    if ($response->rows) {
                        $result->rows = array_merge($result->rows, $response->rows);
                    }

                    $result->nextLink = $response->nextLink;
                }

                return $result;
            });
    }

    /**
     * @param array $properties
     * @return string
     */
    protected function determineCacheName(array $properties): string
    {
        return 'analytics.' . md5(serialize($properties));
    }

    /**
     * Determine the cache name for the set of query properties given.
     *
     * @return Google_Service_Analytics
     */
    public function getAnalyticsService(): Google_Service_Analytics
    {
        return $this->service;
    }
}
