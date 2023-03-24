<?php

namespace expert\modules\v1\controllers;

use expert\models\ExpertUser;
use expert\modules\v1\services\UserRoleService;
use Yii;
use yii\rest\Controller;

/**
 * Default controller for the `v1` module
 */
class ExpertController extends Controller
{

    protected function verbs()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'register-user'  => ['POST'],
                ],
            ],
        ];
    }

    public function actionRegisterUser()
    {
        $post =  Yii::$app->request->post();

       $user = (new UserRoleService())->createUser($post);

        return [
          'data'=>$user
        ];
        //  $auth->assign($author, 2);
    }


    public function actionTest()
    {
     return  (new UserRoleService())->createRole();
       /* $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        // add "updatePost" permission
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);

        // add "author" role and give this role the "createPost" permission
        $author = $auth->createRole('author');
        $auth->add($author);
        $auth->addChild($author, $createPost);*/

    }
}
