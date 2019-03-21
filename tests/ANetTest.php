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
        $this->user = factory(\App\User::class)->create();
        $this->anet = new Anet($this->user);
    }

    /** @test */
    public function it_tests_if_user_can_be_set()
    {
        $this->anet->setUser($this->user);
        $this->assertEquals($this->user, $this->anet->getUser());
    }

    /** @test */
    public function it_will_test_if_customer_profile_can_be_created()
    {
        $response = $this->user->anet()->createCustomerProfile();
        dd($response);
        $this->assertDatabaseHas('anet_customer_profiles', [
            'user_id' => $this->user->id
        ]);
    }


}