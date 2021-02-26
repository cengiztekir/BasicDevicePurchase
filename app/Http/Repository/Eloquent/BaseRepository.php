<?php

namespace App\Http\Repository\Eloquent;

use App\Http\Repository\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param Model $attrModel
     * @param Request $attributes
     *
     * @return Model
     */
    public function create(Request $attributes,?Model $attrModel=null): Model
    {
        return $this->model->create($attrModel,$attributes);
    }



    /**
     * @param Request $attributes
     * @return Model
     */
    public function find(Request $attributes): ?Model
    {
        return $this->model->find($attributes);
    }
}
