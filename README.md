# Laravel AuthorizeNet Package

This package is built on top of official authorizenet package. Which is ```authorizenet/authorizenet```.

## Usage

#### Create Authorize.net customer profile
```php
$user->anet()->createCustomerProfile();
```
Saving credit card information in database is a risk and also you have to be PCI complient in order to save credit card information in your database.
Small business do not want the hassle of PCI.

Small Businesses just want to charge their customer and be done with it. To solve this problem authorize.net provides acceptjs.
It will allow you to send credit card details directly to authorize.net and then they will send you some data called opaque data. You will need to submit two values from
opaque data 'DataValue' and 'DataDescriptor'.

In order to charge a customer. You should first create their profile on authorize.net via above function.

That way authorize.net will keep all cards and banks details inside customer profile.

Later, you can use customer profile id to get payment and other details. This package allows you to simply call ```$user->anet()->createCustomerProfile()```
and you are ready to move.

---

#### Create Payment Profile
```php
$user->anet()->createPaymentProfile([
    'dataValue' => $opaqueData->dataValue,
    'dataDescriptor' => $opaqueData->dataDescriptor
])
```
Payment Profile is a way to solve a problem with storing credit and bank details in database.

In order to save credit card or bank details in database you need to be PCI compliant. Which in case of
small business not ideal to do. So, Instead of using actual card numbers and bank details you can use
payment profiles it is a unique id generated for a credit card or bank. Which you can store in your database and charge
credit card or bank account.

In order to create payment profile you need to setup a form. Where user will provide card or bank details. Authorize.net provides Accept.js which will take data from
your form and send it to authorize.net and once validated authorize.net will send you ```$opaqueData``` which then
needs to be sent to your server and process creation of a payment profile. 

After creating a payment profile successfully. You will get Payment Profile ID. Which you can use to charge that 
card or bank account for which this payment profile is created and you can reuse it as many times as it referring to a valid credit card or bank account.

---

#### Get Payment Profiles (Get added Cards and Bank Accounts)
```php
$paymentMethods = $user->anet()->getPaymentMethods();
```
It will allow you to get all payment methods or payment profiles created by you.  

to only get card. You can use.
```php
$paymentCards = $user->anet()->getPaymentCardProfiles();
```
to only get bank. You can use.
```php
$paymentBanks = $user->anet()->getPaymentBankProfiles();
```

---


#### Charge a Payment Profile (Charge Card or Bank)
```php
// Amount in cents 
$user->anet()->charge(19000, $paymentProfileId]);
```
User can be charged with Payment Profile.

Payment Profile is a unique id for a credit card or bank. Read about Payment Profile above.

Most people prefer saving charge amount in cents. So, You pass in the cents you want to charge like ```1000``` cents for ```$10```



---



#### Refunding transaction
```php
$user->anet()->refund($amount_in_cents, $refsTransId, $payment_profile_id);
```

`$amount_in_cents` it the refund amount.

`$refsTransId` You get this id when you charge a user

`$payment_profile_id` read above for more information on payment profile id. Basically, here it means refund to given payment profile id

---

# Under Development
#### I'm using this code on production but please be sure. That it does what you expect before using it in production.

---
## Available Methods
```php
$user->anet()->charge(1200, $paymentProfile); // $12
```

It will create customer profile on authorizenet
```php
$user->anet()->createCustomerProfile();
```

It will return authorizenet's customer profile id
```php
$user->anet()->getCustomerProfileId();
```

```php
$user->anet()->getPaymentProfiles();
```

```php
$user->anet()->createPaymentProfile();
```

### Card Charge
```php
        // $user is your laravel user model
        $response = $user
            ->anet()
            ->card()
            ->setNumbers(4111111111111111)
            ->setCVV(111)
            ->setNameOnCard('John Doe')
            ->setExpMonth(4)
            ->setExpYear(42)
            ->setAmountInCents(1000) // $10
            ->charge();
```

## Transaction Methods
when you run `$user->anet()->charge($amount, $paymentProfile)` on successful request you will get following methods

