<?php

namespace Jsdecena\Baserepo\Test;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations();
    }
}