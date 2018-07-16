<?php

namespace Jsdecena\Baserepo;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item as FractalItem;
use League\Fractal\Scope;
use League\Fractal\Serializer\JsonApiSerializer;

class ItemAndCollectionManager
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var string
     */
    private $url;

    /**
     * ItemManager constructor.
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
        $this->url = config('app.url', 'http://localhost').config('app.api_ver', '/api/v1');
    }

    /**
     * @param FractalItem $item
     * @param array $includes
     * @return Scope
     */
    public function createItemData(FractalItem $item, array $includes = []) : Scope
    {
        $this->manager->parseIncludes($includes);
        $this->manager->setSerializer(new JsonApiSerializer($this->url));
        return $this->manager->createData($item);
    }

    /**
     * @param FractalCollection $collection
     * @param array $includes
     * @return Scope
     */
    public function createCollectionData(
        FractalCollection $collection,
        array $includes = []
    ) : Scope {
        $this->manager->parseIncludes($includes);
        $this->manager->setSerializer(new JsonApiSerializer($this->url));
        return $this->manager->createData($collection);
    }
}