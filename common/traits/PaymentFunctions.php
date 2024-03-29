<?php

namespace common\traits;

use common\models\ExpertDecision;
use common\models\forms\FormPayment;
use common\models\forms\FormRequester;
use common\models\Payment;
use common\models\Payments;
use common\models\Programming;
use common\models\Selective;
use common\models\Trademark;
use common\models\Nmpt;

use common\models\UserApplications;
use Exception;
use frontend\models\ImaUsers;
use Yii;

trait PaymentFunctions
{

    public function checkInvoiceStatus($invoice_serial, $user_application_id)
    {
        try {
            $acceptablePaymentStatues = [
                'paid',
                'pending'
            ];
            $paymentInfo = $this->setPaymentStatus($invoice_serial);
            if (in_array($paymentInfo[0]['status'], $acceptablePaymentStatues)) {
                $formPayment = FormPayment::findOne([
                    'user_id' => Yii::$app->user->id,
                    'user_application_id' => $user_application_id,
                ]);
                if (!$formPayment) throw new Exception('Form payment has not been found!');
                $formPayment->payment_done = 1;
                $formPayment->payment_info = json_encode($paymentInfo);
                $formPayment->save();
                $formPayment->finishApplication(Yii::$app->user->id, $user_application_id);
                return [
                    'success' => true,
                    'message' => 'Payment has been successfully done!',
                    'paymentInfo' => $paymentInfo
                ];
            }
            return [
                'success' => false,
                'message' => 'Error occured, payment status failed!'
            ];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function createInvoiceForPayment($user_application_id,$amount)
    {
        $userInfo = ImaUsers::findOne(Yii::$app->user->id);
        $applicationForm = UserApplications::findOne($user_application_id);
        $formRequester = FormRequester::findOne([
            'user_id'=>Yii::$app->user->id,
            'user_application_id'=>$user_application_id
        ]);
        if ($formRequester->individual_type == 2) {
            $type = 'Юридическое лицо';
        } elseif ($formRequester->individual_type == 1) {
            $type = 'Физическое лицо';
        }
        $taxid = $formRequester->stir;
        $name = $userInfo['full_name'];
        $email = $userInfo['email'];
        $phone = $userInfo['mob_phone_no'];
        $pnfl = $formRequester->jshshir;
        $passport = $userInfo['pport_no'];

        $quantity = 1;
        $note =sprintf('Payment for %s application form',$applicationForm->application->name);
        $application_id = $user_application_id;
        $application_type =$applicationForm->application->name;
        $online_license = $data['online_license'] ?? null;

        $payer = self::getPayer($name, $email, $phone, $type, $passport, $pnfl, $taxid);
        if (isset($payer['status']) && $payer['status'] == 'BAD_REQUEST' || !isset($payer['id'])) return $payer;
        $payer_id = $payer['id'];
        $request_id =  $application_type . ':' . time();
        $invoice = self::createInvoice($request_id, $payer_id, $amount, $quantity, $note);

        if (isset($invoice['statusCode']) && $invoice['statusCode'] = 'INVOICE_EXISTS') {
            $request_id = $online_license . $application_type . '::' . $application_id;
            $invoice = self::createInvoice($request_id, $payer_id, $amount, $quantity, $note);
        }

        if ($invoice['status'] != 'OPEN') {
            return $invoice;
        }

       /* $checkPayment = Payments::find()->where(['user_application_id'=>$application_id]);
        if ($checkPayment->exists()) {
            $checkPayment->one()->delete();
        }*/

        $payment = new Payments();
        $payment->user_application_id = $application_id;
        $payment->invoice_request_id = $request_id;
        $payment->invoice_serial = $invoice['serial'];
        $payment->invoice_amount = $amount;
        $payment->invoice_status = $invoice['status'];
        $payment->invoice_note = $note;
        $payment->invoice_expire_date = $invoice['expireDate'];
        $payment->invoice_json = json_encode($invoice);
        $payment->payment_taxid = $taxid;
        $payment->payment_status = 0;
        if ($online_license) $payment->online_license = $online_license;
        if (!$payment->save()) {
            throw new \Exception(json_encode($payment->errors));
        }
        return $invoice;
    }

    public static function getPayer($name, $email, $phone, $type, $passport, $pnfl, $taxid)
    {
        $urlPart = "payer";
        $body = json_encode([
            "name" => $name,
            "email" => $email,
            "phone" => $phone,
            "type" => $type,
            "passport" => $passport,
            "pnfl" => $pnfl,
            "taxid" => $taxid,
        ]);
        return self::getBillingCurl($urlPart, 'POST', $body);
    }


    public static function createInvoice($request_id, $payer_id, $amount, $quantity, $note)
    {
        $urlPart = "invoice";
        $body = json_encode([
            "requestId" => $request_id,
            //"serviceId" => 562, staging
            "serviceId" => 726,
            //"departmentId" => 2216, staging
            "departmentId" => 2479,
            "payerId" => $payer_id,
            "amount" => $amount,
            "quantity" => $quantity,
            "note" => $note,
        ]);
        return self::getBillingCurl($urlPart, 'POST', $body);
    }


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



}