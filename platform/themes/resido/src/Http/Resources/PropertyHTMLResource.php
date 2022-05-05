<?php

namespace Theme\Resido\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Theme;

class PropertyHTMLResource extends JsonResource
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
            'id'   => $this->id,
            'HTML' => Theme::partial('real-estate.properties.item-grid', ['property' => $this, 'lazyload' => false]),
        ];
    }
}
