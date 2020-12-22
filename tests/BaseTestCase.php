<?php

namespace ANet\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.env' => 'testing']);
    }

    /**
     * @return stdClass
     */
    public function getFakeUser()
    {
        return User::factory()->create();
    }

    public function generateCustomerId($user = null)
    {
        if(! $user ) {
            $user = $this->getFakeUser();
        }
        $user->anet()->createCustomerProfile();

        return $user;
    }

    public function getCustomerWithPaymentProfile($user = null) {
        if( is_null($user) ) {
            $user = $this->generateCustomerId();
        }

        $opaqueData = $this->getOpaqueData();
        $source     = [
            'type'      => 'card',
            'last_4'    => '1111',
            'brand'     => 'visa'
        ];

        $user->anet()->createPaymentProfile([
            'dataValue' => $opaqueData->dataValue,
            'dataDescriptor' => $opaqueData->dataDescriptor
        ], $source);

        return $user;
    }

    public function getOpaqueData()
    {
        $payload = '{
        "securePaymentContainerRequest":
            {
                "merchantAuthentication":{
                    "name":"38hHU4u5",
                    "clientKey":"8Dt3HTXLuyvk6Cf3fD3hkj9S3nNjpT4L9Gsck3hu2P75TPgY2Cm7uP42evuwTwv5"
                },
                "data":{
                    "type":"TOKEN",
                    "id":"ccf049c2-c170-c52c-f378-ef807d723e58",
                    "token":{
                        "cardNumber":"4111111111111111",
                        "expirationDate":"032021",
                        "cardCode":"123"
                        }
                    }
                }
            }';
        $endpoint = "https://apitest.authorize.net/xml/v1/request.api";


        $result = file_get_contents($endpoint, null, stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/json' . "\r\n"
                    . 'Content-Length: ' . strlen($payload) . "\r\n",
                'content' => $payload,
            ),
        )));
//        if(get_magic_quotes_gpc()){ Deprecated in php7.4
//            $result = stripslashes($result);
//        }

        $result = utf8_decode($result);
        $out = json_decode(str_replace("?", "", $result));
        return  $out->opaqueData;
    }


}
