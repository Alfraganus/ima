<?php

namespace expert\modules\v1\services;

use expert\models\forms\ExpertFormList;
use Yii;
use yii\helpers\ArrayHelper;

class CreateFormService
{
    private static function getAllForms()
    {
     return ArrayHelper::map(ExpertFormList::find()->asArray()->all(),'id','form_name');
    }

    public function createForm($form_id)
    {
//        $postContent = Yii::$app->request->post();
        $forms = self::getAllForms();
        return $forms[$form_id];
    }


}
