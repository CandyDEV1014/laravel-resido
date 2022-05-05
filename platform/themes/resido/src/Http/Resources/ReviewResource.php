<?php

namespace Theme\Resido\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class ReviewResource extends JsonResource
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
            'user_name'   => $this->account->name,
            'user_avatar' => $this->account->avatar->url ? RvMedia::getImageUrl($this->account->avatar->url, 'thumb') : $this->account->avatar_url,
            'created_at'  => $this->created_at->format('d M, Y'),
            'comment'     => $this->comment,
            'star'        => $this->star,
        ];
    }
}
