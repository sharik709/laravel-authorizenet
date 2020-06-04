<?php
namespace ANet;

use Illuminate\Support\Facades\Cache;
use net\authorize\api\contract\v1\TransactionResponseType;

class ANetMock
{
    /** @var string */
    const MOCK_CACHE_KEY = 'anet:mocks';

    /**
     * It will set charge mock response for testing
     * @param TransactionResponseType $mockResponse
     * @return bool
     */
    public function setChargeResponse(TransactionResponseType $mockResponse)
    {
        $mocks = $this->getAllMocks();
        $mocks['PaymentProfileCharge'] = $mockResponse;
        $this->setMock($mocks);
        return true;
    }

    /**
     * It will set customer profile mock response for testing
     * @param $mockResponse
     * @return bool
     */
    public function setCustomerProfileResponse($mockResponse)
    {
        $mocks = $this->getAllMocks();
        $mocks['CustomerProfile'] = $mockResponse;
        $this->setMock($mocks);
        return true;
    }

    /**
     * @return array
     */
    private function getAllMocks()
    {
        return Cache::get(self::MOCK_CACHE_KEY);
    }

    /**
     * @param $mocks
     */
    private function setMock($mocks)
    {
        Cache::put(self::MOCK_CACHE_KEY, $mocks);
    }

    /**
     * It will return mocked response
     * @param $class
     * @return array
     */
    public function get($class)
    {
        $mapMocks = $this->getAllMocks();
        return $mapMocks[$class] ?? '';
    }
}
