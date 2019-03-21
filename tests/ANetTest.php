<?php

use ANet\Test\BaseTestCase;
use ANet\Traits\ANetPayments;

class User {
    use ANetPayments;
}



class ANetTest extends BaseTestCase
{
    /** @test */
    public function it_will_test_if_trait_implemented_class_returns_instance_of_anet_class()
    {
        $user = new User;
        $this->assertInstanceOf('ANet\ANet', $user->anet());
    }

    public function it_will_allow_user_to_


}
