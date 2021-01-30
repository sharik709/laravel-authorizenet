<?php

namespace ANet\Tests;

use ANet\Transactions\Transaction;
use App\Models\User;
use Cache;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use net\authorize\api\contract\v1\CreateTransactionResponse;
use net\authorize\api\contract\v1\MessagesType;
use net\authorize\api\contract\v1\TransactionResponseType;

class TransactionsTest extends BaseTestCase
{
    use DatabaseMigrations;

    public function testTransaction()
    {
        $this->markTestSkipped('Idea of this class has been skipped');
        $user = User::factory()->create();
        $transactionInst = $user->anet()->transaction();
        $this->assertInstanceOf(Transaction::class, $transactionInst);
    }

    public function testIsApproved()
    {
        $this->markTestSkipped('Idea of this class has been skipped');
        $user = User::factory()->create();
        $response = new CreateTransactionResponse();
        $transactionResponse = new TransactionResponseType();
        $transactionResponse->setTransId(123123);
        $message = new TransactionResponseType\MessagesAType\MessageAType();
        $message->setCode(1);
        $message->setDescription('This transaction has been approved.');
        $transactionResponse->setMessages([$message]);
        $response->setTransactionResponse($transactionResponse);
        $transaction = new Transaction($user, $response);
        $this->assertTrue($transaction->isApproved());
    }

    public function testIsRequestSuccessful_when_its_successful()
    {
        $this->markTestSkipped('Idea of this class has been skipped');
        $response = new CreateTransactionResponse();
        $transactionResponse = new TransactionResponseType();
        $transactionResponse->setTransId(123123);
        $message = new MessagesType();
        $message->setResultCode('ok');
        $response->setMessages($message);
        $response->setTransactionResponse($transactionResponse);
        $transaction = new Transaction('user', $response);
        $this->assertTrue($transaction->isRequestSuccessful());
    }

    public function testIsRequestSuccessful_when_its_failed()
    {
        $this->markTestSkipped('Idea of this class has been skipped');
        $response = new CreateTransactionResponse();
        $transactionResponse = new TransactionResponseType();
        $transactionResponse->setTransId(123123);
        $message = new MessagesType();
        $message->setResultCode('error');
        $response->setMessages($message);
        $response->setTransactionResponse($transactionResponse);
        $transaction = new Transaction('user', $response);
        $this->assertFalse($transaction->isRequestSuccessful());
    }

    public function testGetId()
    {
        $this->markTestSkipped('Idea of this class has been skipped');


        $transId = 123123;
        $response = new CreateTransactionResponse();
        $transactionResponse = new TransactionResponseType();
        $transactionResponse->setTransId($transId);
        $message = new MessagesType();
        $message->setResultCode('error');
        $response->setMessages($message);
        $response->setTransactionResponse($transactionResponse);
        $transaction = new Transaction('', $response);
        $this->assertEquals($transId, $transaction->getId());
    }

    public function testGetRefId()
    {
        $t = Cache::get('trans');
        $this->markTestSkipped('Idea of this class has been skipped');

        $transId = 123123;
        $response = new CreateTransactionResponse();
        $transactionResponse = new TransactionResponseType();
        $transactionResponse->setRefTransID($transId);
        $message = new MessagesType();
        $message->setResultCode('error');
        $response->setMessages($message);
        $response->setTransactionResponse($transactionResponse);
        $transaction = new Transaction('', $response);
        $this->assertEquals($transId, $transaction->getRefTransID());
    }

    public function testDynamicMethodCaller()
    {
        $this->markTestSkipped('Idea of this class has been skipped');

        $response = new CreateTransactionResponse();
        $transactionResponse = new TransactionResponseType();
        $transactionResponse->setRefTransID(123123);
        $transactionResponse->setResponseCode('error');
        $message = new MessagesType();
        $message->setResultCode('error');
        $response->setMessages($message);
        $response->setTransactionResponse($transactionResponse);
        $transaction = new Transaction('user', $response);
        $this->assertEquals(123123, $transaction->getRefTransId());
        $this->assertEquals('error', $transaction->getResponseCode());
    }

    /** @test */
    public function all()
    {
        $this->markTestSkipped('Idea of this class has been skipped');

        $user = Cache::get('user');
        $transactions = $user->anet()->transactions()->all();
        $this->assertCount(1, $transactions);
    }
}
