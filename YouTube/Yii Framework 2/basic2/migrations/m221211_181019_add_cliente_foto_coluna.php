<?php

use app\models\Cliente;
use yii\db\Migration;

/**
 * Class m221211_181019_add_cliente_foto_coluna
 */
class m221211_181019_add_cliente_foto_coluna extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Cliente::tableName(), 'foto', $this->string(60));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Cliente::tableName(), 'foto',);
    }
}
