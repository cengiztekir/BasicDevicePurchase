<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Purchase extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Status' => $this->Status,
            'ExpireDate' => $this->ExpireDate,
            'ClientToken' => $this->ClientToken,
            'receipt' => $this->receipt,
            'Message' => $this->Message,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
