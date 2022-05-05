<?php

namespace Botble\Blog\Supports;

use Botble\Base\Enums\BaseStatusEnum;

class FilterPost
{

    /**
     * @param array $request
     * @return array
     */
    public static function setFilters(array $request): array
    {
        return [
            'page'               => $request['page'] ?? 1,
            'per_page'           => $request['per_page'] ?? 10,
            'search'             => $request['search'] ?? null,
            'author'             => $request['author'] ?? null,
            'author_exclude'     => $request['author_exclude'] ?? null,
            'exclude'            => $request['exclude'] ?? null,
            'include'            => $request['include'] ?? null,
            'after'              => $request['after'] ?? null,
            'before'             => $request['before'] ?? null,
            'order'              => $request['order'] ?? 'desc',
            'order_by'           => $request['order_by'] ?? 'updated_at',
            'status'             => BaseStatusEnum::PUBLISHED,
            'categories'         => $request['categories'] ?? null,
            'categories_exclude' => $request['categories_exclude'] ?? null,
            'tags'               => $request['tags'] ?? null,
            'tags_exclude'       => $request['tags_exclude'] ?? null,
            'featured'           => $request['featured'] ?? null,
        ];
    }
}
