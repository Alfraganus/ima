<?php

namespace frontend\modules\api\controllers;

use common\models\UserApplications;
use expert\modules\v1\controllers\DefaultController;
use expert\modules\v1\services\ApplicationChatService;
use expert\modules\v1\services\UserRoleService;
use yii\rest\Controller;
use yii\web\UploadedFile;

/**
 * Default controller for the `v1` module
 */
class UserProfileController extends Controller
{
    public function actionGetUserApplications($user_application_id)
    {
        $userAplicaitons = UserApplications::find()->all();
        return $userAplicaitons;
    }
}
