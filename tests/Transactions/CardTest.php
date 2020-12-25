<?php namespace ANet\Tests\Transactions;

use ANet\Contracts\CardInterface;
use ANet\Tests\BaseTestCase;
use ANet\Transactions\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use net\authorize\api\contract\v1\CreateTransactionResponse;

class CardTest extends BaseTestCase
{
    use DatabaseMigrations;

    /** @var Card */
    protected $card;

    protected function setUp() : void
    {
        parent::setUp();
        $this->card = $this->_getCreditCardInstance();
    }

    private function _getCreditCardInstance($user = []) : Card
    {
        return new Card($user);
    }

    /** @test */
    public function it_follows_credit_card_contract()
    {
        $this->assertTrue((new \ReflectionClass($this->card))->implementsInterface(CardInterface::class));
    }

    /** @test */
    public function it_tests_setters_and_getters_card_numbers()
    {
        $cardNumbers = 4111111111111111;
        $card = $this->card->setNumbers($cardNumbers);
        $this->assertEquals($cardNumbers, $card->getNumbers());
    }

    /** @test */
    public function it_tests_setters_and_getters_for_card_cvv()
    {
        $cvv = 123;
        $card = $this->card->setCVV($cvv);
        $this->assertEquals($cvv, $card->getCVV());
    }

    /** @test */
    public function it_tests_Setters_and_Getters_for_name_on_Card()
    {
        $nameOnCard = 'John Doe';
        $card = $this->card->setNameOnCard($nameOnCard);
        $this->assertEquals($nameOnCard, $card->getNameOnCard());
    }

    /** @test */
    public function it_tests_Setters_and_Getters_for_number_exp_month()
    {
        $intExpMonth = 1;
        $card = $this->card->setExpMonth($intExpMonth);
        $this->assertEquals($intExpMonth, $card->getExpMonth());
    }

    /** @test */
    public function it_tests_Setters_and_Getters_for_string_exp_month()
    {
        $intExpMonth = 1;
        $card = $this->card->setExpMonth('january');
        $this->assertEquals($intExpMonth, $card->getExpMonth());
    }

    public function it_tests_Setters_and_Getters_for_number_exp_year()
    {
        $intExpMonth = 2024;
        $card = $this->card->setExpYear($intExpMonth);
        $this->assertEquals($intExpMonth, $card->getExpYear());
    }

    /** @test */
    public function it_tests_Setters_and_Getters_for_2_digit_exp_year()
    {
        $intExpMonth = 2024;
        $card = $this->card->setExpYear(24);
        $this->assertEquals($intExpMonth, $card->getExpYear());
    }

    /** @test */
    public function it_tests_if_users_card_can_be_Charged()
    {
        $user = $this->getCustomerWithPaymentProfile();
        $card = $this->_getCreditCardInstance($user);
        $response = $card
            ->setNumbers(4111111111111111)
            ->setCVV(111)
            ->setNameOnCard('John Doe')
            ->setExpMonth(4)
            ->setExpYear(42)
            ->setAmountInCents(1000) // $10
            ->charge();

        $this->assertInstanceOf(CreateTransactionResponse::class, $response);
    }

}
