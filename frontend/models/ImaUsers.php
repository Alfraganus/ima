<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "ima_users".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $email
 * @property string|null $full_name
 * @property string|null $birth_date
 * @property string|null $mob_phone_no
 * @property string|null $pin
 * @property string|null $pport_issue_date
 * @property string|null $pport_expr_date
 * @property string|null $pport_issue_place
 * @property string|null $pport_no
 * @property string|null $ctzn
 * @property string|null $birth_place
 * @property string|null $per_adr
 * @property int|null $is_active
 * @property string|null $registered_time
 * @property string|null $auth_key
 * @property string|null $verification_token
 */
class ImaUsers extends \yii\db\ActiveRecord  implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     *
     */
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['birth_date', 'pport_issue_date', 'pport_expr_date', 'registered_time'], 'safe'],
            [['is_active'], 'integer'],
            [['username'], 'string', 'max' => 200],
            [['email', 'mob_phone_no', 'pin', 'pport_issue_place', 'pport_no', 'ctzn'], 'string', 'max' => 150],
            [['full_name', 'birth_place', 'per_adr', 'auth_key', 'verification_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'full_name' => Yii::t('app', 'Full Name'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'mob_phone_no' => Yii::t('app', 'Mob Phone No'),
            'pin' => Yii::t('app', 'Pin'),
            'pport_issue_date' => Yii::t('app', 'Pport Issue Date'),
            'pport_expr_date' => Yii::t('app', 'Pport Expr Date'),
            'pport_issue_place' => Yii::t('app', 'Pport Issue Place'),
            'pport_no' => Yii::t('app', 'Pport No'),
            'ctzn' => Yii::t('app', 'Ctzn'),
            'birth_place' => Yii::t('app', 'Birth Place'),
            'per_adr' => Yii::t('app', 'Per Adr'),
            'is_active' => Yii::t('app', 'Is Active'),
            'registered_time' => Yii::t('app', 'Registered Time'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'verification_token' => Yii::t('app', 'Verification Token'),
        ];
    }

    public function setAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString(16);
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'is_active' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'is_active' => self::STATUS_ACTIVE]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * {@inheritdoc}
     * @return \frontend\models\query\ImaUsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \frontend\models\query\ImaUsersQuery(get_called_class());
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        // TODO: Implement toArray() method.
    }

    public static function instance($refresh = false)
    {
        // TODO: Implement instance() method.
    }
}
