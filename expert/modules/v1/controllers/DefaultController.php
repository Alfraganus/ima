<?php

namespace expert\modules\v1\controllers;

use expert\models\forms\ExpertFormDecision;
use expert\models\forms\ExpertFormEnquiry;
use expert\models\forms\ExpertFormFeedback;
use expert\models\forms\ExpertFormNotification;
use expert\models\forms\ExpertFormPayment;
use expert\modules\v1\services\DeleteFormService;
use expert\modules\v1\services\ReadFormService;
use expert\modules\v1\services\UpdateFormService;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use expert\modules\v1\services\CreateFormService;
use yii\web\UploadedFile;

/**
 * Default controller for the `v1` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
            ],
        ];
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::class,
            'actions' => [
                'save-form' => ['POST'],
                'get-form' => ['POST'],
                'get-application' => ['POST'],
                'send-message' => ['POST'],
                'send-user-message' => ['POST'],
            ],
        ];
        return $behaviors;
    }

    private $form;

    public function __construct($id, $module, $config = [])
    {
        $this->form = CreateFormService::getAllForms();
        parent::__construct($id, $module, $config);
    }

    public function actionSaveForm()
    {
//        return Yii::$app->request->post();
        return  (new CreateFormService)->createForm(
            Yii::$app->request->post(),
            $_FILES['form_info'],
            Yii::$app->user->id
        );
    }

    public function actionGetForm()
    {
        $post = Yii::$app->request->post();
        return  (new ReadFormService())->getFormData(
            $this->form[$post['form_id']],
            Yii::$app->request->post()
        );

    }

    public function actionUpdateApplicationForm()
    {
        try {
            return (new UpdateFormService())->updateExpertForm(
                Yii::$app->request->post(),
                $_FILES);
        } catch (\Exception $exception) {
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function actionDeleteApplicationForm()
    {
        try {
            return (new DeleteFormService())->deleteExpertForm(Yii::$app->request->post());
        } catch (\Exception $exception) {
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }

    }
}
