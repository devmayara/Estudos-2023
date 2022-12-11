<?php

namespace app\controllers;

use Yii;
use app\models\Cliente;
use yii\web\Controller;

class InsertController extends Controller
{
    public function actionIndex()
    {
        $clientes = [
            ['nome' => 'mayara'],
            ['nome' => 'test'],
            ['nome' => 'alguem']
        ];

        Yii::$app->db
            ->createCommand()
            ->batchInsert(Cliente::tableName(), ['nome'], $clientes)
            ->execute();

//        foreach ($clientes as $cliente) {
//            $row = new Cliente();
//            $row->nome = $cliente['nome'];
//            $row->save();
//        }

        echo "OK!";
    }
}