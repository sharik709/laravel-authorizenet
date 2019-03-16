<?php

use ANet\Test\TestCase;
use \net\authorize\api\controller as AnetController;
use \net\authorize\api\contract\v1 as AnetAPI;


class FormTokenTest extends TestCase
{
    protected $api;

    protected function setUp():void
    {
        parent::setUp();
    }

    /** @test */
    public function it_will_get_a_token_from_authorizenet()
    {
        $amount = 1000; // $10
        $this->assertInstanceOf(net\authorize\api\contract\v1\GetHostedPaymentPageResponse::class, ANet\FormToken::generate($amount));
    }



}