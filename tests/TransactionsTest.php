<?php

namespace ANet\Tests;

use ANet\Transactions\Transactions;
use App\User;
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
        $user = factory(User::class)->create();
        $transactionInst = $user->anet()->transactions();
        $this->assertInstanceOf(Transactions::class, $transactionInst);
    }

    public function testIsApproved()
    {
        $user = factory(User::class)->create();
        $response = new CreateTransactionResponse();
        $transactionResponse = new TransactionResponseType();
        $transactionResponse->setTransId(123123);
        $message = new TransactionResponseType\MessagesAType\MessageAType();
        $message->setCode(1);
        $message->setDescription('This transaction has been approved.');
        $transactionResponse->setMessages([$message]);
        $response->setTransactionResponse($transactionResponse);
        $transaction = new Transactions($user, $response);
        $this->assertTrue($transaction->isApproved());
    }

    public function testIsRequestSuccessful_when_its_successful()
    {
        $response = new CreateTransactionResponse();
        $transactionResponse = new TransactionResponseType();
        $transactionResponse->setTransId(123123);
        $message = new MessagesType();
        $message->setResultCode('ok');
        $response->setMessages($message);
        $response->setTransactionResponse($transactionResponse);
        $transaction = new Transactions('user', $response);
        $this->assertTrue($transaction->isRequestSuccessful());
    }

    public function testIsRequestSuccessful_when_its_failed()
    {
        $response = new CreateTransactionResponse();
        $transactionResponse = new TransactionResponseType();
        $transactionResponse->setTransId(123123);
        $message = new MessagesType();
        $message->setResultCode('error');
        $response->setMessages($message);
        $response->setTransactionResponse($transactionResponse);
        $transaction = new Transactions('user', $response);
        $this->assertFalse($transaction->isRequestSuccessful());
    }

    public function testGetId()
    {
        $transId = 123123;
        $response = new CreateTransactionResponse();
        $transactionResponse = new TransactionResponseType();
        $transactionResponse->setTransId($transId);
        $message = new MessagesType();
        $message->setResultCode('error');
        $response->setMessages($message);
        $response->setTransactionResponse($transactionResponse);
        $transaction = new Transactions('', $response);
        $this->assertEquals($transId, $transaction->getId());
    }

    public function testGetRefId()
    {
        $t = Cache::get('trans');

        $transId = 123123;
        $response = new CreateTransactionResponse();
        $transactionResponse = new TransactionResponseType();
        $transactionResponse->setRefTransID($transId);
        $message = new MessagesType();
        $message->setResultCode('error');
        $response->setMessages($message);
        $response->setTransactionResponse($transactionResponse);
        $transaction = new Transactions('', $response);
        $this->assertEquals($transId, $transaction->getRefTransID());
    }

    public function testDynamicMethodCaller()
    {
        $response = new CreateTransactionResponse();
        $transactionResponse = new TransactionResponseType();
        $transactionResponse->setRefTransID(123123);
        $transactionResponse->setResponseCode('error');
        $message = new MessagesType();
        $message->setResultCode('error');
        $response->setMessages($message);
        $response->setTransactionResponse($transactionResponse);
        $transaction = new Transactions('user', $response);
        $this->assertEquals(123123, $transaction->getRefTransId());
        $this->assertEquals('error', $transaction->getResponseCode());
    }

    /** @test */
    public function all()
    {
        $user = Cache::get('user');
        $transactions = $user->anet()->transactions()->all();
        $this->assertCount(1, $transactions);
    }
}
