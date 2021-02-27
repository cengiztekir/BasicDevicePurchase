<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Device extends Model
{
    use HasFactory,Searchable;

    protected $table="device";

    public function searchableAs()
    {
        return 'items_index';
    }

    protected $fillable = [
        'uid',
        'appId',
        'language',
        'OpSys',
        'ClientToken',
    ];

    public function toSearchableArray(): array
    {
        return [
            'Status' => $this->Status,
            'uid' => $this->uid,
            'appId' => $this->appId,
            'ClientToken' => $this->ClientToken,
        ];
    }
}
