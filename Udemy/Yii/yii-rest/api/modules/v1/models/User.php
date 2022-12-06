<?php

namespace api\modules\v1\models;

use yii\db\ActiveRecord;


class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    const ACTIVE_USER = 1;
    const INATIVE_USER = 0;

    public function rules()
    {
        return [
            ['status','default','value' => self::ACTIVE_USER],
            ['status','in','range' => [self::ACTIVE_USER, self::INATIVE_USER]]
        ];
    }
    
// afri_
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        // return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
        return static::findOne(['id'=>$id, 'status'=>self::ACTIVE_USER]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // foreach (self::$users as $user) {
        //     if ($user['accessToken'] === $token) {
        //         return new static($user);
        //     }
        // }

        // return null;
        return static::findOne(['access_token'=>$token]);
    }

    /**
     * Finds user by usename
     *
     * @parem string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        // return static::findOne(['username'=>$username,'status'=>self::ACTIVE_USER]);

        return static::find()->where(['username'=>$username])
            ->orWhere(['email', $username])
            ->andWhere(['status'=>self::ACTIVE_USER])
            ->one();
    }

    /**
     * Encrypt password
     *
     * @param $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Gerenate random string for authentiction
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     */
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
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password, $userPassword)
    {
        return \Yii::$app->security->validatePassword($password, $userPassword);
    }
}
