<?php

namespace ANet\Test;

use ANet\AuthorizeNet;
use ANet\Test\BaseTestCase;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\controller\GetHostedPaymentPageController;
use stdClass;

class AuthorizeNetTest extends BaseTestCase
{
    /** @var AuthorizeNet */
    protected $authorizenet;

    protected function setUp():void
    {
        parent::setUp();
        $this->authorizenet = $this->getMockForAbstractClass(AuthorizeNet::class);
    }

    /** @test */
    public function it_will_will_check_if_set_and_get_request_method_sets_and_gets_request_object()
    {
        $object = new stdClass;
        $this->authorizenet->setRequest($object);
        $this->assertEquals($object, $this->authorizenet->getRequest());
    }

    /** @test */
    public function it_will_set_and_get_new_ref()
    {
        $ref = time();
        $this->authorizenet->setRefId($ref);
        $this->assertEquals($ref, $this->authorizenet->getRefId());
    }

    /** @test */
    public function it_will_test_if_no_ref_is_set_then_a_time_string_is_returned()
    {
        $this->assertNotNull($this->authorizenet->getRefId());
    }

    /** @test */
    public function it_will_test_if_transaction_type_can_be_set()
    {
        $type = 'authCaptureTransaction';
        $amount = 1000; // $10
        $this->authorizenet->setTransactionType($type, $amount);

        $object = $this->authorizenet->getTransactionType();
        $this->assertInstanceOf(TransactionRequestType::class, $object);
    }

    /** @test */
    public function it_will_convert_cents_to_dollar()
    {
        $this->assertEquals(10.99, $this->authorizenet->convertCentsToDollar(1099));
    }

    /** @test */
    public function it_will_convert_dollars_to_cents()
    {
        $this->assertEquals(1099, $this->authorizenet->convertDollarsToCents(10.99));
    }

    /** @test */
    public function it_will_test_if_controller_can_be_set_and_get()
    {
        $object = new stdClass;
        $this->authorizenet->setController($object);
        $this->assertEquals($object, $this->authorizenet->getController());
    }

    /** @test */
    public function test_if_execute_will_be_returning_sandbox_env()
    {
        $sandboxMock = new stdClass;
        $sandboxMock->sandbox = true;
        $prodMock  = new stdClass;
        $prodMock->prod = true;
        $controller = \Mockery::mock(GetHostedPaymentPageController::class)
            ->shouldReceive('executeWithApiResponse')
            ->once()
            ->andReturn('sandbox')
            ->getMock();

        $this->assertEquals('sandbox', $this->authorizenet->execute($controller));
    }

}