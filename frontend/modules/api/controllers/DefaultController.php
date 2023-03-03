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


    public function actionIndex2()
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

    public function actionGetApplicationData()
    {
        return (new ApiService())->getWizardContent(1,1,1);
    }







}
