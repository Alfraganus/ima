<?php

namespace common\traits;

use common\models\ExpertDecision;
use common\models\Payment;
use common\models\Programming;
use common\models\Selective;
use common\models\Trademark;
use common\models\Nmpt;

use Yii;

trait PaymentFunctions
{
    private static function getAuthData(){
        $basic_auth_username = Yii::$app->params['billing_auth_username'];
        $auth_data = $basic_auth_username.':';
        return $auth_data;
    }

    private static function getFullUrl($urlPart){
        $billing_base_url = Yii::$app->params['billing_base_url'];
        $full_url = $billing_base_url.$urlPart;
        return $full_url;
    }

    public static function getBillingCurl($urlPart, $requestType, $body = ''){
        $basic_auth = self::getAuthData();
        $full_url = self::getFullUrl($urlPart);
        $curl = curl_init($full_url);

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $requestType,
            CURLOPT_USERPWD => $basic_auth,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            die($err);
        }
        return  json_decode($response, true);
    }

    public function setPaymentStatus($invoice_serial){
        $urlPart = "invoice/$invoice_serial/payments";
        $response = $this->getBillingCurl($urlPart, 'GET');
        return $response;
    }

    protected function makeExpertCode($current_number, $shortcode){
        $trailing_zeros = str_pad($current_number+1, 4, '0', STR_PAD_LEFT);
        $year = date("Y");
        return $shortcode.' '.$year.$trailing_zeros;
    }



}