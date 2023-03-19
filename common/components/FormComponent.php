<?php

namespace common\components;

use expert\models\ExpertFormMedia;

class FormComponent
{
    public static function getExpertFiles($user_application_id,$user_id,$module_id,$form_id,$object_id)
    {
        return ExpertFormMedia::find()->where([
            'user_application_id'=>$user_application_id,
            'user_id' => $user_id,
            'module_id' => $module_id,
            'form_id' => $form_id,
            'object_id' => $object_id,
        ])->select(['id', 'file_name', 'file_path'])->all();
    }
}
