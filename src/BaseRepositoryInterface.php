<?php

namespace Jsdecena\Baserepo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;

interface BaseRepositoryInterface
{
    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * @param array $attributes
     * @return bool
     */
    public function update(array $attributes) : bool;

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function all($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc');

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param $id
     * @return mixed
     */
    public function findOneOrFail($id);

    /**
     * @param array $data
     * @return mixed
     */
    public function findBy(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function findOneBy(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function findOneByOrFail(array $data);

    /**
     * @return bool
     */
    public function delete() : bool;

    /**
     * @param array $data
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateArrayResults(array $data, int $perPage = 50);

    /**
     * @param Model $model
     * @param TransformerAbstract $transformer
     * @param string $resourceKey
     * @param string $includes
     * @return Scope
     */
    public function processItemTransformer(
        Model $model,
        TransformerAbstract $transformer,
        string $resourceKey,
        string $includes = null
    ) : Scope;

    /**
     * @param Collection $collection
     * @param TransformerAbstract $transformer
     * @param string $resourceKey
     * @param string $includes
     * @param int $perPage
     * @return Scope
     */
    public function processCollectionTransformer(
        Collection $collection,
        TransformerAbstract $transformer,
        string $resourceKey,
        string $includes = null,
        $perPage = 25
    ) : Scope;

    /**
     * @param LengthAwarePaginator $paginator
     * @param TransformerAbstract $transformer
     * @param string $resourceKey
     * @param string $includes
     * @return Scope
     */
    public function processPaginatedResults(
        LengthAwarePaginator $paginator,
        TransformerAbstract $transformer,
        string $resourceKey,
        string $includes = null
    ) : Scope;
}