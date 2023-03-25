<?php

namespace expert\modules\v1\services;

use expert\models\ExpertFormMedia;
use expert\models\ExpertUser;
use Yii;
use yii\console\controllers\MigrateController;
use yii\console\Request;

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
        if (!$expertUser->save()) {
            throw new \Exception(json_encode($expertUser->errors));
        }
        $this->assignRole($expertUser->id, 'expert');

        return $expertUser;
    }

    private function assignRole($user_id, $role)
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

    public function runRbacMigrations()
    {
        if (Yii::$app->db->schema->getTableSchema('auth_assignment') === null) {
            $migrateController = new MigrateController('migrate', Yii::$app);

            $request = new Request();
            $request->setParams(['migrationPath' => '@yii/rbac/migrations']);

          return  $runMigration = function () use ($migrateController, $request) {
                $tempStream = fopen('php://temp', 'w+');
                $oldStream = defined('STDOUT') ? STDOUT : null;
                define('STDOUT', $tempStream);
                $migrateController->runAction('up', [], $request);
                fseek($tempStream, 0);
                $output = stream_get_contents($tempStream);
                fclose($tempStream);
                if ($oldStream === null) {
                    unset($STDOUT);
                } else {
                    define('STDOUT', $oldStream);
                }
                return $output;
            };


        }
    }
}