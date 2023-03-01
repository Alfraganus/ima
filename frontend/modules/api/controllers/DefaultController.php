<?php

namespace frontend\modules\api\controllers;


use common\models\AuthorApplication;
use common\models\UserApplicationContent;
use common\models\UserApplications;
use frontend\modules\api\service\ApiService;
use Yii;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{

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







    private function saveForms($application_id,$wizard_id,$form_id,$form_contents)
    {


        /*echo "<pre>";
        echo var_dump(print_r($field_value));
        die;*/
        foreach ($form_contents as $field_value) {

            foreach ($field_value as $key => $field) {

                $applicationContent = new UserApplicationContent();
                $applicationContent->user_application_id = $application_id;
                $applicationContent->user_application_wizard_id = $wizard_id;
                $applicationContent->user_application_form_id = $field_value['form_id'];
                $applicationContent->user_application_form_field_id = 111;
                $applicationContent->user_application_form_field_key = $key;
                $applicationContent->user_application_form_field_value =$field;
                if(!$applicationContent->save()) {
                    throw new \Exception(json_encode($applicationContent->errors));
                }
            }

        }
    }



}