- getTransactionResponse:
    - getResponseCode
    - setResponseCode
    - getRawResponseCode
    - setRawResponseCode
    - getAuthCode
    - setAuthCode
    - getAvsResultCode
    - setAvsResultCode
    - getCvvResultCode
    - setCvvResultCode
    - getCavvResultCode
    - setCavvResultCode
    - getTransId
    - setTransId
    - getRefTransID
    - setRefTransID
    - getTransHash
    - setTransHash
    - getTestRequest
    - setTestRequest
    - getAccountNumber
    - setAccountNumber
    - getEntryMode
    - setEntryMode
    - getAccountType
    - setAccountType
    - getSplitTenderId
    - setSplitTenderId
    - getPrePaidCard
    - setPrePaidCard
    - addToMessages
    - issetMessages
    - unsetMessages
    - getMessages
    - setMessages
    - addToErrors
    - issetErrors
    - unsetErrors
    - getErrors
    - setErrors
    - addToSplitTenderPayments
    - issetSplitTenderPayments
    - unsetSplitTenderPayments
    - getSplitTenderPayments
    - setSplitTenderPayments
    - addToUserFields
    - issetUserFields
    - unsetUserFields
    - getUserFields
    - setUserFields
    - getShipTo
    - setShipTo
    - getSecureAcceptance
    - setSecureAcceptance
    - getEmvResponse
    - setEmvResponse
    - getTransHashSha2
    - setTransHashSha2
    - getProfile
    - setProfile
    - getNetworkTransId
    - setNetworkTransId
    - jsonSerialize
    - set

- getProfileResponse

- getRefId

- getMessages

- getSessionToken

___

## Recurring Payments or Subscriptions

You can use any of the following method to get instance of subscription class which allow you to manage subscriptions. They all are same, gave them different aliases because wanted to make sure it fits your env context.


```phpt
    $user->anet()->subs();
    $user->anet()->subscription();
    $user->anet()->recurring();
```

You can create, update, cancel and get subscriptions via following methods

#### 1. Create Subscription
```phpt
$response = $user->anet()->subs()->create([
    'name'  => 'Sample Subscription',
    'startDate' => '2022-03-12',
    'totalOccurrences' => 12,
    'trialOccurrences' => 1,
    'intervalLength' => 30,
    'intervalLengthUnit' => 'days',
    'amountInDollars' => 10, // $10
    'trialAmountInDollars' => 0, // $0
    'cardNumber' => 4111111111111111,
    'cardExpiry' => '2038-12',
    'invoiceNumber' => 1232434243,
    'subscriptionDescription' => 'Some services will be provided some how.',
    'customerFirstName' => 'john',
    'customerLastName' => 'doe'
]);
```
`$response` will give you subscription id and required details regarding the subscription.

#### 2. Update Subscription
```phpt
$response = $user->anet()->subs()->update($subscriptionId, [
    'cardNumber' => 4111111111111111,
    'cardExpiry' => '2022-12'
]);
```

#### 3. Cancel Subscription
```phpt
$response = $user->anet()->subs()->cancel($subscriptionId);
```

#### 4. Get a Subscription
```phpt
$response = $user->anet()->subs()->get($subscriptionId);
```

#### 5. Get all subscriptions with filters
```phpt
$options = [
    'orderBy' => 'id',
    'orderDescending' => false,
    'limit' => 300, // Default is 1000
    'offset' => 2, // Default is 1
    'searchType' => 'subscriptionActive', // subscriptionActive, subscriptionInactive. Default is subscriptionActive
];
$response = $user->anet()->subs()->getList($options);

```

if you don't want to use filters don't pass any options array. It will use defaults and give you your list.

___

# License
MIT
----

## Steps to install

### Step 1
do composer require
```composer require sharik709/laravel-authorizenet```

### Step 2
If you are using laravel 5.5 or above then you do not need to register service provider. If you are using 5.4 or less then you will need to register service provider in you ```config/app.php``` ```providers``` array.

```php
ANet/AuthorizeNetServiceProvider::class
```

### Step 3
in your ```.env``` file you will need to define following keys
```
AUTHORIZE_NET_LOGIN_ID=
AUTHORIZE_NET_CLIENT_KEY=
AUTHORIZE_NET_TRANSACTION_KEY=
```
you can obtain above information from authorize.net's sandbox or live account. It's best if you define above keys in your ```.env.example``` file as well

### Step 4
This package requires a table to hold records for cards profile ids and other information. So, You will need to run migration to migrate this package's tables.
```php
php artisan migrate
```

### Step 5
To make ```anet()``` method available on your user model. You need to add ```ANet\Traits\ANetPayments``` trait to your model.
```php
use ANet\Traits\ANetPayments;

class User extends Model {
    use ANetPayments;
}
``` 

---
