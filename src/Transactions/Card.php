<?php namespace ANet\Transactions;

use net\authorize\api\controller as AnetController;
use net\authorize\api\contract\v1 as AnetAPI;
use ANet\Contracts\CardInterface;
use ANet\AuthorizeNet;
use Carbon\Carbon;

class Card extends AuthorizeNet implements CardInterface
{
    /** @var array */
    private $_data = [
        'nameOnCard'    => null,
        'cardNumber'    => null,
        'expMonth'      => null,
        'expYear'       => null,
        'amount'        => null, // in cents
        'brand'         => null, // visa, master, etc.
        'type'          => null, // credit, debit, gift, etc.
        'cvv'           => null,
    ];

    public function setNumbers(int $cardNumbers): CardInterface
    {
        $this->_data['cardNumber'] = $cardNumbers;
        return $this;
    }

    public function setCVV(int $cvvNumbers): CardInterface
    {
        $this->_data['cvv'] = $cvvNumbers;
        return $this;
    }

    public function setNameOnCard(string $name): CardInterface
    {
        $this->_data['nameOnCard'] = $name;
        return $this;
    }

    public function setAmountInCents(int $cents): CardInterface
    {
        $this->_data['amount'] = $cents;
        return $this;
    }

    public function setAmountInDollars(float $amount): CardInterface
    {
        $this->_data['amount'] = $amount / 100; // normalizing to store cents
        return $this;
    }

    public function getNumbers(): ?string
    {
        return $this->_data['cardNumber'];
    }

    public function getCVV(): ?int
    {
        return $this->_data['cvv'];
    }

    public function getNameOnCard(): ?string
    {
        return $this->_data['nameOnCard'];
    }

    public function getAmountInCents(): ?int
    {
        return $this->_data['amount'];
    }

    public function getAmountInDollars(): ?float
    {
        return $this->_data['amount'] / 100;
    }

    public function charge(): AnetAPI\CreateTransactionResponse
    {

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($this->getNumbers());
        $creditCard->setExpirationDate($this->getExpYear().'-'.$this->getExpMonth());
        $creditCard->setCardCode($this->getCVV());

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId($this->user->id);
        $customerData->setEmail($this->user->email);

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($this->getAmountInDollars());
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setCustomer($customerData);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($this->getMerchantAuthentication());
        $request->setRefId($this->getRefId());
        $request->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($request);
        return $this->execute($controller);
    }

    public function setExpMonth($month): CardInterface
    {
        $intMonth = $month;
        if (is_string($month)) {
            $intMonth = Carbon::parse('january')->month;
        }
        $this->_data['expMonth'] = $intMonth;
        return $this;
    }

    public function setExpYear(int $year): CardInterface
    {
        $intYear = $year;
        if (strlen($year) === 2) {
            $intYear = '20'.$year; // TODO: i know it should be dynamic but I guess I've at least 50 more years to update it
        }
        $this->_data['expYear'] = $intYear;
        return $this;
    }

    public function setType(string $type = 'Credit'): CardInterface
    {
        $this->_data['type'] = $type;
        return $this;
    }

    public function setBrand(string $brandName): CardInterface
    {
        $this->_data['brand'] = $brandName;
        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->_data['brand'];
    }

    public function getType(): ?string
    {
        return $this->_data['type'];
    }

    public function getExpMonth(): ?int
    {
        return $this->_data['expMonth'];
    }

    public function getExpYear(): ?int
    {
        return $this->_data['expYear'];
    }
}
