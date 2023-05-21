<?php

namespace expert\modules\v1\controllers;

use common\models\ApplicationForm;
use common\models\forms\FormRequester;
use expert\models\ExpertUser;
use expert\modules\v1\services\AdvancedSearch;
use expert\modules\v1\services\FrontApplicationService;
use expert\modules\v1\services\UserRoleService;
use frontend\models\ImaUsers;
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
    public function actionAdvancedSearch()
    {
        $post = Yii::$app->request->post();
        return (new AdvancedSearch())->search2($post['column'],$post['conditions']);
    }



    public function actionGetTrustedPerson($user_application_id)
    {
        $formRequester = FormRequester::findOne(['user_application_id' => $user_application_id]);
        if ($formRequester->role_id == FormRequester::ROLE_ID_TRUSTED) {
            return [
                'success' => true,
                'data' => ImaUsers::findOne($formRequester->user_id)
            ];
        }
        return [];
    }

    public function actionGetFrontForm($user_application_id, $form_id)
    {
        return $this->frontApplicationService->getFrontForm($user_application_id, $form_id);
    }

    public function actionUpdateFrontFormAll()
    {
        $post = Yii::$app->request->post();
        return $this->frontApplicationService->updateFormAll(
            $post['form_id'],
            $post
        );
    }

    public function actionUpdateFrontFormSingle()
    {
        $post = Yii::$app->request->post();
        return $this->frontApplicationService->updateFormSingle(
            $post
        );
    }

    public function actionCreateFrontForm()
    {
        $post = Yii::$app->request->post();
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
