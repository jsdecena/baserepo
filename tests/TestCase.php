<?php

namespace Jsdecena\Baserepo\Test;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Set up
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        $this->withFactories(__DIR__. '/database/factories');
    }
}