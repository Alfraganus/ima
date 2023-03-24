<?php

namespace expert\modules\v1\services;

use common\models\UserApplications;
use expert\models\ApplicationChat;
use expert\models\forms\ExpertFormDecision;
use expert\models\forms\ExpertFormList;
use expert\models\forms\ExpertFormPayment;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class ApplicationModuleDopdownService extends Model
{

    public  function module000()
    {
        return [
            [
                'expert_desicion' => $this->typeDecision(),
                'expert_payment' => $this->paymentDropdown(),
            ],
        ];
    }


    private function typeDecision()
    {
        return [
            'type_decision'=> [
                'tab1'=> ExpertFormDecision::decisionTypeTabOne(),
                'tab2'=> ExpertFormDecision::decisionTypeTabTwo(),
            ]
        ];
    }

    private function paymentDropdown()
    {
        return [
            'payment_purpose_id'=> [
                'tab1'=> ExpertFormPayment::paymentPurposeListTabOne(),
            ],
            'currencyList'=>ExpertFormPayment::currencyList()
        ];
    }
}
