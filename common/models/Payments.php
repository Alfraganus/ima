<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payments".
 *
 * @property int $id
 * @property int|null $user_application_id
 * @property string|null $invoice_request_id
 * @property string|null $invoice_serial
 * @property int|null $invoice_amount
 * @property string|null $invoice_status
 * @property string|null $invoice_note
 * @property string|null $invoice_created_at
 * @property string|null $invoice_updated_at
 * @property string|null $invoice_expire_date
 * @property string|null $invoice_json
 * @property string|null $billing_request_id
 * @property string|null $billing_invoice_serial
 * @property int|null $billing_amount
 * @property string|null $billing_status
 * @property string|null $billing_note
 * @property string|null $billing_created_at
 * @property string|null $billing_updated_at
 * @property string|null $billing_json
 * @property string|null $billing_ip
 * @property int|null $payment_status
 * @property string|null $document
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property UserApplications $userApplication
 */
class Payments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_application_id', 'invoice_amount', 'billing_amount', 'payment_status', 'created_at', 'updated_at'], 'integer'],
            [['invoice_json', 'billing_json', 'document'], 'string'],
            [['invoice_request_id', 'invoice_serial', 'invoice_status', 'invoice_created_at', 'invoice_updated_at', 'invoice_expire_date', 'billing_request_id', 'billing_invoice_serial', 'billing_status', 'billing_created_at', 'billing_updated_at', 'billing_ip'], 'string', 'max' => 32],
            [['invoice_note', 'billing_note'], 'string', 'max' => 255],
            [['user_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserApplications::class, 'targetAttribute' => ['user_application_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_application_id' => Yii::t('app', 'User Application ID'),
            'invoice_request_id' => Yii::t('app', 'Invoice Request ID'),
            'invoice_serial' => Yii::t('app', 'Invoice Serial'),
            'invoice_amount' => Yii::t('app', 'Invoice Amount'),
            'invoice_status' => Yii::t('app', 'Invoice Status'),
            'invoice_note' => Yii::t('app', 'Invoice Note'),
            'invoice_created_at' => Yii::t('app', 'Invoice Created At'),
            'invoice_updated_at' => Yii::t('app', 'Invoice Updated At'),
            'invoice_expire_date' => Yii::t('app', 'Invoice Expire Date'),
            'invoice_json' => Yii::t('app', 'Invoice Json'),
            'billing_request_id' => Yii::t('app', 'Billing Request ID'),
            'billing_invoice_serial' => Yii::t('app', 'Billing Invoice Serial'),
            'billing_amount' => Yii::t('app', 'Billing Amount'),
            'billing_status' => Yii::t('app', 'Billing Status'),
            'billing_note' => Yii::t('app', 'Billing Note'),
            'billing_created_at' => Yii::t('app', 'Billing Created At'),
            'billing_updated_at' => Yii::t('app', 'Billing Updated At'),
            'billing_json' => Yii::t('app', 'Billing Json'),
            'billing_ip' => Yii::t('app', 'Billing Ip'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'document' => Yii::t('app', 'Document'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[UserApplication]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserApplication()
    {
        return $this->hasOne(UserApplications::class, ['id' => 'user_application_id']);
    }
}
