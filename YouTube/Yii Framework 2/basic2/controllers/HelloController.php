<?php

namespace app\controllers;

class HelloController extends \yii\web\Controller
{
    public function actionSaySomething()
    {
        return $this->render('say-something');
    }

}
