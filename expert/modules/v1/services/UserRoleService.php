<?php

namespace expert\modules\v1\services;

use expert\models\ExpertFormMedia;
use expert\models\ExpertUser;
use Yii;
use yii\console\controllers\MigrateController;
use yii\console\Request;

class UserRoleService
{
    public function getRole($role_id)
    {
        $roles = [
          1=>'expert'
        ];

        if(!in_array($role_id,array_keys($roles))) {
            throw new \Exception('Wrong user role provided!');
        }
        return $role_id ? $roles[$role_id] : $roles;
    }

    public function createUser($post)
    {
        $expertUser = new ExpertUser();
        $expertUser->username = $post['username'];
        $expertUser->password = Yii::$app->security->generatePasswordHash($post['password']);
        $expertUser->generateAuthKey();
        $expertUser->email = $post['email'];
        $expertUser->status = ExpertUser::STATUS_ACTIVE;
        if (!$expertUser->save()) {
            throw new \Exception(json_encode($expertUser->errors));
        }
        $this->assignRole($expertUser->id, $this->getRole($post['role_id']));

        return $expertUser;
    }

    private function assignRole($user_id, $role)
    {
        Yii::$app->authManager->assign(Yii::$app->authManager->getRole($role), $user_id);
    }

    public function createExpertRole()
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
        }
    }

}