<?php

namespace ANet\Test;

use function get_class_methods;
use function http_build_query;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use function json_decode;
use function json_encode;
use function preg_replace;
use stdClass;
use function str_replace;

abstract class BaseTestCase extends \Tests\TestCase
{
    use DatabaseMigrations;

    /**
     * @return stdClass
     */
    public function getFakeUser()
    {
        return factory(\App\User::class)->create();
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
        $user->anet()->createPaymentProfile([
            'dataValue' => $opaqueData->dataValue,
            'dataDescriptor' => $opaqueData->dataDescriptor
        ]);
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
        if(get_magic_quotes_gpc()){
            $result = stripslashes($result);
        }

        $result = utf8_decode($result);
        $out = json_decode(str_replace("?", "", $result));
        return  $out->opaqueData;
    }


}