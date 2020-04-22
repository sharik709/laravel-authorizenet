<?php
namespace ANet\PaymentProfile;

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetControllers;
use ANet\AuthorizeNet;

class PaymentProfileRefund extends AuthorizeNet
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function handle(int $cents, $refsTransId, $paymentProfileId)
    {
        $amount = $this->convertCentsToDollar($cents);

        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($paymentProfileId);

        // Set the transaction's refId
        $customerProfile = new AnetAPI\CustomerProfilePaymentType();
        $customerProfile->setCustomerProfileId($this->user->anet()->getCustomerProfileId());
        $customerProfile->setPaymentProfile($paymentProfile);

        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($paymentProfileId);

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType( "refundTransaction");

        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setProfile($customerProfile);
        $transactionRequestType->setRefTransId($refsTransId);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($this->getMerchantAuthentication());
        $request->setRefId($this->getRefId());
        $request->setTransactionRequest($transactionRequestType);

        $controller = new AnetControllers\CreateTransactionController($request);
        return $this->execute($controller);
    }



}
