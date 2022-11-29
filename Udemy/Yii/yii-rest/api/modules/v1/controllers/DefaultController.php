<?php

namespace api\modules\v1\controllers;

use yii\web\Controller;

/**
 * Default controller for the `Module` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($name,$age)
    {
        echo $name.' '.$age;
    }

    public function actionForm()
    {
        // print_r(\Yii::$app->request->bodyParams);
        echo \Yii::$app->request->post('firstname')."\n";
        echo \Yii::$app->request->post('lestname')."\n";
        echo \Yii::$app->request->post('email')."\n";
        echo \Yii::$app->request->post('username');
    }
}
