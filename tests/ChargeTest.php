<?php

use ANet\Test\TestCase;
use ANet\AuthorizeNet;
use ANet\ANet;

class ChargeTest extends TestCase
{

    protected $authorizenet;
    protected $user;

    protected function setUp():void
    {
        parent::setUp();
        $this->user = Mockery::mock(\App\User::class)
                        ->shouldReceive('getCustomerProfileId')
                        ->andReturn('my-token')
                        ->getMock();

        $this->authorizenet = new ANet($this->user);
    }


    /** @test */
    public function it_will_charge_given_user()
    {
        $chargeAmount = 1000; // $10
        $paymentProfileId = 'my-token';
        $response = $this->authorizenet->charge($chargeAmount, $paymentProfileId);
        dd($response);
    }




}