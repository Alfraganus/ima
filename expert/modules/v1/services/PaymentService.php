<?php

namespace expert\modules\v1\services;


use common\models\UserApplications;
use common\models\WizardFormField;
use expert\models\forms\ExpertFormPayment;
use frontend\modules\api\service\FormSaveService;
use Yii;
use yii\helpers\ArrayHelper;

class PaymentService
{
    public function registerUserPayment($paymentModel,$user_id)
    {
        try {
            $getApplicationType = UserApplications::findOne($paymentModel->user_application_id);
            $formComponent = WizardFormField::findOne([
                'application_id' => $getApplicationType->application_id,
                'title' => 'payment'
            ]);
            (new FormSaveService())->saveData(
                $user_id,
                [
                    'application_id' => $getApplicationType->application_id,
                    'wizard_id' => $formComponent->wizard_id,
                    'forms' => [
                        [
                            'form_id' => $formComponent->form_id,
                            'payment_done' => 1,
                            'payment_info'=> $paymentModel->invoice_json,
                            'user_application_id' => $paymentModel->user_application_id,
                        ],
                    ],
                ],
            );
        } catch (\Exception $exception) {
            return [
                'error' => $exception->getMessage()
            ];
        }

    }

    public function registerExpertPayment($paymentModel,$user_id)
    {
        try {
            (new CreateFormService)->createForm(
                [
                    'user_application_id' => $paymentModel->user_application_id,
                    'module_id' => 2,
                    'tab_id' => 1,
                    'form_id' => 2,
                    'form_info' => [
                        'payment_purpose_id' => 1,
                        'payment_date' => $paymentModel->invoice_created_at,
                        'currency' => 1,
                        'amount' => $paymentModel->invoice_amount,
                    ],
                ],
                null,
                $user_id
            );
        } catch (\Exception $exception) {
            return [
                'error' => $exception->getMessage()
            ];
        }

    }

}