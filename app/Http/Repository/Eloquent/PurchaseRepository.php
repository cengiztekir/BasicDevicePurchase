<?php
namespace App\Http\Repository\Eloquent;

use App\Models\Purchase;
use App\Http\Repository\PurchaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PurchaseRepository extends BaseRepository implements PurchaseRepositoryInterface
{
    /**
     *
     * @param Purchase $model
     */
    public function __construct(Purchase $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param Model $attrModel
     * @param Request $attributes
     * @return Model
     */
    public function create(Request $attributes,?Model $attrModel=null): Model
    {
        $Purchase = new Purchase([
            'Status' => $attributes->Status,
            'ExpireDate' => $attributes->ExpireDate,
            'ClientToken' => $attributes->ClientToken,
            'receipt' => $attributes->receipt,
            'Message' =>  $attributes->Message,
        ]);

        $Purchase->save();

        return $Purchase;
    }

    /**
     * @param Request $attributes
     * @return Model|null
     */
    public function find(Request $attributes): ?Model
    {
        $ClientTokenName = "client-token";
        $Purchase = Purchase::where('ClientToken', '=', $attributes->$ClientTokenName)->where('Status','=','1')->first();

        return $Purchase;
    }
}
