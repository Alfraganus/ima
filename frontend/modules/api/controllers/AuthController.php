<?php

namespace frontend\modules\api\controllers;


use frontend\models\ImaUsers;
use Yii;
use yii\rest\Controller;


class AuthController extends Controller
{
    const ACTIVE = 10;
    const IN_ACTIVE = 0;

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

    public function actionLoginRedirect()
    {
        return Yii::$app->controller->redirect(Yii::$app->oneId->getLoginLink());
    }

    public function actionLoginByCredentials()
    {
        $post = Yii::$app->request->post();
        $getUser = ImaUsers::findOne(['username'=>$post['user_id']]);
        if($getUser) {
            return [
                'user'=>$getUser->username,
                'token'=>$getUser->auth_key
            ];
        }else {
            $model = new ImaUsers();
            $model->setAttributes($post);
            $model->username = $post['user_id'];
            $model->pport_issue_date = $post['_pport_issue_date'];
            $model->pport_expr_date = $post['_pport_expr_date'];
            $model->is_active = self::ACTIVE;
            $model->setAuthKey();
            if(!$model->save()) {
                throw new \Exception(json_encode($model->errors));
            }
            return [
                'user'=>'The user has been successfully saved',
                'token'=>$model->auth_key
            ];
        }
    }


    public function actionLogin()
    {
        if (!empty(Yii::$app->request->get())) {
            $one_id_response = Yii::$app->oneId->getUserData();
            if($one_id_response) {
                $getUser = ImaUsers::findOne(['username'=>$one_id_response['user_id']]);
                if($getUser) {
                    return [
                      'user'=>$getUser->username,
                      'token'=>$getUser->auth_key
                    ];
                } else {
                    $model = new ImaUsers();
                    $model->setAttributes($one_id_response);
                    $model->username = $one_id_response['user_id'];
                    $model->pport_issue_date = $one_id_response['_pport_issue_date'];
                    $model->pport_expr_date = $one_id_response['_pport_expr_date'];
                    $model->is_active = self::ACTIVE;
                    $model->setAuthKey();
                    if(!$model->save()) {
                        throw new \Exception(json_encode($model->errors));
                    }
                    return [
                        'user'=>'The user has been successfully saved',
                        'token'=>$model->auth_key
                    ];
                }
            }
        }
    }

}
