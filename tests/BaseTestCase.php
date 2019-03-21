<?php

namespace ANet\Test;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use stdClass;

abstract class BaseTestCase extends \Tests\TestCase
{
    use DatabaseMigrations;

    /**
     * @return stdClass
     */
    public function getFakeUser()
    {
        return factory(\App\User::class)->create();
    }


}