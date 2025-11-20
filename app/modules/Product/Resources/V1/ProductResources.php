<?php 

namespace Modules\Order\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResources extends JsonResource
{
    public function toArray($request)
    {
        
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'category_id' => $this->category_id,
            'image'       => $this->image ? asset('storage/' . $this->image) : null,
            'options'     => $this->whenLoaded('options', function () {
                return $this->options->map(function ($option) {
                    return [
                        'id'     => $option->id,
                        'name'   => $option->name,
                        'values' => $option->values->map(function ($value) {
                            return [
                                'id'    => $value->id,
                                'value' => $value->value,
                            ];
                        }),
                    ];
                });
            }),
        ];
    
    }
}