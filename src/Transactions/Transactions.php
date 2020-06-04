<?php

namespace ANet\Transactions;

use ANet\AuthorizeNet;
use BadMethodCallException;
use Exception;
use Illuminate\Support\Collection;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\contract\v1\CreateTransactionResponse;
use net\authorize\api\controller as AnetController;

class Transactions extends AuthorizeNet
{
    public $transaction;

    public function __construct($user, CreateTransactionResponse $transaction)
    {
        parent::__construct($user);
        $this->transaction = $transaction;
    }

    /**
     * It will check if the transaction is approved
     * @return bool
     */
    public function isApproved()
    {
        $responseText = $this->transaction->getTransactionResponse()->getMessages()[0]->getDescription();
        return $responseText === 'This transaction has been approved.';
    }

    /**
     * It will check if transaction request was successfully reached and handled. It does not indicate if the charge was successful or failed
     * @return bool
     */
    public function isRequestSuccessful()
    {
        return strtolower($this->transaction->getMessages()->getResultCode()) === 'ok';
    }

    /**
     * It will return transaction ID for selected transaction
     * @return int
     */
    public function getId()
    {
        return $this->transaction->getTransactionResponse()->getTransId();
    }

    /**
     * It will return all transactions for given user from authorize.net api
     * @param int $batchId
     * @return Collection
     * @throws Exception
     */
    public function get(int $batchId)
    {
        //Setting a valid batch Id for the Merchant
        $request = new AnetAPI\GetTransactionListRequest();
        $request->setMerchantAuthentication($this->getMerchantAuthentication());
        $request->setBatchId($batchId);

        $controller = new AnetController\GetTransactionListController($request);

        //Retrieving transaction list for the given Batch Id
        $response = $this->execute($controller);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            if ($response->getTransactions() == null) {
                return collect();
            }
            return collect($response->getTransactions());
        }

        $msg = $response->getMessages()->getMessage[0];
        throw new Exception('Code: ' . $msg->getCode() . '. Message: ' . $msg->getText());
    }

    /**
     * @method string getRefTransID()
     * @method string getResponseCode();
     */
    public function __call($method, $arg)
    {
        $response = $this->transaction->getTransactionResponse();
        if (method_exists($response, $method)) {
            return $response->$method($arg);
        }
        throw new BadMethodCallException('Method: ' . $method . ' Does not exists');
    }

}
