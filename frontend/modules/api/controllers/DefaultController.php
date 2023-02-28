<?php

namespace frontend\modules\api\controllers;


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

        // Return the JSON data as a JSON object
        return $data;
    }


    private function saveData()
    {

    }

}
