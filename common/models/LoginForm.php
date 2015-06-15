<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required', 'message' => Yii::t('common', 'It is necessary to fill the field')],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            
            ['username', 'roleValidator', 'params' => ['role' => User::ROLE_USER], on => 'default'],
            ['username', 'roleValidator', 'params' => ['role' => User::ROLE_ADMIN], on => 'adminLogin'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('common', 'Login'),
            'password' => Yii::t('common', 'Password'),
            'rememberMe' => Yii::t('common', 'Remember me'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('common', 'Incorrect username or password.'));
            }
        }
    }
    
    public function roleValidator($attribute, $params)
    {
        $user = User::findByUsername($this->username);
        
        if (!$user || $user->role != $params['role']) {
            $this->addError($attribute, Yii::t('common', 'This account does not exist'));
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
}
