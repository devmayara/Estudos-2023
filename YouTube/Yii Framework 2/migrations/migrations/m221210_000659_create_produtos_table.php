<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%produtos}}`.
 */
class m221210_000659_create_produtos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%produtos}}', [
            'id' => $this->primaryKey(),
            'categoria_id' => $this->integer()->notNull(),
            'data_cadastro' => $this->dateTime()->notNull(),
            'nome' => $this->string(60),
            'descricao' => $this->text(),
            'valor' => $this->decimal(10,2)->notNull(),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(1)
        ]);

        $this->addForeignKey('fk_produtos_categoria_id','produtos', 'categoria_id', 'categorias', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_produtos_categoria_id', 'produtos');
        $this->dropTable('{{%produtos}}');
    }
}
