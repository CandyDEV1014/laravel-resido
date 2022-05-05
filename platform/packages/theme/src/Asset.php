<?php

namespace Botble\Theme;

use Closure;

class Asset
{

    /**
     * Path to assets.
     *
     * @var string
     */
    public static $path;

    /**
     * all the instantiated asset containers.
     *
     * @var array
     */
    public static $containers = [];

    /**
     * Asset buffering.
     *
     * @var array
     */
    protected $stacks = [
        'cooks'  => [],
        'serves' => [],
    ];


    /**
     * Add a path to theme.
     *
     * @param string $path
     */
    public function addPath($path)
    {
        static::$path = rtrim($path, '/') . '/';
    }

    /**
     * Cooking your assets.
     *
     * @param string $name
     * @param Closure $callbacks
     * @return void
     */
    public function cook($name, Closure $callbacks)
    {
        $this->stacks['cooks'][$name] = $callbacks;
    }

    /**
     * Serve asset preparing from cook.
     *
     * @param string $name
     * @return Asset
     */
    public function serve($name)
    {
        $this->stacks['serves'][$name] = true;

        return $this;
    }

    /**
     * Flush all cooks.
     *
     * @return void
     */
    public function flush()
    {
        foreach (array_keys($this->stacks['serves']) as $key) {
            if (array_key_exists($key, $this->stacks['cooks'])) {
                $callback = $this->stacks['cooks'][$key];

                if ($callback instanceof Closure) {
                    $callback($this);
                }
            }
        }
    }

    /**
     * Magic Method for calling methods on the default container.
     *
     * <code>
     *        // Call the "styles" method on the default container
     *        echo Asset::styles();
     *
     *        // Call the "add" method on the default container
     *        Asset::add('jquery', 'js/jquery.js');
     * </code>
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([static::container(), $method], $parameters);
    }

    /**
     * Get an asset container instance.
     *
     * <code>
     *        // Get the default asset container
     *        $container = Asset::container();
     *
     *        // Get a named asset container
     *        $container = Asset::container('footer');
     * </code>
     *
     * @param string $container
     * @return AssetContainer
     */
    public static function container($container = 'default')
    {
        if (!isset(static::$containers[$container])) {
            static::$containers[$container] = new AssetContainer($container);
        }

        return static::$containers[$container];
    }
}
