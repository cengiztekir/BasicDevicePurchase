<?php
namespace App\Http\Repository;

use Illuminate\Support\Collection;

interface DeviceRepositoryInterface
{
    /**
     * @return Collection
     */
    public function all(): Collection;
}
