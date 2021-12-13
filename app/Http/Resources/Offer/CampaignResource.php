<?php

namespace App\Http\Resources\Offer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\ProductResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return 
        [
          'id'             => $this->id,
          'campaign_title' => $this->title,
          'slug'           => str_replace('%','-percent',str_replace(' ', '-', $this->title)),
          'banner'         => url('images/campaign/banner/').'/'.$this->image,
          'meta_image'     => $this->meta_image ? url('images/campaign/meta/').'/'.$this->meta_image : '',
          'status'         => $this->status,
          'status_text'    => $this->status == 1 ? 'Active' : 'Inactive',
          'product'        => ProductResource::collection($this->whenLoaded('product')),
        ];
    }
}
