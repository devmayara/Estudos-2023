<?php

namespace app\controllers;

class HelloController extends \yii\web\Controller
{
    public function actionSaySomething($message='hello')
    {
        return $this->render('say-something', [
            'message' => $message
        ]);
    }
}
