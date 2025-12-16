<?php

namespace Modules\Quote\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResources extends JsonResource
{
    //   'company_name',
    //     'contact_name',
    //     'email',
    //     'phone',
    //     'address',
    //     'tax_number',
    //     'expiration_date',
    //     'special_notes',
    //     'subtotal',
    //     'tax_total',
    //     'grand_total',
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'company_name'   => $this->company_name,
            'contact_name'   => $this->contact_name,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'address'        => $this->address,
            'tax_number'     => $this->tax_number,
            'expiration_date'=> $this->expiration_date,
            'special_notes'  => $this->special_notes,
            'subtotal'       => $this->subtotal,
            'tax_total'      => $this->tax_total,
            'grand_total'    => $this->grand_total,
        ];
    }

}
