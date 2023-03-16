<?php

namespace expert\modules\v1\controllers;

use expert\models\forms\ExpertFormDecision;
use expert\models\forms\ExpertFormEnquiry;
use expert\models\forms\ExpertFormFeedback;
use expert\models\forms\ExpertFormNotification;
use expert\models\forms\ExpertFormPayment;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use expert\modules\v1\services\CreateFormService;
use yii\web\UploadedFile;

/**
 * Default controller for the `v1` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
            ],
        ];
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::class,
            'actions' => [
                'save-form' => ['POST'],
                'send-message' => ['POST'],
                'send-user-message' => ['POST'],
            ],
        ];
        return  $behaviors;
    }

    public function actionIndex()
    {
        return (new ExpertFormFeedback())->run(null,true);
        $post = \Yii::$app->request->post();
        return (new CreateFormService)->createForm($post, $_FILES['form_info'],\Yii::$app->user->id);
    }
}
