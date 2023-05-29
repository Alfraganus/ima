<?php

namespace frontend\modules\api\controllers;

use common\models\UserApplications;
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
}
