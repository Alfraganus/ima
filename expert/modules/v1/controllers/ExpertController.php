<?php

namespace expert\modules\v1\controllers;

use common\models\ApplicationForm;
use common\models\forms\FormRequester;
use expert\models\ExpertUser;
use expert\modules\v1\services\AdvancedSearch;
use expert\modules\v1\services\FrontApplicationService;
use expert\modules\v1\services\ReadFormService;
use expert\modules\v1\services\UserRoleService;
use frontend\models\ImaUsers;
use frontend\modules\api\service\FormReadService;
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
        return (new AdvancedSearch())->search2($post['column'], $post['conditions']);
    }

    public function actionGetLocations()
    {
        return (new FormReadService())->getRegions();
    }

    public function actionGetCountries()
    {
        return (new \yii\db\Query())
            ->select('*')
            ->from('world_countries')
            ->orderBy('country_name')
            ->all();
    }

    public function actionCheckedPatent($user_application_id)
    {
        return $this->getRequesterByRoleId($user_application_id, FormRequester::ROLE_ID_TRUSTED);
    }

    public function actionTrustedRepsesentative($user_application_id)
    {
        return $this->getRequesterByRoleId($user_application_id, FormRequester::ROLE_ID_REPRESENTATIVE);
    }


    public function getRequesterByRoleId($user_application_id, $roleId)
    {
        $formRequester = FormRequester::findOne(['user_application_id' => $user_application_id]);
        if ($formRequester->role_id == $roleId) {
            return [
                'success' => true,
                'data' => ImaUsers::findOne($formRequester->user_id)
            ];
        }
        return [];
    }

    public function actionGetForm55($user_application_id)
    {
        return (new ReadFormService())->getForm55($user_application_id);
    }

    public function actionGetFrontForm($user_application_id, $form_id)
    {
        return $this->frontApplicationService->getFrontForm($user_application_id, $form_id);
    }

    public function actionGetSingleFrontForm($user_application_id, $form_id, $data_id)
    {
        return $this->frontApplicationService->getSingleFrontForm($user_application_id, $form_id, $data_id);
    }

    public function actionCustomFormFields()
    {
        $post = Yii::$app->request->post();
        return $this->frontApplicationService->getCustomFromClass(
            $post['form_id'],
            $post['user_application_id'],
            $post['columns'],
            $post['isSingle'] ?? true,
            $post['data_id'] ?? true,
        );
    }

    public function actionDeleteFrontForm($form_type_id, $data_id)
    {
        return $this->frontApplicationService->deleteFrontForm($form_type_id, $data_id);
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
