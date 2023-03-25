<?php

namespace expert\modules\v1\controllers;

use Yii;
use expert\modules\v1\services\UserRoleService;
use yii\rest\Controller;


class AuthController extends Controller
{
    const ACTIVE = 10;
    const IN_ACTIVE = 0;

    private $userRoleService;

    public function __construct($id, $module, $config = [])
    {
        $this->userRoleService = new UserRoleService();
        $this->userRoleService->runRbacMigrations();
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'login-by-credentials'  => ['POST'],
                ],
            ],
        ];
    }

    public function actionTest()
    {
        return 'tes';
    }
    


}
