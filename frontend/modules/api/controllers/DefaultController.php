<?php

namespace frontend\modules\api\controllers;


use frontend\modules\api\service\ApiService;
use frontend\modules\api\service\UpdateFormService;
use Yii;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{


    public function actionSaveApplication()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $post =  $request->post();
//        return $post;
      return  (new ApiService())->saveData($user_id=1,$post,$_FILES);

    }

    public function actionUpdateApplication()
    {
         $post = Yii::$app->request->post();
        return  (new UpdateFormService())->updateWizard($user_id=1,$post,$_FILES);
    }


    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $dataString = $request->getRawBody();
      return  $data = json_decode($dataString, true);

        (new ApiService())->saveData($user_id=1,$data);
//        return $data;
    }

    public function actionGetApplicationData($application_id,$wizard_id,$user_id)
    {
        return (new ApiService())->getWizardContent($application_id,$wizard_id,$user_id);
    }

    public function actionGetApplicationSummary($application_id)
    {
        $user_id = 1;
        return (new ApiService())->getApplicationContent($application_id,$user_id);

    }







}
