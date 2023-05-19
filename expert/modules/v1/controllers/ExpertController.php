<?php

namespace expert\modules\v1\controllers;

use common\models\ApplicationForm;
use expert\models\ExpertUser;
use expert\modules\v1\services\FrontApplicationService;
use expert\modules\v1\services\UserRoleService;
use Yii;
use yii\rest\Controller;

/**
 * Default controller for the `v1` module
 */
class ExpertController extends Controller
{
    private $frontApplicationService;

    public function __construct($id, $module, $config = [])
    {
        $this->frontApplicationService = Yii::createObject(FrontApplicationService::class);
        parent::__construct($id, $module, $config);
    }

    protected function verbs()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'register-user' => ['POST'],
                ],
            ],
        ];
    }

    public function actionGetFrontForm($user_application_id, $form_id)
    {
        return $this->frontApplicationService->getFrontForm($user_application_id, $form_id);
    }

    public function actionUpdateFrontForm()
    {
        $post =  Yii::$app->request->post();
        return $this->frontApplicationService->updateForm(
            $post['user_application_id'],
            $post['form_id'],
            $post
        );
    }

    public function actionCreateFrontForm()
    {
        $post =  Yii::$app->request->post();
        return $this->frontApplicationService->createForm(
            $post
        );

    }


    public function actionAssignPermissions()
    {
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        // add "updatePost" permission
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);

        // add "author" role and give this role the "createPost" permission
        $author = $auth->createRole('author');
        $auth->add($author);
        $auth->addChild($author, $createPost);

    }
}
