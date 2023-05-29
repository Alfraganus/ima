<?php

namespace frontend\modules\api\controllers;

use common\models\Payments;
use common\models\UserApplications;
use expert\models\forms\ExpertForm10;
use expert\models\forms\ExpertFormDecision;
use expert\modules\v1\controllers\DefaultController;
use expert\modules\v1\services\ApplicationChatService;
use expert\modules\v1\services\UserRoleService;
use Yii;
use yii\rest\Controller;
use yii\web\UploadedFile;

/**
 * Default controller for the `v1` module
 */
class UserProfileController extends DefaultController
{
    public function actionGetUserApplications($application_type_id)
    {
        $userAplicaitons = UserApplications::findAll(['id'=>Yii::$app->user->id,'application_id'=>$application_type_id]);
        return $userAplicaitons;
    }

    public function actionAdditionalInfoForProfile($user_application_id)
    {
        $query = ['user_application_id'=>$user_application_id];
        $userForm10 = ExpertForm10::findOne($query);
        $getInitialInvoice = Payments::findOne($query);
        $getNewInvoice = ExpertFormDecision::findOne($query);
        return [
            'new_invoice_for_licence'=>json_decode($getNewInvoice->extra_info),
            'invoice_for_application_form'=>$getInitialInvoice->invoice_serial,
            'form10'=>$userForm10,

        ];
    }
}
