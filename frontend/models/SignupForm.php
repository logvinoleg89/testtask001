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
            ['username', 'match', 'pattern' => '/^[0-9a-z]+$/i', 'message' => 'Имя пользователя может содержать только буквы латинского алфавита и цифры'],
            ['username', 'required', 'message' => 'Введите имя пользователя'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким именем уже зарегистрирован'],
            ['username', 'string', 'min' => 2, 'max' => 255, 'tooShort' => 'Имя пользователя должно быть не менее 2 символов',
                'tooLong' => 'Имя пользователя должно быть не более 255 символов'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => 'Введите e-mail'],
            ['email', 'email', 'message' => 'Введите корректный e-mail адрес'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Данный адрес электронной почты уже используется'],

            ['password', 'required', 'message' => 'Введите пароль'],

            ['password', 'string', 'length' => [6, 25], 'tooShort' => 'Пароль должен быть не менее 6 символов',
                'tooLong' => 'Пароль должен быть не более 25 символов'],
            ['password', 'match', 'pattern' => '/^[0-9a-z]+$/i', 'message' => 'Пароль может содержать только буквы латинского алфавита и цифры'],

            ['confirm_password', 'required', 'message' => 'Повторно введите Ваш пароль'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'confirm_password' => 'Подтверждение пароля'
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
