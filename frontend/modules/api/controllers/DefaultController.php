<?php

namespace frontend\modules\api\controllers;


use frontend\modules\api\service\FormReadService;
use frontend\modules\api\service\FormSaveService;
use frontend\modules\api\service\UpdateFormService;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        unset($behaviors['authenticator']);
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
            ],
        ];

        return $behaviors;
    }


    public function actionSaveApplication()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $post = $request->post();
        return (new FormSaveService())->saveData($user_id = 1, $post, $_FILES);

    }

    public function actionUpdateApplication()
    {
        $post = Yii::$app->request->post();
        return (new UpdateFormService())->updateWizard($user_id = 1, $post, $_FILES);
    }



    public function actionGetApplicationData($application_id, $wizard_id, $user_id)
    {
        return (new FormReadService())->getWizardContent($application_id, $wizard_id, $user_id);
    }

    public function actionGetApplicationSummary($application_id)
    {
        return (new FormReadService())->getApplicationContent(
            $application_id,
            Yii::$app->user->id
        );

    }


}
