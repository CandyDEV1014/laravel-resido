<?php

namespace Theme\Resido\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use RvMedia;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'url'         => $this->url,
            'description' => Str::words($this->description, 35),
            'image'       => $this->image ? RvMedia::getImageUrl($this->image, 'medium', false, RvMedia::getDefaultImage()) : null,
            'created_at'  => $this->created_at->format('d M, Y'),
            'views'       => number_format($this->views),
            'categories'  => CategoryResource::collection($this->categories),
        ];
    }
}
