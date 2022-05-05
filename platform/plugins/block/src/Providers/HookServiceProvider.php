<?php

namespace Botble\Block\Providers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Block\Repositories\Interfaces\BlockInterface;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (function_exists('shortcode')) {
            add_shortcode('static-block', trans('plugins/block::block.static_block_short_code_name'),
                trans('plugins/block::block.static_block_short_code_description'), [$this, 'render']);

            shortcode()->setAdminConfig('static-block', function ($attributes, $content) {
                $blocks = $this->app->make(BlockInterface::class)
                    ->pluck('name', 'alias', ['status' => BaseStatusEnum::PUBLISHED]);

                return view('plugins/block::partials.short-code-admin-config', compact('blocks', 'attributes', 'content'))->render();
            });
        }
    }

    /**
     * @param \stdClass $shortcode
     * @return null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function render($shortcode)
    {
        $block = $this->app->make(BlockInterface::class)
            ->getFirstBy([
                'alias'  => $shortcode->alias,
                'status' => BaseStatusEnum::PUBLISHED,
            ]);

        if (empty($block)) {
            return null;
        }

        return $block->content;
    }
}
