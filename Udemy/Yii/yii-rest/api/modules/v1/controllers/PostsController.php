<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Posts;
use yii\rest\ActiveController;
use yii\web\Response;

class PostsController extends ActiveController
{
    public function behaviors()
    {
        // return [
        //     [
        //         'class' => 'yii\filters\ContentNegotiator',
        //         'only' => ['index'],
        //         'formats' => [
        //             'application/json' => Response::FORMAT_XML
        //         ]
        //     ],
        //     'verbs' => [
        //         'class' => \yii\filters\VerbFilter::className(),
        //         'actions' => [
        //             'index' => ['GET'],
        //             'view' => ['POST']
        //         ]
        //     ]
        // ]; 

        $behaviors = parent::behaviors();
        $behaviors['corsfilter'] = [
            'class' => \yii\filters\Cors::className()
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['view'],$actions['delete']);
    }

    public function actionIndex()
    {
        return Posts::find()->select('title,body')->all();
    }

    public function actionView($id)
    {
        return Posts::find()->where(['id'=>$id])->select('title,body')->one();
    }

    public $modelClass = 'api\modules\v1\models\Posts';
}