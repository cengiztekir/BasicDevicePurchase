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
    public function create(Request $attributes,?Model $attrModel=null): Model{

        if(!is_null($attrModel)){
            if($attrModel->appId==$attributes->appId){
                $result['client-token'] = $attrModel->ClientToken;
                return $attrModel;
            }
        }

        $ClientToken = str_random(60);

        $Device = new Device([
            'uid' => $attributes->uid,
            'appId' => $attributes->appId,
            'language' => $attributes->language,
            'OpSys' => $attributes->OpSys,
            'ClientToken' => $ClientToken
        ]);

        $Device->save();

        return $Device;
    }

    /**
     * @param Request $attributes
     * @return Model|null
     */
    public function find(Request $attributes): ?Model
    {
        $ClientTokenName = "client-token";

        $Device = Device::where('Status', '=', '1');
        if(!is_null($attributes->uid)){
            $Device->where('uid', '=', $attributes->uid);
        }
        if(!is_null($attributes->appId)){
            $Device->where('appId', '=', $attributes->appId);
        }
        if(!is_null($attributes->$ClientTokenName)){
            $Device->where('ClientToken', '=', $attributes->$ClientTokenName);
        }
        return $Device->first();
    }
}
