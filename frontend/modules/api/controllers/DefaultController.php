<?php

namespace frontend\modules\api\controllers;


use common\models\UserApplicationContent;
use common\models\UserApplications;
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

        // Get the JSON data as a string
        $dataString = $request->getRawBody();

        // Convert the JSON data string to a PHP array
        $data = json_decode($dataString, true);
        $user_id = 1;
        $this->saveData($user_id=1,$data);
        // Return the JSON data as a JSON object
//        return $data;
    }


    private function saveData($user_id,$postContent)
    {
        $application = UserApplications::findOne([
            'user_id'=>$user_id,
            'application_id'=>$postContent['application_id']
        ]);
        if(!$application) {
            $application = new UserApplications();
            $application->application_id = $postContent['application_id'];
            $application->user_id = $user_id;
            $application->save();
        }

        $i= 1;
        foreach ($postContent['forms'] as $key => $form) {
            foreach ($form as $field => $field_value) {
               /* echo "<pre>";
                echo var_dump(print_r($keya));
                echo var_dump(print_r($f));*/
                $applicationContent = UserApplicationContent::findOne([
                    'user_application_id'=>$postContent['application_id'],
                    'user_application_wizard_id'=>$postContent['wizard_id'],
                    'user_application_form_field_key'=>$field,
                    'user_application_form_field_value'=>$field_value,

                ]);
                if(!$applicationContent) {
                    $applicationContent = new UserApplicationContent();
                    $applicationContent->user_application_id = $postContent['application_id'];
                    $applicationContent->user_application_wizard_id = $postContent['wizard_id'];
                }
                $applicationContent->user_application_form_id = $form['form_id'];
                $applicationContent->user_application_form_field_id = 111;
                $applicationContent->user_application_form_field_key = $field;
                $applicationContent->user_application_form_field_value =$field_value;
                if(!$applicationContent->save()) {
                    throw new \Exception(json_encode($applicationContent->errors));
                }
            }

        }

    }



}
