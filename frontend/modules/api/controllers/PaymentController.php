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
          return $this->createInvoiceForPayment($data['user_application_id'],100);
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
            throw new Exception($exception->getMessage());
        }
    }


}
