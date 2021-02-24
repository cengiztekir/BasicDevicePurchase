<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Device extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uid' => $this->uid,
            'appId' => $this->appId,
            'language' => $this->language,
            'OpSys' => $this->OpSys,
            'ClientToken' => $this->ClientToken,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
