<?php

namespace expert\modules\v1\services;

use expert\models\ExpertFormMedia;
use expert\models\ExpertUser;
use Yii;

class UserRoleService
{

    public function createUser($post)
    {
        $expertUser = new ExpertUser();
        $expertUser->username = $post['username'];
        $expertUser->password = Yii::$app->security->generatePasswordHash($post['password']);
        $expertUser->generateAuthKey();
        $expertUser->email = $post['email'];
        $expertUser->status = ExpertUser::STATUS_ACTIVE;
        if(!$expertUser->save()) {
            throw new \Exception(json_encode($expertUser->errors));
        }
        $this->assignRole($expertUser->id,'expert');

        return $expertUser;
    }
    private function assignRole($user_id,$role)
    {
        Yii::$app->authManager->assign(Yii::$app->authManager->getRole($role), $user_id);
    }

    public function createRole()
    {
        $auth = Yii::$app->authManager;
        if (!$auth->getRole('expert')) {
            $createPost = $auth->createPermission('createForm');
            $createPost->description = 'Create a form';
            $auth->add($createPost);

            $updatePost = $auth->createPermission('updateForm');
            $updatePost->description = 'Update form';
            $auth->add($updatePost);

            $expert = $auth->createRole('expert');
            $auth->add($expert);
            $auth->addChild($expert, $createPost);
        } else {
            throw new \Exception('Role already exists');
        }


    }
}