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
        dd(config('authorizenet'));
        $this->user = Mockery::mock(config('authorizenet.user_model'))
                        ->getMock();
        $this->anet = new Anet($this->user);
    }

    /** @test */
    public function it_tests_if_user_can_be_set()
    {
        $this->anet->setUser($this->user);
        $this->assertEquals($this->user, $this->anet->user);
    }


}