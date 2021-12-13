<?php

namespace App\Http\Resources\Slider;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'banner' => url('images/slider/').'/'.$this->slider_image,
            'back_url' => $this->back_link_url,
            'status' => $this->status
        ];
        // return parent::toArray($request);
    }
}
