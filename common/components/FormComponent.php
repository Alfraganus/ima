<?php

namespace common\components;

use expert\models\ExpertFormMedia;

class FormComponent
{
    public static function getExpertFiles($user_id,$module_id,$tab_id,$object_id)
    {
        return ExpertFormMedia::find()->where([
            'user_id' => $user_id,
            'module_id' => $module_id,
            'form_id' => $tab_id,
            'object_id' => $object_id,
        ])->select(['id', 'file_name', 'file_path'])->all();
    }
}
