<?php

namespace frontend\modules\api\controllers;


use http\Client;
use http\Client\Request;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use common\models\Application;
use common\models\ApplicationWizard;
use expert\models\application\ExpertModules;
use expert\models\application\ExpertTabs;
use expert\models\forms\ExpertFormList;
use frontend\modules\api\service\FormReadService;
use frontend\modules\api\service\FormSaveService;
use frontend\modules\api\service\UpdateFormService;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        unset($behaviors['authenticator']);
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
            ],
        ];

        return $behaviors;
    }


    public function actionSaveApplication()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $post = $request->post();
//        return json_decode(json_decode($post['forms']['attachments']));
        return (new FormSaveService())->saveData(
            Yii::$app->user->id,
            $post,
            $_FILES
        );
    }
    public function actionGetCountries()
    {
        return (new \yii\db\Query())
            ->select('*')
            ->from('world_countries')
            ->orderBy('country_name')
            ->all();
    }

    public function actionSaveApplicationAttachment()
    {
        $request = Yii::$app->request;
        return (new FormSaveService())->saveFiles(
            $_FILES,
            null,
            Yii::$app->user->id,
            null,
        );
    }

    public function actionUpdateApplication()
    {
        $post = Yii::$app->request->post();
        return (new UpdateFormService())->updateWizard(Yii::$app->user->id, $post, $_FILES);
    }


    public function actionGetApplicationData($application_id, $wizard_id)
    {
        return (new FormReadService())->getWizardContent($application_id, $wizard_id, Yii::$app->user->id);
    }

    public function actionGetApplicationSummary($application_id)
    {
        return (new FormReadService())->getApplicationContent(
            $application_id,
        );
    }

    public function actionApplicationComponents($application_id)
    {
        $application = Application::findOne(htmlspecialchars($application_id));
        return [
            'application' => $application,
            'wizards' => ApplicationWizard::findAll(['application_id' => $application->id])
        ];
    }

    public function actionGetLocations()
    {
        return (new FormReadService())->getRegions();
    }

    public function actionExpertApplicationComponents($application_id)
    {
        $application = Application::findOne(htmlspecialchars($application_id));
        //

        return $applicationIndustry = [
            'application_type_id' => $application->id,
            'application_name' => $application->name,
            'module' => [
                'name' => '000',
                'module_id' => $this->getExpertModuleInfo('000'),
                'tabs' => [
                    'Формальная экспертиза' => [
                        'tab_id' => $this->getTabInfo('Формальная экспертиза'),
                        'tabs' => [
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormDecision')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormDecision')['form_name'],
                            ],
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormPayment')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormPayment')['form_name'],
                            ],
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormNotification')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormNotification')['form_name'],
                            ],
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormEnquiry')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormEnquiry')['form_name'],
                            ],
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormFeedback')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormFeedback')['form_name'],
                            ],
                        ],
                    ],
                    'Экспертиза' => [
                        'tab_id' => $this->getTabInfo('Экспертиза'),
                        'tabs' => [
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormDecision')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormDecision')['form_name'],
                            ],
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormPayment')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormPayment')['form_name'],
                            ],
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormNotification')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormNotification')['form_name'],
                            ],
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormEnquiry')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormEnquiry')['form_name'],
                            ],
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormFeedback')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormFeedback')['form_name'],
                            ],
                        ],
                    ],
                    'Регистрация' => [
                        'tab_id' => $this->getTabInfo('Регистрация'),
                        'tabs' => [
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormPayment')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormPayment')['form_name'],
                            ],
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormNotification')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormNotification')['form_name'],
                            ],
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormEnquiry')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormEnquiry')['form_name'],
                            ],

                        ],
                    ],
                    'Продления' => [
                        'tab_id' => $this->getTabInfo('Продления'),
                        'tabs' => [
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormPayment')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormPayment')['form_name'],
                            ],
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormNotification')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormNotification')['form_name'],
                            ],
                            [
                                'form_id' => $this->getFormInfo('expert\models\forms\ExpertFormEnquiry')['id'],
                                'form_name' => $this->getFormInfo('expert\models\forms\ExpertFormEnquiry')['form_name'],
                            ],

                        ],
                    ]
                ]
            ]
        ];
    }

    private function getFormInfo($form_class)
    {
        $form = ExpertFormList::findOne(['form_class' => $form_class]);
        return $form;
    }

    private function getTabInfo($tab_name)
    {
        $tab = ExpertTabs::findOne(['tab_name' => $tab_name]);
        return $tab->id;
    }

    private function getExpertModuleInfo($module_name)
    {
        $module = ExpertModules::findOne(['module_name' => $module_name]);
        return $module->id;
    }

}