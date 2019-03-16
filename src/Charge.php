<?php namespace ANet;

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class Charge extends AuthorizeNet
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $reff;

    /**
     * constructor
     * @param User $user
     * @param string $chargeType
     * @param int $amount
     *
     * @throws InvalidChargeTypeException
     */
    public function __construct($user)
    {
        $this->setUser($user);
    }

    /**
     * It will set user
     * @param User $user
     *
     * @return self
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * It will get the user's model instance
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function charge($amount, $paymentProfileId)
    {
        $amount = $this->convertCentsToDollar($amount);
        // Set the transaction's refId
        $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
        $profileToCharge->setCustomerProfileId($this->user->getCustomerProfileId());

        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($paymentProfileId);

        $profileToCharge->setPaymentProfile($paymentProfile);

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType( "authCaptureTransaction");

        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setProfile($profileToCharge);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($this->getMerchantAuthentication());
        $request->setRefId($this->getRefId());
        $request->setTransactionRequest( $transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);

        return $this->execute($controller);
    }
}
