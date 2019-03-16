<?php namespace ANet;

use \net\authorize\api\controller as AnetController;
use \net\authorize\api\contract\v1 as AnetAPI;

class FormToken extends AuthorizeNet
{
    /**
     * @param int $amount
     * @return mixed
     */
    public static function generate(int $amount, $hostedPaymentPageRequest = null, $controller = null)
    {
        return (new static)
            ->setRequest($hostedPaymentPageRequest ?? new AnetAPI\GetHostedPaymentPageRequest)
            ->setController($controller ?? AnetController\GetHostedPaymentPageController::class)
            ->generateNewToken($amount);
    }

    public function generateNewToken(int $amount)
    {
        $controller = $this->getController();
        $this->setTransactionType('authCaptureTransaction', $amount);
        $this->request
            ->setMerchantAuthentication($this->getMerchantAuthentication())
            ->setRefId($this->getRefId())
            ->setTransactionRequest($this->transactionType);
        
        $controller = new $controller($this->request);

        return $this->execute($controller);
    }
}