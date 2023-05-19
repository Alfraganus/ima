<?php

namespace expert\modules\v1\controllers;

use common\models\ApplicationFormMedia;
use common\models\UserApplications;
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

    public function actionGetApplications($application_type)
    {
        $applications = UserApplications::find()->where([
            'application_id' => $application_type,
            'is_finished' => 1
        ])->orderBy('id DESC')
            ->all();
        return $applications;
    }

    public function actionGet20($user_application_id)
    {
        $applications = UserApplications::findOne($user_application_id);

        return [
            'data_submitted' => date('d-m-Y', $applications->date_submitted),
            'application_number' => $applications->generated_id,
        ];
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

    public function actionGetUserDocuments($user_application_id)
    {
        $formMedia = ApplicationFormMedia::findAll(['application_id' => $user_application_id]);

        return $formMedia;
    }


}
