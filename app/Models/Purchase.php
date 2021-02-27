<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Purchase extends Model
{
    use HasFactory,Searchable;

    protected $table = "purchase";

    public function searchableAs()
    {
        return 'items_index';
    }

    protected $fillable = [
        'Status',
        'ExpireDate',
        'ClientToken',
        'receipt',
        'Message',
    ];

    public function toSearchableArray(): array
    {
        return [
            'Status' => $this->Status,
            'ClientToken' => $this->ClientToken,
        ];
    }
}
