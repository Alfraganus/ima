<?php

namespace expert\modules\v1\controllers;

use yii\rest\Controller;

/**
 * Default controller for the `v1` module
 */
class ApplicationController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionSaveForm()
    {
        return \Yii::$app->request->post();
    }
}
