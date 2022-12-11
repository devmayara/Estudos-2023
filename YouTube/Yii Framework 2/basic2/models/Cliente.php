<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%clientes}}".
 *
 * @property int $id
 * @property string $nome
 * @property string $foto
 */
class Cliente extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $fotoCliente;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%clientes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            ['fotoCliente', 'file', 'extensions' => 'jpg, png'],
            [['nome', 'foto'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'foto' => 'Foto do Cliente',
            'fotoCliente' => 'Foto do Cliente',
        ];
    }
}
