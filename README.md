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
AUTHORIZE_NET_TRANSACTION_KEY=
AUTHORIZE_NET_LOGIN_ID=
AUTHORIZE_NET_CLIENT_KEY=
```
you can obtain above information from authorize.net's sandbox or live account. It's best if you define above keys in your ```.env.example``` file as well

### Step 4
This package requries a table to hold records for cards profile ids and other information. So, You will need to run migration to migrate this package's tables.
```php
php artisan migrate
```
---

## Usage
---
## Create Authorize.net customer profile
```php
$user->anet()->createCustomerProfile();
```
It will check if you have already have created a profile for this customer. If not then it will create profile and get you the profile of the customer.

---

## Charge a Card
```php
// Amount in cents and options for more information
$user->charge(19000, []);
```
It takes first parameter as cents and seconds parameter is the options you can pass in.

---

# Under Development
#### This package is under development. Please do not use this in production

---
## Avaiable Methods
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