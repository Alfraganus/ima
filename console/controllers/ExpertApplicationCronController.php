<?php

namespace console\controllers;

use common\models\UserApplications;
use common\traits\PaymentFunctions;
use expert\models\ApplicationStatus;
use expert\modules\v1\services\ApplicationStatusService;
use Yii;

class ExpertApplicationCronController extends \yii\console\Controller
{
    use PaymentFunctions;

    private $userApplicaitons;
    private $applicaitonStatusService;
    private $applicationStatusManager;

    public function __construct($id, $module, $config = [])
    {
        $this->userApplicaitons = UserApplications::find();
        $this->applicaitonStatusService = ApplicationStatus::find();
        $this->applicationStatusManager = Yii::createObject(ApplicationStatusService::class);
        parent::__construct($id, $module, $config);
    }

    /*har kuni shu funksiya run bolishi kerak*/
    public function actionCheckPaymentsForExpert()
    {
        $applications = $this->userApplicaitons->where([
            'status_id' => $this->applicationStatusManager->getApplicationStatus('%На стадии уплаты пошлины за экспертизу')
        ])
            ->joinWith('decision')
            ->asArray()
            ->all();

        foreach ($applications as $application) {
            $expertDecisionExtraInfo = json_decode($application['decision']['extra_info'], true);
            if (!empty($expertDecisionExtraInfo)) {
                $paymentStatusChecker = $this->checkInvoiceStatus(
                    $expertDecisionExtraInfo['invoice_serial'], $application->id
                );
                if ($paymentStatusChecker['success']) {
                    $this->applicationStatusManager->setApplicationStatusExpertPending($application['id']);
                }
            }
        }
    }

}