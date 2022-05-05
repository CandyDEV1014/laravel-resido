<?php

namespace Botble\RealEstate\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                     => $this->id,
            'name'                   => $this->name,
            'price'                  => $this->price,
            'price_text'             => format_price($this->price, $this->currency),
            'credits'                => $this->credits,
            'number_of_days'         => $this->number_of_days,
            'number_of_properties'   => $this->number_of_properties,
            'number_of_aminities'    => $this->number_of_aminities,
            'number_of_nearestplace' => $this->number_of_nearestplace,
            'number_of_photo'        => $this->number_of_photo,
            'is_allow_featured'      => $this->is_allow_featured,
            'number_of_featured'     => $this->number_of_featured,
            'is_allow_top'           => $this->is_allow_top,
            'number_of_top'          => $this->number_of_top,
            'is_allow_urgent'        => $this->is_allow_urgent,
            'number_of_urgent'       => $this->number_of_urgent,
            'is_promotion'           => $this->is_promotion,
            'promotion_price'        => $this->promotion_price,
            'promotion_price_text'   => format_price($this->promotion_price, $this->currency),
            'promotion_time'         => $this->promotion_time,
            'is_auto_renew'          => $this->is_auto_renew,
            'is_agent'               => $this->is_agent,
            'features'               => $this->features
        ];
    }
}
