<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirm_password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'confirm_password'], function ($attribute) {
                $this->$attribute = \yii\helpers\HtmlPurifier::process(
                    $this->$attribute,
                    [
                        'HTML.Allowed' => '',
                    ]
                );
            }],

                    
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'match', 'pattern' => '/^[0-9a-z]+$/i', 'message' => Yii::t('frontend', 'The password can contain only letters and numbers')],
            ['username', 'required', 'message' => Yii::t('frontend', 'Enter your user name')],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('frontend', 'This user name is already registered')],
            ['username', 'string', 'min' => 2, 'max' => 255, 'tooShort' => Yii::t('frontend', 'Username must be at least 2 characters'),
                'tooLong' => Yii::t('frontend', 'The user name must be no more than 255 characters')],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => Yii::t('frontend', 'Enter your e-mail')],
            ['email', 'email', 'message' => Yii::t('frontend', 'Please enter a valid e-mail address')],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('frontend', 'This e-mail address is already in use')],

            ['password', 'required', 'message' => Yii::t('frontend', 'Enter password')],

            ['password', 'string', 'length' => [6, 25], 'tooShort' => Yii::t('frontend', 'The password must be at least 6 characters'),
                'tooLong' => Yii::t('frontend', 'The password should be no more than 25 characters')],
            ['password', 'match', 'pattern' => '/^[0-9a-z]+$/i', 'message' => Yii::t('frontend', 'The password can contain only letters and numbers')],

            ['confirm_password', 'required', 'message' => Yii::t('frontend', 'Re-enter your password')],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('frontend', 'Passwords do not match')],

        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('frontend', 'Login'),
            'password' => Yii::t('frontend', 'Password'),
            'confirm_password' => Yii::t('frontend', 'Confirm password'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
        }
        }

        return null;
    }
}
