<?php

namespace frontend\modules\api\controllers;

use common\models\User;
use common\models\UserApplications;
use common\models\WizardFormField;
use expert\models\forms\ExpertFormPayment;
use frontend\models\ImaUsers;
use Yii;
use yii\rest\Controller;
use  common\models\Payments;
use expert\modules\v1\services\PaymentService;
use common\traits\PaymentFunctions;

class PaymentController extends Controller
{

    use PaymentFunctions;

    const STATUS_OPEN = 'OPEN';
    const STATUS_PAID = 'paid';

    const STATUS_BILLING_PAID = 'invoice.paid';

    public function actionCreateInvoice()
    {
        if (Yii::$app->request->isPost) {
            $userInfo = ImaUsers::findOne(Yii::$app->user->id);
            $data = Yii::$app->request->post();
            $name = $userInfo['full_name'];
            $email = $userInfo['email'];
            $phone = $userInfo['mob_phone_no'];
            $type = $data['type'];
            $passport = $userInfo['pport_no'];
            $pnfl = $data['pnfl'];
            $amount = $data['amount'];
            $quantity = $data['quantity'];
            $note = $data['note'];
            $application_id = $data['application_id'];
            $application_type = $data['application_type'];
            $online_license = $data['online_license'] ?? null;
            $taxid = $userInfo['taxid'] ?? null;

            $payer = self::getPayer($name, $email, $phone, $type, $passport, $pnfl, $taxid);
            if (isset($payer['status']) && $payer['status'] == 'BAD_REQUEST' || !isset($payer['id'])) return $payer;
            $payer_id = $payer['id'];
            $request_id = $online_license . $application_type . ':' . $application_id;
            $invoice = self::createInvoice($request_id, $payer_id, $amount, $quantity, $note);

            if (isset($invoice['statusCode']) && $invoice['statusCode'] = 'INVOICE_EXISTS') {
                $request_id = $online_license . $application_type . '::' . $application_id;
                $invoice = self::createInvoice($request_id, $payer_id, $amount, $quantity, $note);
            }

            if ($invoice['status'] != 'OPEN') {
                return $invoice;
            }

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

        return [
            'message' => 'Request is not post'
        ];
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


    public function actionTest()
    {
        $data = Yii::$app->request->post();
        $payment = Payments::findOne(['invoice_request_id' => $data['invoice_request_id']]);

        (new PaymentService())->registerUserPayment($payment);
        (new PaymentService())->registerExpertPayment($payment);

        return [
            'message' => 'oxshadi'
        ];
    }

    public function actionBillingResponse()
    {
        $data = Yii::$app->request->post();
        $user_id = Yii::$app->user->id;
        $billing_type = $data['type'];
        $billing_ip = Yii::$app->getRequest()->getUserIP();
        if ($billing_type !== self::STATUS_BILLING_PAID) {
            return $this->billingResponseFormat('Type is not ' . self::STATUS_BILLING_PAID, $data);
        }

        $billing_request_id = $data['data']['requestId'];
        $payment = Payments::findOne(['invoice_request_id' => $billing_request_id]);
        if (!$payment) {
            return $this->billingResponseFormat('Request id is wrong, invoice not found', $data);
        }

        $billing_serial = $data['data']['serial'];
        $billing_amount = $data['data']['payments'][0]['amount'];
        $billing_note = $data['data']['note'];
        $billing_created_at = $data['data']['createdAt'];

        $payment->billing_request_id = $billing_request_id;
        $payment->billing_invoice_serial = $billing_serial;
        $payment->billing_amount = $billing_amount;
        $payment->billing_status = $billing_type;
        $payment->billing_note = $billing_note;
        $payment->billing_created_at = $billing_created_at;
        $payment->billing_ip = $billing_ip;
        $payment->billing_json = json_encode($data);
        $payment->payment_status = true;

        if (!$payment->save()) {
            throw new \Exception(json_encode($payment->errors));
        }

        (new PaymentService())->registerUserPayment($payment,$user_id);
        (new PaymentService())->registerExpertPayment($payment,$user_id);

        return [
            'success' => true
        ];
//        return $this->billingResponseFormat(false, $data);
    }

    public function actionGetOsPdf($serviceName, $id_application)
    {
        $payment = Payment::find([
            'type_application' => $serviceName,
            'id_application' => $id_application,
            'online_license' => true,
        ])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if (!$payment) return 'payment not found';
        if (!$payment->payment_status) return 'not paid';

        /* @var $className Trademark */
        $className = 'common\\models\\' . $serviceName;
        $service = $className::findOne($id_application);

        $type_service = $service->type_service ?? null;
        $shortcode = $this->getShortcode($serviceName, $type_service);

        $qr_code = Setting::findOne(['variable' => 'qr_code_account']);
        /* @var $service Trademark */
        $data[] = [
            'os_id' => $shortcode . ' ' . $service->id,
            'name' => $service->name,
            'name_company' => $service->name_company ?? 'not available',
            'created_at' => $service->created_at,
            'expert_code' => $service->expert_code,
            'updated_at' => $service->updated_at,
            'ids_klass_item' => $service->ids_klass_item ?? 'not available',
            'type_application' => $serviceName,
            'qr_code' => $qr_code->value,
        ];
        return $data;
    }

    public function actionCheckStatus($serviceName, $id_application)
    {
        /* @var $className Trademark */
        $className = 'common\\models\\' . $serviceName;
        $service = $className::findOne($id_application);
        return $this->setPaymentStatus($service, $serviceName);
    }

    public function actionGetHistory($serviceName)
    {
        $id_user = Yii::$app->user->identity->getId();
        $className = 'common\\models\\' . $serviceName;
        $services = $className::find()
            ->select('id,updated_at,created_at,payment_amount,payment_invoice_serial,payment_status,id_payment')
            ->where(['id_user' => $id_user])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        $data = [];
        foreach ($services as $service) {
            /* @var $service Trademark */

            /* @var $payment Payment */
            $payment = Payments::findOne($service->id_payment);
            $data[] = [
                'id' => $service->id,
                'updated_at' => $service->updated_at,
                'payment_created_at' => $service->created_at,
                'payment_amount' => $payment->billing_amount ?? $service->payment_amount,
                'payment_invoice_serial' => $payment->invoice_serial ?? $service->payment_invoice_serial,
                'payment_status' => $payment->billing_status ?? $service->payment_status,
                'type_application' => $serviceName,
            ];
        }
        return $data;
    }


    private function billingResponseFormat($error = false, $data = null)
    {
        if (!$error) {
            $payment_log = new PaymentLog();
            $payment_log->ip = Yii::$app->getRequest()->getUserIP();
            $payment_log->url = '/payment/billing-response';
            $payment_log->data = json_encode($data);
            $payment_log->save();
            return [
                'AnswereId' => 1,
                'AnswereMessage' => 'OK',
                'AnswereComment' => '',
            ];
        } else {
            $payment_log = new PaymentLog();
            $payment_log->ip = Yii::$app->getRequest()->getUserIP();
            $payment_log->url = '/payment/billing-response';
            $payment_log->error = json_encode($error);
            $payment_log->data = json_encode($data);
            $payment_log->save();
            Yii::$app->response->statusCode = 406;
            return [
                'AnswereId' => 2,
                'AnswereMessage' => 'ERROR',
                'AnswereComment' => $error,
            ];
        }
    }
}
