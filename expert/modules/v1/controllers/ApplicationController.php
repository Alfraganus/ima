<?php

namespace expert\modules\v1\controllers;

use expert\modules\v1\services\ApplicationChatService;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

/**
 * Default controller for the `v1` module
 */
class ApplicationController extends DefaultController
{

    private $applicationChatService;

    public function __construct($id, $module, $config = [])
    {
        $this->applicationChatService = new ApplicationChatService();
        parent::__construct($id, $module, $config);
    }


    public function actionSendExpertMessage()
    {
        return $this->applicationChatService->sendMessage(
            Yii::$app->user->id,
            Yii::$app->request->post()
        );
    }

    public function actionGetExpertMessage($user_application_id)
    {
        return $this->applicationChatService->getFormMessage(
            htmlspecialchars($user_application_id)
        );
    }


}
