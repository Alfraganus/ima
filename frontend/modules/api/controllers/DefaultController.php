<?php

namespace frontend\modules\api\controllers;


use common\models\AuthorApplication;
use common\models\UserApplicationContent;
use common\models\UserApplications;
use frontend\modules\api\service\ApiService;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{


    public function actionIndex2()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $post =  $request->post();
        (new ApiService())->saveData($user_id=1,$post,$_FILES);

    }


    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $dataString = $request->getRawBody();
        $data = json_decode($dataString, true);

        (new ApiService())->saveData($user_id=1,$data);
//        return $data;
    }

    public function actionGet()
    {
        $application_id = 1;
        $user_id = 1;
        $wizard_id = 1;
        return (new ApiService())->getWizardContent(1);
    }







}
