<?php

namespace expert\modules\v1\services;


use common\models\UserApplications;
use common\models\WizardFormField;
use frontend\modules\api\service\FormSaveService;
use Yii;
use yii\helpers\ArrayHelper;

class PaymentService
{
   public function registerUserPayment($paymentModel)
   {
       $getApplicationType = UserApplications::findOne($paymentModel->user_application_id);
       $formComponent = WizardFormField::findOne([
           'application_id'=>$getApplicationType->application_id,
           'title'=>'payment'
       ]);
       (new FormSaveService())->saveData(
           Yii::$app->user->id,
           [
               'application_id' => $paymentModel->user_application_id,
               'wizard_id' => $formComponent->wizard_id,
               'forms' => [
                   0 => [
                       'form_id' => $formComponent->form_id
                   ],
               ],
           ],
       );
   }

   public function registerExpertPayment($paymentModel)
   {
       (new CreateFormService)->createForm(
           [
               'user_application_id' => $paymentModel->user_application_id,
               'module_id' => 2,
               'tab_id' => 1,
               'form_id' => 2,
               'form_info' => [
                   'payment_purpose_id' => 1,
                   'payment_date' => $paymentModel->invoice_created_at,
                   'currency' => 'UZS',
                   'amount' => $paymentModel->billing_amount,
               ],
           ],
           null,
           Yii::$app->user->id
       );
   }

}