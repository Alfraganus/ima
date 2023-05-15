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
            return $this->createInvoiceForPayment($data['user_application_id'], 100);
        }
        return [
            'message' => 'Request is not post'
        ];
    }


    public function actionCheckStatus($invoice_serial, $user_application_id)
    {
        return $this->checkInvoiceStatus($invoice_serial, $user_application_id);
    }


}
