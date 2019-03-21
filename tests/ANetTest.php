<?php

use ANet\Test\BaseTestCase;
use ANet\Traits\ANetPayments;


class ANetTest extends BaseTestCase
{
    /** @test */
    public function it_will_test_if_trait_implemented_class_returns_instance_of_anet_class()
    {
        $user = $this->getFakeUser();
        $this->assertInstanceOf('ANet\ANet', $user->anet());
    }

    /** @test */
    public function it_will_test_if_customer_profile_can_be_created()
    {
        $user = $this->getFakeUser();
        $customerProfile = $user->anet()->createCustomerProfile();
        $this->assertNotNull($customerProfile->getCustomerProfileId());
        $this->assertDatabaseHas('user_gateway_profiles', [
            'user_id' => $user->id,
            'profile_id' => $customerProfile->getCustomerProfileId()
        ]);
    }

}
