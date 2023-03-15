<?php

namespace expert\modules\v1\controllers;

use expert\modules\v1\services\ApplicationChatService;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

/**
 * Default controller for the `v1` module
 */
class ApplicationController extends DefaultController
{


    public function actionSaveForm()
    {
        return \Yii::$app->request->post();
    }

    public function actionSendMessage()
    {
        $post = \Yii::$app->request->post();
        $user_id = \Yii::$app->user->id;
        return (new ApplicationChatService())->sendMessage($user_id,$post);
    }
}
