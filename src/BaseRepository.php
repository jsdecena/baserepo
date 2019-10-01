<?php

namespace Jsdecena\Baserepo;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;;
use League\Fractal\Resource\Item;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item as FractalItem;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var BaseManager
     */
    protected $manager;

    /**
     * @var BasePaginator
     */
    protected $paginator;

    /**
     * @var $query Builder
     */
    protected $query;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->manager = new BaseManager;
        $this->paginator = new BasePaginator;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data) : bool
    {
        return $this->model->update($data);
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function all($columns = ['*'], string $orderBy = 'id', string $sortBy = 'asc')
    {
        return $this->model->orderBy($orderBy, $sortBy)->get($columns);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param  $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOneOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $data
     * @return Collection
     */
    public function findBy(array $data)
    {
        return $this->model->where($data)->get();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function findOneBy(array $data)
    {
        return $this->model->where($data)->first();
    }

    /**
     * @param array $data
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOneByOrFail(array $data)
    {
        return $this->model->where($data)->firstOrFail();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function delete() : bool
    {
        return $this->model->delete();
    }

    /**
     * Paginate arrays
     *
     * @param array $data
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateArrayResults(array $data, int $perPage = 50)
    {
        $page = Input::get('page', 1);
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(
            array_values(array_slice($data, $offset, $perPage, true)),
            count($data),
            $perPage,
            $page,
            [
                'path' => app('request')->url(),
                'query' => app('request')->query()
            ]
        );
    }

    /**
     * @param Model $model
     * @param TransformerAbstract $transformer
     * @param string $resourceKey
     * @param string $includes
     * @return Scope
     *
     * @deprecated use @transformItem
     */
    public function processItemTransformer(
        Model $model,
        TransformerAbstract $transformer,
        string $resourceKey,
        string $includes = null
    ) : Scope {
        $manager = new ItemAndCollectionManager(new Manager);
        $item = new FractalItem($model, $transformer, $resourceKey);

        $included = explode(',', $includes);
        return $manager->createItemData(
            $item,
            $included
        );
    }

    /**
     * @param Collection $collection
     * @param TransformerAbstract $transformer
     * @param string $resourceKey
     * @param string $includes
     * @param int $perPage
     * @return Scope
     *
     * @deprecated use @transformCollection
     */
    public function processCollectionTransformer(
        Collection $collection,
        TransformerAbstract $transformer,
        string $resourceKey,
        string $includes = null,
        $perPage = 25
    ) : Scope {

        $manager = new ItemAndCollectionManager(new Manager);

        $page = app('request')->input('page', 1);
        $fractalCollection = new FractalCollection($collection->forPage($page, $perPage), $transformer, $resourceKey);

        $paginator = $this->paginateArrayResults($collection->all(), $perPage);
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $paginator->appends($queryParams);
        $fractalCollection->setPaginator(new IlluminatePaginatorAdapter($paginator));

        if (!is_null($includes)) {
            $included = explode(',', $includes);
            return $manager->createCollectionData(
                $fractalCollection,
                $included
            );
        } else {
            return $manager->createCollectionData(
                $fractalCollection
            );
        }
    }

    /**
     * Transform a Paginated response
     *
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
    ) : Scope
    {
        $items = $paginator->getCollection();

        $resource = new FractalCollection($items, $transformer, $resourceKey);
        $fractalCollection = $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $manager = new ItemAndCollectionManager(new Manager);

        if (!is_null($includes)) {
            $included = explode(',', $includes);
            return $manager->createCollectionData(
                $fractalCollection,
                $included
            );
        } else {
            return $manager->createCollectionData(
                $fractalCollection
            );
        }
    }

    /**
     * Transform a Model
     *
     * @param Model $model
     * @param TransformerAbstract $transformer
     * @param $resourceKey
     * @param array $includes
     * @return array
     */
    public function transformItem(
        Model $model,
        TransformerAbstract $transformer,
        $resourceKey,
        array $includes = []
    ) : array
    {
        $resource = new Item($model, $transformer, $resourceKey);
        return $this->manager->buildData($resource, $includes);
    }

    /**
     * Transform a Model Collection
     *
     * @param $collection
     * @param TransformerAbstract $transformer
     * @param $resourceKey
     * @param array $includes
     * @return array
     */
    public function transformCollection(
        $collection,
        TransformerAbstract $transformer,
        $resourceKey,
        array $includes = []
    ) : array
    {
        $resource = new FractalCollection($collection, $transformer, $resourceKey);
        return $this->manager->buildData($resource, $includes);
    }

    /**
     * Create custom build query
     *
     * @param Model|Builder $modelOrBuilder
     * @param array $params
     * @return Builder
     */
    public function queryBy($modelOrBuilder, array $params) : Builder
    {
        $start = $modelOrBuilder;
        if (!empty($params)) {
            $start->where($params);
        }

        $this->query = $start;

        return $this->query;
    }

    /**
     * @param Builder $builder
     * @param TransformerAbstract $transformer
     * @param bool $isPaginated
     * @param int $limit
     *
     *
     * @param null $offset
     * @param null $previous
     *
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    public function getData(
        Builder $builder,
        TransformerAbstract $transformer,
        $isPaginated = true,
        $limit = 50,
        $offset = null,
        $previous = null
    )
    {
        if (!$isPaginated) {
            return $builder->get();
        }

        if ($offset) {
            $collection = $builder->offset($offset)->take($limit)->get();
        } else {
            $collection = $builder->take($limit)->get();
        }

        $newCursor = $collection->last()->id;
        $cursor = new Cursor($offset, $previous, $newCursor, $collection->count());

        $resource = new FractalCollection($collection, $transformer);
        $resource->setCursor($cursor);

        $manager = new Manager;

        return $manager->createData($resource)->toArray();
    }
}
