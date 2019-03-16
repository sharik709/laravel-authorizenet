<?php

use ANet\Test\TestCase;
use ANet\ANet;

class ANetTest extends TestCase
{
    /** @var ANet */
    protected $anet;

    protected $user;

    protected function setUp():void 
    {
        parent::setUp();
        $this->user = Mockery::mock(App\User::class);
        $this->anet = new Anet($this->user);
    }

    /** @test */
    public function it_tests_if_user_can_be_set()
    {
        $this->anet->setUser($this->user);
        $this->assertEquals($this->user, $this->anet->getUser());
    }



}