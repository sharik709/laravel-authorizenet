<?php
namespace ANet;

use ANet\CustomerProfile\CustomerProfile;
use ANet\PaymentProfile\PaymentProfile;

class ANet {

    /**
     * @var User
     */
    protected $user;

    /**
     * ANet constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * It will create customer profile on authorize net
     * and store payment profile id in the system.
     * @return mixed
     * @throws \Exception
     */
    public function createCustomerProfile()
    {
        return (new CustomerProfile($this->user))->create();
    }

    /**
     * @return mixed
     */
    public function getCustomerProfileId() {
        $data = \DB::table('user_gateway_profiles')
                    ->where('user_id', $this->user->id)
                    ->first();
        return $data->profile_id;
    }

    /**
     * @param $opaqueData
     * @return mixed
     */
    public function createPaymentProfile($opaqueData)
    {
        return (new PaymentProfile($this->user))->create($opaqueData);
    }


}