<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Category;
use yii\data\ActiveDataProvider;
use yii\web\Response;

class CategoryController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['index'],
                'formats' => [
                    'application/json' => Response::FORMAT_XML
                ]
            ]
        ]; 
    }

    public function actionIndex()
    {
        return Category::find()->all();
    }

    public function actionCreate()
    {
        $model = new Category(['scenario' => 'newpost']);
        if ($model->load(\Yii::$app->request->post(), '')) {
            $model->title = \Yii::$app->request->post('title');
            $model->description = \Yii::$app->request->post('description');
            $model->save();

            return $model;
        } else {
            return 'Metodo request invalido!';
        }
    }

    public function actionRead($id)
    {
        $model = Category::find()->where(['id' => $id])->one();
        if (empty($model)) {
            return 'ID Invalido!';
        }

        return $model;
    }

    public function actionUpdate($id)
    {
        $model = Category::findOne(['id'=>$id]);
        if ($model->load(\Yii::$app->request->post(), '')) {
            $model->scenario = 'newpost';
            $model->title = \Yii::$app->request->post('title');
            $model->description = \Yii::$app->request->post('description');
            $model->save();

            return $model;
        } else {
            return 'Metodo request invalido!';
        }
    }

    public function actionDelete($id)
    {
        return Category::findOne(['id'=>$id])->delete();
    }

    public function actionFaker()
    {
        $faker = \Faker\Factory::create();
        for($i = 1;$i<20;$i++) {
            $model = new Category(['scenario' => 'newpost']);
            $model->title = $faker->word;
            $model->description = $faker->text;
            $model->save();
        }
    }

    public function actionPaginate()
    {
        $datas = new ActiveDataProvider([
            'query' => Category::find(),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        return $datas->getModels();
    }
}
