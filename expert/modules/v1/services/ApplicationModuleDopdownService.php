<?php

namespace expert\modules\v1\services;

use common\models\UserApplications;
use expert\models\ApplicationChat;
use expert\models\forms\ExpertFormDecision;
use expert\models\forms\ExpertFormEnquiry;
use expert\models\forms\ExpertFormFeedback;
use expert\models\forms\ExpertFormList;
use expert\models\forms\ExpertFormNotification;
use expert\models\forms\ExpertFormPayment;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class ApplicationModuleDopdownService extends Model
{

    public function module000()
    {
        return [
            'expert_desicion' => $this->typeDecision(),
            'expert_payment' => $this->paymentDropdown(),
            'expert_notification' => $this->notificationDropdown(),
            'expert_enquiry' => $this->enquiryDropdown(),
            'expert_feedback' => $this->feedbackDropdown(),
        ];
    }


    private function typeDecision()
    {
        return [
            'type_decision' => [
                'tab1' => ExpertFormDecision::decisionTypeTab(1),
                'tab2' => ExpertFormDecision::decisionTypeTab(2),
            ]
        ];
    }

    private function paymentDropdown()
    {
        return [
            'payment_purpose_id' => [
                'tab1' => (new ExpertFormPayment())->paymentPurposeListTab(1),
                'tab2' => (new ExpertFormPayment())->paymentPurposeListTab(2),
                'tab3' => (new ExpertFormPayment())->paymentPurposeListTab(3),
                'tab4' => (new ExpertFormPayment())->paymentPurposeListTab(4),
            ],
            'currencyList' => ExpertFormPayment::currencyList()
        ];
    }

    private function notificationDropdown()
    {
        return [
            'payment_purpose_id' => [
                'tab1' => ExpertFormNotification::notificationTabTypeList(1),
                'tab2' => ExpertFormNotification::notificationTabTypeList(2),
                'tab3' => ExpertFormNotification::notificationTabTypeList(3),
                'tab4' => ExpertFormNotification::notificationTabTypeList(4),
            ],
            'department' => [
                'tab1' => ExpertFormNotification::departmentList(1),
                'tab2' => ExpertFormNotification::departmentList(2),
                'tab3' => ExpertFormNotification::departmentList(3),
                'tab4' => ExpertFormNotification::departmentList(4),
            ]
        ];
    }

    private function enquiryDropdown()
    {
        return [
            'payment_purpose_id' => [
                'tab1' => ExpertFormEnquiry::enquiryListTab(1),
                'tab2' => ExpertFormEnquiry::enquiryListTab(2),
                'tab3' => ExpertFormEnquiry::enquiryListTab(3),
                'tab4' =>ExpertFormEnquiry::enquiryListTab(4),
            ],
            'department' => [
                'tab1' => ExpertFormNotification::departmentList(1),
                'tab2' => ExpertFormNotification::departmentList(2),
                'tab3' => ExpertFormNotification::departmentList(3),
                'tab4' => ExpertFormNotification::departmentList(4),
            ]
        ];
    }

    private function feedbackDropdown()
    {
        return [
            'department' => [
                'tab1' => ExpertFormNotification::departmentList(1),
                'tab2' => ExpertFormNotification::departmentList(2),
                'tab3' => ExpertFormNotification::departmentList(3),
                'tab4' => ExpertFormNotification::departmentList(4),
            ],
            'feedback_type' => [
                'tab1' => ExpertFormFeedback::feedbackTypeListTab(1),
                'tab2' => ExpertFormFeedback::feedbackTypeListTab(2),
            ]
        ];
    }
}
