<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'new_password'], function ($attribute) {
                $this->$attribute = \yii\helpers\HtmlPurifier::process(
                    $this->$attribute,
                    [
                        'HTML.Allowed' => '',
                    ]
                );
            }],


            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],


            [ 'new_password', 'safe'],

            ['new_password', 'string', 'length' => [6, 25], 'tooShort' => 'Пароль должен быть не менее 6 символов' ,
                'tooLong' => 'Пароль должен быть не более 25 символов'],
            ['new_password', 'match', 'pattern' => '/^[0-9a-z]+$/i', 'message' => 'Пароль может содержать только буквы латинского алфавита и цифры'],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'match', 'pattern' => '/^[0-9a-z]+$/i', 'message' => 'Имя пользователя может содержать только буквы латинского алфавита и цифры'],
            ['username', 'required', 'message' => 'Введите имя пользователя'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким именем уже зарегистрирован'],
            ['username', 'string', 'min' => 2, 'max' => 255, 'tooShort' => 'Имя пользователя должно быть не менее 2 символов' ,
                'tooLong' => 'Имя пользователя должно быть не более 255 символов'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => 'Введите e-mail'],
            ['email', 'email', 'message' => 'Введите корректный e-mail адрес'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Данный адрес электронной почты уже используется'],


        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public $new_password = "";

    public function beforeSave($insert) {
        if ($this->new_password) {
            $this->setPassword($this->new_password);
        }
        return parent::beforeSave($insert);
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'new_password' => 'Новый пароль',
            'email' => 'E-mail',
            'status' => 'Статус',
            'role' => 'Роль'
        ];
    }

}
