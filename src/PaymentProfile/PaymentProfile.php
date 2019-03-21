<?php
namespace ANet\PaymentProfile;

use ANet\AuthorizeNet;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetControllers;

class PaymentProfile extends AuthorizeNet
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function create($opaqueData)
    {
        $merchantKeys = $this->getMerchantAuthentication();

        $opaqueDataType = new AnetAPI\OpaqueDataType();
        $opaqueDataType->setDataDescriptor($opaqueData['dataDescriptor']);
        $opaqueDataType->setDataValue($opaqueData['dataValue']);

        $paymentType = new AnetAPI\PaymentType();
        $paymentType->setOpaqueData($opaqueDataType);

        $customerPaymentProfileType = new AnetAPI\CustomerPaymentProfileType;
        $customerPaymentProfileType->setPayment($paymentType);

        // Assemble the complete transaction request
        $paymentProfileRequest = new AnetAPI\CreateCustomerPaymentProfileRequest();
        $paymentProfileRequest->setMerchantAuthentication($merchantKeys);
        // Add an existing profile id to the request
        $paymentProfileRequest->setCustomerProfileId($this->user->anet()->getCustomerProfileId());
        $paymentProfileRequest->setPaymentProfile($customerPaymentProfileType);
        // Create the controller and get the response
        $controller = new AnetControllers\CreateCustomerPaymentProfileController($paymentProfileRequest);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if(!is_null($response->getCustomerPaymentProfileId()))
        {
            $this->storeInDatabase($response);
        }

        return $response;
    }

    /**
     * @param $response
     * @return mixed
     */
    public function storeInDatabase($response)
    {
        return \DB::table('user_payment_profiles')->insert([
            'user_id' => $this->user->id,
            'payment_profile_id' => $response->getCustomerPaymentProfileId()
        ]);
    }




}
