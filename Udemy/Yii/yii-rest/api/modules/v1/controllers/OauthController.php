<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\LoginForm;
use api\modules\v1\models\User;
use api\modules\v1\models\UserSignup;
use yii\rest\Controller;

class OauthController extends Controller
{
    public function actionSignup()
    {
        $model = new UserSignup();

        if($model->load(\Yii::$app->getRequest()->getBodyParams(), '')) {
            // return \Yii::$app->getRequest()->getBodyParams();
            return $model->signup();
        } else {
            return 'Not submitted';
        }
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            $user = User::findOne(['username'=>$model->username]);
            $user->access_token = \Yii::$app->security->generateRandomString();
            $user->save();

            return ['token'=>$user->access_token];
        }
    }
}