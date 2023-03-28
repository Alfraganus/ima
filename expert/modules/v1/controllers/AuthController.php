<?php

namespace expert\modules\v1\controllers;

use common\models\LoginForm;
use expert\models\ExpertUser;
use Yii;
use expert\modules\v1\services\UserRoleService;
use yii\filters\AccessControl;
use yii\rest\Controller;


class AuthController extends Controller
{
    const ACTIVE = 10;
    const IN_ACTIVE = 0;

    private $userRoleService;

    public function __construct($id, $module, $config = [])
    {
        $this->userRoleService = new UserRoleService();
        $this->userRoleService->createExpertRole();
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
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

    public function actionRegisterUser()
    {
        $post = Yii::$app->request->post();

        $user = $this->userRoleService->createUser($post);
        return [
            'message' => 'User has been registered successfully!',
            'data' => $user
        ];
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        $data = Yii::$app->getRequest()->getBodyParams();

        if ($model->load($data, '') && $model->login()) {
            $user = ExpertUser::findOne(['username'=>$model->username]);

            // return user information and JWT token
            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'token' => $user->auth_key,
            ];
        } else {
            Yii::$app->response->statusCode = 401; // unauthorized status code
            return ['error' => 'Invalid login credentials'];
        }
    }



}
