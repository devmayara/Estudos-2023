<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog}}`.
 */
class m221214_132453_create_blog_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%blog}}', [
            'id' => $this->primaryKey(),
            'text' => $this->text()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-blog-created_by', '{{%blog}}', 'created_by');
        $this->addForeignKey('fk-blog-created_by', '{{%blog}}', 'created_by', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx-blog-updated_by', '{{%blog}}', 'updated_by');
        $this->addForeignKey('fk-blog-updated_by', '{{%blog}}', 'updated_by', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog}}');
    }
}
