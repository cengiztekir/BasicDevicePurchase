<?php
namespace App\Http\Repository\Eloquent;

use App\Models\Device;
use App\Http\Repository\DeviceRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DeviceRepository extends BaseRepository implements DeviceRepositoryInterface
{

    /**
     *
     * @param Device $model
     */
    public function __construct(Device $model)
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
        if(!is_null($attrModel) && $attrModel->appId==$attributes->appId){
            $result['client-token'] = $attrModel->ClientToken;

            return $attrModel;
        }

        $ClientToken = str_random(60);

        return Device::query()->create([
            'uid' => $attributes->uid,
            'appId' => $attributes->appId,
            'language' => $attributes->language,
            'OpSys' => $attributes->OpSys,
            'ClientToken' => $ClientToken
        ]);
    }

    /**
     * @param Request $attributes
     * @return Model|null
     */
    public function find(Request $attributes): ?Model
    {
        $ClientTokenName = "client-token";

        return Device::query()->where('Status', '=', '1')
            ->when($attributes->uid, function ($query) use ($attributes) {
                $query->where('uid', '=', $attributes->uid);
            })
            ->when($attributes->appId, function ($query) use ($attributes) {
                $query->where('appId', '=', $attributes->appId);
            })
            ->when($attributes->$ClientTokenName, function ($query) use ($ClientTokenName, $attributes) {
                $query->where('ClientToken', '=', $attributes->$ClientTokenName);
            })
            ->searchable();
    }
}
