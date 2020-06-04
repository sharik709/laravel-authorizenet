<?php
namespace ANet\Tests;

use ANet\ANetMock;
use Illuminate\Support\Facades\Cache;
use net\authorize\api\contract\v1\TransactionResponseType;

class ANetMockTest extends BaseTestCase
{
    protected $class;

    public function setUp(): void
    {
        parent::setUp();
        $this->class = new ANetMock();
    }

    public function test_if_set_charge_response_sets_given_response()
    {
        $transactionResponse = new TransactionResponseType();
        $this->assertTrue($this->class->setChargeResponse($transactionResponse));
        $mapMocks = Cache::get(ANetMock::MOCK_CACHE_KEY);
        $this->assertEquals($transactionResponse, $mapMocks['PaymentProfileCharge']);
    }

    public function test_mock_getter()
    {
        $transactionResponse = new TransactionResponseType();
        $this->assertTrue($this->class->setChargeResponse($transactionResponse));

        $mock = $this->class->get('PaymentProfileCharge');
        $this->assertEquals($transactionResponse, $mock);
    }
}
