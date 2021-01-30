<?php namespace ANet;

use DateTime;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class Subscription extends AuthorizeNet
{

    public function create(array $data)
    {
        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName($data['name']);

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($data['intervalLength']);
        $interval->setUnit($data['intervalLengthUnit'] ?? 'days');

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new DateTime($data['startDate']));
        $paymentSchedule->setTotalOccurrences($data['totalOccurrences']);
        $paymentSchedule->setTrialOccurrences($data['trialOccurrences']);


        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($data['cardNumber']);
        $creditCard->setExpirationDate($data['cardExpiry']);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($data['amountInDollars']);
        $subscription->setTrialAmount($data['trialAmountInDollars']);

        $subscription->setPayment($payment);

        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($data['invoiceNumber']);
        $order->setDescription($data['subscriptionDescription']);
        $subscription->setOrder($order);

        $billTo = new AnetAPI\NameAndAddressType();
        $billTo->setFirstName($data['customerFirstName']);
        $billTo->setLastName($data['customerLastName']);

        $subscription->setBillTo($billTo);

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($this->getMerchantAuthentication());
        $request->setRefId($this->getRefId());
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);
        return $this->execute($controller);
    }

    public function getList(array $options = [])
    {
        $sorting = new AnetAPI\ARBGetSubscriptionListSortingType();
        $sorting->setOrderBy($options['orderBy'] ?? 'id');
        $sorting->setOrderDescending($options['orderDescending'] ?? false);

        $paging = new AnetAPI\PagingType();
        $paging->setLimit($options['limit'] ?? 1000);
        $paging->setOffset($options['offset'] ?? 1);

        $request = new AnetAPI\ARBGetSubscriptionListRequest();
        $request->setMerchantAuthentication($this->getMerchantAuthentication());
        $request->setRefId($this->getRefId());
        $request->setSearchType($options['searchType'] ?? "subscriptionActive");

        $request->setSorting($sorting);
        $request->setPaging($paging);


        $controller = new AnetController\ARBGetSubscriptionListController($request);

        return $this->execute($controller);
    }

    public function getStatus(string $subscriptionId)
    {
        $request = new AnetAPI\ARBGetSubscriptionStatusRequest();
        $request->setMerchantAuthentication($this->getMerchantAuthentication());
        $request->setRefId($this->getRefId());
        $request->setSubscriptionId($subscriptionId);

        $controller = new AnetController\ARBGetSubscriptionStatusController($request);
        return $this->execute($controller);
    }

    public function get(string $subscriptionId)
    {
        // Creating the API Request with required parameters
        $request = new AnetAPI\ARBGetSubscriptionRequest();
        $request->setMerchantAuthentication($this->getMerchantAuthentication());
        $request->setRefId($this->getRefId());
        $request->setSubscriptionId($subscriptionId);
        $request->setIncludeTransactions(true);

        // Controller
        $controller = new AnetController\ARBGetSubscriptionController($request);
        return $this->execute($controller);
    }

    public function update(string $subscriptionId, array $data)
    {

        $subscription = new AnetAPI\ARBSubscriptionType();

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($data['cardNumber']);
        $creditCard->setExpirationDate($data['cardExpiry']);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        $subscription->setPayment($payment);


        $request = new AnetAPI\ARBUpdateSubscriptionRequest();
        $request->setMerchantAuthentication($this->getMerchantAuthentication());
        $request->setRefId($this->getRefId());
        $request->setSubscriptionId($subscriptionId);
        $request->setSubscription($subscription);

        $controller = new AnetController\ARBUpdateSubscriptionController($request);
        return $this->execute($controller);
    }

    public function cancel(string $subscriptionId)
    {
        $request = new AnetAPI\ARBCancelSubscriptionRequest();
        $request->setMerchantAuthentication($this->getMerchantAuthentication());
        $request->setRefId($this->getRefId());
        $request->setSubscriptionId($subscriptionId);

        $controller = new AnetController\ARBCancelSubscriptionController($request);
        return $this->execute($controller);
    }
}
