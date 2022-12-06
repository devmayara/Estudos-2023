<?php

namespace api\modules\v1\models;

use yii\base\Model;

class UserSignup extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_confirm;

    public function rules()
    {
        return [
            ['username', 'trim'],            
            ['username', 'required'],
            ['username', 'string', 'min'=>4, 'max'=>255],
            ['username', 'unique', 'targetClass'=>'api\modules\v1\models\User', 'message'=>'Ops! username token'],
            
            ['email', 'trim'],            
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max'=>200],
            ['email', 'unique', 'targetClass'=>'api\modules\v1\models\User', 'message'=>'Ops! email token'],
            
            ['password', 'string', 'min'=>6],
            ['password', 'required'],

            ['password_confirm', 'required'],
            ['password_confirm', 'compare', 'compareAttribute'=>'password', 'message'=>'Ops! Both password not the same']
        ];
    }

    public function signup()
    {
        if(!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->access_token = \Yii::$app->security->generateRandomString(32);
        $user->unique_key = time().mt_rand(10,90);
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return ($user->save()) ? $user:null;
    }
}
