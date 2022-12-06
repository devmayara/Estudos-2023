<?php

namespace api\modules\v1\controllers;

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
}