<?php

namespace expert\modules\v1\controllers;

use yii\rest\Controller;
use expert\modules\v1\services\CreateFormService;
/**
 * Default controller for the `v1` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $form_id = 1;
        return (new CreateFormService)->createForm($form_id);
    }
}
