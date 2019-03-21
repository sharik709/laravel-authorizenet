<?php
namespace ANet;

use ANet\CustomerProfile\CustomerProfile;

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
     */
    public function createCustomerProfile()
    {
        return (new CustomerProfile($this->user))->create();
    }





}