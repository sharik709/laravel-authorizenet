<?php namespace ANet\Tests;

use ANet\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;

class SubscriptionTest extends BaseTestCase
{
    use WithFaker, DatabaseMigrations;

    public function test_if_subscription_can_be_initiated_or_Created()
    {
        $user = User::factory()->create();
        $response = $user->anet()->subs()
            ->create([
                'name'  => 'Sample Subscription',
                'startDate' => now()->addDay(2),
                'totalOccurrences' => 12,
                'trialOccurrences' => 1,
                'intervalLength' => 30,
                'intervalLengthUnit' => 'days',
                'amountInDollars' => rand(0, 100),
                'trialAmountInDollars' => 0,
                'cardNumber' => 4111111111111111,
                'cardExpiry' => '2038-12',
                'invoiceNumber' => rand(02223, 123213),
                'subscriptionDescription' => $this->faker->words(10, true),
                'customerFirstName' => $this->faker->firstName,
                'customerLastName' => $this->faker->lastName
            ]);
        $this->assertNotNull($response->getSubscriptionId());
    }

    public function test_if_subscription_can_be_retrieved()
    {
        $user = User::factory()->create();

        $response = $user->anet()->subs()->getList();
        $this->assertEquals('Ok', $response->getMessages()->getResultCode());
    }

    public function test_If_subscription_status_be_be_retrieved()
    {
        $subscriptionId = '7160746';
        $user = User::factory()->create();

        $response = $user->anet()->subs()->getStatus($subscriptionId);

        $this->assertEquals('Ok', $response->getMessages()->getResultCode());
    }

    public function test_if_a_subscription_can_be_retrieved()
    {
        $subscriptionId = '7160746';
        $user = User::factory()->create();

        $response = $user->anet()->subs()->get($subscriptionId);
        $this->assertEquals('Ok', $response->getMessages()->getResultCode());
    }

    public function test_if_card_details_can_be_updated_for_a_subscription()
    {
        $subscriptionId = '7160759';
        $user = User::factory()->create();

        $response = $user->anet()->subs()->update($subscriptionId, [
            'cardNumber' => 4111111111111111,
            'cardExpiry' => '2022-12'
        ]);
        $this->assertEquals('Ok', $response->getMessages()->getResultCode());
    }

    public function test_If_a_subscription_can_be_cancelled()
    {
        $subscriptionId = '7160746';
        $user = User::factory()->create();

        $response = $user->anet()->subs()->cancel($subscriptionId);
        $this->assertEquals('Ok', $response->getMessages()->getResultCode());
    }
}
