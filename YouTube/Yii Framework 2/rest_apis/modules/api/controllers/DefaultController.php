<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class DefaultController extends ActiveController
{
    public $modelClass = 'app\models\Noticia';

    // não permite deletar ou criar atraves de api
    // public function actions()
    // {
    //     $actions = parent::actions();
    //     unset($actions['delete'], $actions['create']);

    //     return $actions;
    // }
}
