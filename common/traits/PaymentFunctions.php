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

    public function setPaymentStatus($service, $serviceName){
        $payment_invoice_serial = $service->payment_invoice_serial ?? $service->payment->invoice_serial;
        $payment_status = $service->payment_status ?? $service->payment->invoice_status;
        //check payment status for each trademark
        if ($payment_invoice_serial && $payment_status == self::STATUS_OPEN) {
            $urlPart = "invoice/$payment_invoice_serial/payments";
            $response = $this->getBillingCurl($urlPart, 'GET');
            if ($service->expert_code == null && isset($response[0]['status']) && $response[0]['status'] == self::STATUS_PAID){

                $service_last_number = $this->getServiceLastNumber($service, $serviceName);
                $shortcode = $this->getShortcode($service, $serviceName);
                $expert_code = $this->makeExpertCode($service_last_number,$shortcode);
                $service->expert_code_number = $service_last_number+1;
                $service->expert_code = $expert_code;

                $payment = Payment::findOne($service->id_payment);
                $payment->billing_status = $response[0]['status'];
                $payment->payment_status = true;
                $payment->save();

                $service->payment_status = $response[0]['status'];
                $service->save();
                if ($service->hasErrors()){
                    return $service->errors;
                }
                $expert_decision = ExpertDecision::findOne([
                    'type_application' => $serviceName,
                    'id_application' => $service->id
                ]);
                $expert_decision->expert_code = $expert_code;
                $expert_decision->expert_code_created_at = time();
                $expert_decision->save();
                return $expert_code;
            }
        }
        return false;
    }

    protected function makeExpertCode($current_number, $shortcode){
        $trailing_zeros = str_pad($current_number+1, 4, '0', STR_PAD_LEFT);
        $year = date("Y");
        return $shortcode.' '.$year.$trailing_zeros;
    }

    protected function getShortcode($classname, $type_service){
        if ($classname == 'Trademark') {
            $shortcode = 'MGU';
        } else if ($classname == 'Programming') {
            if ($type_service == Programming::TYPE_PROGRAMMING) {
                $shortcode = 'DGU';
            } else if ($type_service == Programming::TYPE_DATABASE) {
                $shortcode = 'BGU';
            }
        } else if ($classname == 'Invention') {
            $shortcode = 'IAP';
        } else if ($classname == 'UsefulModel') {
            $shortcode = 'FAP';
        } else if ($classname == 'Industry') {
            $shortcode = 'SAP';
        } else if ($classname == 'Selective') {
            if ($type_service == Selective::TYPE_PLANT) {
                $shortcode = 'NAP';
            } else if ($type_service == Selective::TYPE_ANIMAL){
                $shortcode = 'ZAP';
            }
        } else if ($classname == 'Topology') {
            $shortcode = 'TGU';
        } else if ($classname == 'Nmpt') {
            $shortcode = 'JGU';
        } else if ($classname == 'Geography') {
            $shortcode = 'GK';
        }

        return $shortcode;
    }

    protected function getServiceLastNumber($service,$serviceName = null){
        if ($service == 'Trademark') {
            $service_last_number = Trademark::find()
                ->select('id,expert_code_number')
                ->where(['IS NOT', 'expert_code_number', null])
                ->orderBy(['expert_code_number'=>SORT_DESC])
                ->one();
        } else if ($service == 'Programming') {
            $service_last_number = Programming::find()
                ->select('id,expert_code_number')
                ->where(['IS NOT', 'expert_code_number', null])
                ->andWhere(['type_service'=>$serviceName])
                ->orderBy(['expert_code_number'=>SORT_DESC])
                ->one();
            if (!$service_last_number && $serviceName){
                if ($serviceName == Programming::TYPE_PROGRAMMING) {
                    return Yii::$app->params['programming_dgu_start'];
                } else {
                    return Yii::$app->params['programming_bgu_start'];
                }
            } else {
                return $service_last_number->expert_code_number ?? 1;
            }
        } else if ($service == 'Nmpt') {
            $service_last_number = Nmpt::find()
                ->select('id,expert_code_number')
                ->where(['IS NOT', 'expert_code_number', null])
                ->orderBy(['expert_code_number'=>SORT_DESC])
                ->one();
            if (!$service_last_number){
                return Yii::$app->params['nmtp_start'];
            } else {
                return $service_last_number->expert_code_number;
            }
        } else if ($service == 'Selective') {
            $service_last_number = Selective::find()
                ->select('id,expert_code_number')
                ->where(['IS NOT', 'expert_code_number', null])
                ->orderBy(['expert_code_number'=>SORT_DESC])
                ->one();
            if (!$service_last_number){
                return Yii::$app->params['selective_start'];
            } else {
                return $service_last_number->expert_code_number;
            }
        } else {
            die('other services under development');
        }
        return $service_last_number->expert_code_number;
    }

}