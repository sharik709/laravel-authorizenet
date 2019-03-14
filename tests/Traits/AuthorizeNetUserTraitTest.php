<?php namespace ANet\Test\Traits;

use ANet\Test\TestCase;
use ANet\ANet;

/** It will create a instace of class which is using trait which needs to be tested */
use ANet\Traits\AuthorizeNetUserTrait as TestableTrait;
class AuthorizeNetUserTraitClass {
    use TestableTrait;
}


class AuthorizeNetUserTraitTest extends TestCase {

    public $trait;

    protected function setUp() :void
    {
        parent::setUp();
        $this->trait = new AuthorizeNetUserTraitClass;
    }

    /** @test */
    public function it_will_test_if_trait_has_anet_method()
    {
        $this->assertTrue(method_exists($this->trait, 'anet'));
    }

    /** @test */
    public function it_will_Test_if_calling_anet_method_on_user_Trait_it_will_Return_authorizenet_class_instance()
    {
        $this->assertInstanceOf(ANet::class, $this->trait->anet());
    }

}