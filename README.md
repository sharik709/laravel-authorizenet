# Laravel AuthorizeNet Package

This package is built on top of official authorizenet package. Which is ```authorizenet/authorizenet```.

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
[AUTHORIZE NET]
LoginID=
ClientKey=
TransactionKey=
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

In order to charge a customer. You should first create their profile on authorize.net.

That way authorize.net will keep all cards and banks details inside customer profile.

Later, you can use customer profile id to get payment and other details. This package allows you to simply call ```$user->anet()->createCustomerProfile()```
and you are ready to move.

---

#### Payment Profile
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

#### Charge a Payment Profile
```php
// Amount in cents 
$user->anet()->charge(19000, $paymentProfileId]);
```
User can be charged with Payment Profile.

Payment Profile is a unique id for a credit card or bank. Read about Payment Profile above.

Most people prefer saving charge amount in cents. So, You pass in the cents you want to charge like ```1000``` cents for ```$10```



---

# Under Development
#### This package is under development. Please do not use this in production

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



__

# License
MIT