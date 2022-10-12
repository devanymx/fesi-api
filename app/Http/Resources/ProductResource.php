<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'detail' => $this->detail,
            'department' => $this->department->name,
            'category' => $this->category->name,
            'code' => $this->code,
            'description' => $this->description,
            'measurement' => $this->measurement,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'profit' => $this->profit,
            'unit' => $this->unit,
            'minimum' => $this->minimum,
            'maximum' => $this->maximum,
            'taxes' => $this->taxes,
            'image' => $this->image,
            'dealer' => $this->dealer->name,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
