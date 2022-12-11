<?php

namespace app\models;

use yii\base\Model;

class CadastroForm extends Model
{
    public $nome;
    public $email;
    public $idade;
    public $site;
    public $dataNascimento;
    public $dataInicial;
    public $dataFinal;

    public function  rules()
    {
        return [
            [['nome', 'email', 'idade', 'site', 'dataNascimento', 'dataInicial', 'dataFinal'], 'required'],
            ['nome', 'string', 'min'=>5, 'max'=>60],
            ['email', 'email'],
            ['idade', 'integer', 'min'=>18, 'max'=>90],
            ['site', 'url'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nome' => 'Nome',
            'email' => 'E-mail',
            'idade' => 'Idade',
            'site' => 'Site',
            'dataNascimento' => 'Data de Nascimento',
            'dataInicial' => 'Data Inícial',
            'dataFinal' => 'Data Final'
        ];
    }
}