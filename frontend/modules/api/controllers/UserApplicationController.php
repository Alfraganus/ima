<?php

namespace frontend\modules\api\controllers;

use common\models\UserApplications;
use expert\modules\v1\controllers\DefaultController;
use expert\modules\v1\services\ApplicationChatService;
use expert\modules\v1\services\UserRoleService;
use yii\web\UploadedFile;

/**
 * Default controller for the `v1` module
 */
class UserApplicationController extends DefaultController
{

    private $applicationChatService;

    public function __construct($id, $module, $config = [])
    {
        $this->applicationChatService = new ApplicationChatService();


        parent::__construct($id, $module, $config);
    }

    public function actionSendUserMessage()
    {
        $post = \Yii::$app->request->post();
        if(!$post['user_application_id']) {
            return  [
              'message'=>'user_application_id not provided'
            ];
        }
        return $this->applicationChatService->sendUserMessage($post,UploadedFile::getInstanceByName('file'));

    }

    public function actionGetUserMessage($user_application_id)
    {
        return $this->applicationChatService->getFormMessage(
            htmlspecialchars($user_application_id),
            false
        );
    }
    public function actionGetUserApplications($user_application_id)
    {
        $userAplicaitons = UserApplications::find()->all();
        return $userAplicaitons;
    }


}
