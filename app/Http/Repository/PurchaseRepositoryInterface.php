<?php
namespace App\Http\Repository;

use Illuminate\Support\Collection;

interface PurchaseRepositoryInterface
{
    /**
     * @return Collection
     */
    public function all(): Collection;
}
