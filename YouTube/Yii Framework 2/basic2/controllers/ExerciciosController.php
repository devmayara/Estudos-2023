<?php

namespace app\controllers;

use app\models\CadastroModel;

class ExerciciosController extends \yii\web\Controller
{
    public function actionFormulario()
    {
        $cadastroModel = new CadastroModel;

        return $this->render('formulario', [
            'model' => $cadastroModel
        ]);
    }

}
