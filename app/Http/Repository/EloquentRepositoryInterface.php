<?php
namespace App\Http\Repository;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface EloquentRepositoryInterface
{
    /**
     * @param Model $attrModel
     * @param Request $attributes
     * @return Model
     */
    public function create(Request $attributes,?Model $attrModel=null): Model;

    /**
     * @param Request $attributes
     * @return Model
     */
    public function find(Request $attributes): ?Model;
}
