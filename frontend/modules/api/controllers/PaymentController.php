<?php

namespace frontend\modules\api\controllers;

use common\models\ApplicationForm;
use common\models\forms\FormPayment;
use common\models\forms\FormRequester;
use common\models\User;
use common\models\UserApplications;
use common\models\WizardFormField;
use Exception;
use expert\models\forms\ExpertFormPayment;
use frontend\models\ImaUsers;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use  common\models\Payments;
use expert\modules\v1\services\PaymentService;
use common\traits\PaymentFunctions;

class PaymentController extends Controller
{

    use PaymentFunctions;
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        unset($behaviors['authenticator']);
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
            ],
        ];

        return $behaviors;
    }
    const STATUS_OPEN = 'OPEN';
    const STATUS_PAID = 'paid';

    const STATUS_BILLING_PAID = 'invoice.paid';

    public function actionCreateInvoice()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();

            $userInfo = ImaUsers::findOne(Yii::$app->user->id);
            $applicationForm = UserApplications::findOne($data['user_application_id']);
            $formRequester = FormRequester::findOne([
                'user_id'=>1,
                'user_application_id'=>8
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
            $pnfl = $userInfo['pin'];
            $passport = $userInfo['pport_no'];

            $amount = 1000;
            $quantity = 1;
            $note =sprintf('Payment for %s application form',$applicationForm->application->name);
            $application_id = $data['user_application_id'];
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


    public function actionCheckStatus($invoice_serial, $user_application_id)
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
                if(!$formPayment) throw new Exception('Form payment has not been found!');
                $formPayment->payment_done = 1;
                $formPayment->payment_info = json_encode($paymentInfo);
                $formPayment->save();
                $formPayment->finishApplication( Yii::$app->user->id,$user_application_id);
                return [
                    'success' => true,
                    'message' => 'Payment has been successfully done!',
                    'paymentInfo' => $paymentInfo
                ];
            }
            return [
                'success'=>false,
                'message'=>'Error occured, payment status failed!'
            ];
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
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
