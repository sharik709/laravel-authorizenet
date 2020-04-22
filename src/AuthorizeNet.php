<?php
namespace ANet;

use net\authorize\api\contract\v1 as AnetAPI;

abstract class AuthorizeNet
{

    /**
     * @var AnetAPI\MerchantAuthenticationType
     */
    protected $merchantAuthentication;

    /** @var mixed */
    protected $request;

    /** @var string */
    protected $refId;

    /** @var AnetAPI\TransactionRequestType */
    protected $transactionType;

    /**
     * It will setup and get merchant authentication keys
     */
    public function getMerchantAuthentication() : AnetAPI\MerchantAuthenticationType
    {
        $this->merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $this->merchantAuthentication->setName($this->_getLoginID());
        $this->merchantAuthentication->setTransactionKey($this->_getTransactionKey());
        return $this->merchantAuthentication;
    }

    private function _getLoginID() {
        $loginId = config('authorizenet.login_id');
        if (!$loginId) {
            throw new \Exception('Please provide Login ID in .env file. Which you can get from authorize.net');
        }

        return $loginId;
    }

    private function _getTransactionKey() {
        $transactionKey = config('authorizenet.transaction_key');
        if (!$transactionKey) {
            throw new \Exception('Please provide transaction key in .env file. Which you can get from authorize.net');
        }

        return $transactionKey;
    }

    /**
     * @param $requestObject
     * @return $this
     */
    public function setRequest($requestObject)
    {
        $this->request = $requestObject;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param string $refId
     * return $this
     */
    public function setRefId(string $refId)
    {
        $this->refId = $refId;
        return $this;
    }

    /**
     * it will return refId if not provided then time
     * @return string
     */
    public function getRefId()
    {
        return $this->refId || time();
    }

    /**
     * @param string $type
     * @param int $amount
     * @return $this
     */
    public function setTransactionType(string $type, int $amount)
    {
        $this->transactionType = new AnetAPI\TransactionRequestType;
        $this->transactionType->setTransactionType($type);
        $this->transactionType->setAmount($this->convertCentsToDollar($amount));
        return $this;
    }

    /**
     * @return AnetAPI\TransactionRequestType
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param int $cents
     * @return string
     */
    public function convertCentsToDollar(int $cents)
    {
        return $cents / 100;
    }

    /**
     * @param $dollars
     * @return string
     */
    public function convertDollarsToCents($dollars)
    {
        return $dollars * 100;
    }

    /**
     * @param $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param $controller
     * @return mixed
     */
    public function execute($controller)
    {
        if( app()->environment() === 'testing' || app()->environment() === 'local' )
        {
            return $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        }
        return $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
    }

}
