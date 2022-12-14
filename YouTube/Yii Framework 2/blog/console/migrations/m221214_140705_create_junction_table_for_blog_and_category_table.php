<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_category}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%blog}}`
 * - `{{%category}}`
 */
class m221214_140705_create_junction_table_for_blog_and_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%blog_category}}', [
            'blog_id' => $this->integer(),
            'category_id' => $this->integer(),
            'PRIMARY KEY(blog_id, category_id)',
        ]);

        // creates index for column `blog_id`
        $this->createIndex(
            '{{%idx-blog_category-blog_id}}',
            '{{%blog_category}}',
            'blog_id'
        );

        // add foreign key for table `{{%blog}}`
        $this->addForeignKey(
            '{{%fk-blog_category-blog_id}}',
            '{{%blog_category}}',
            'blog_id',
            '{{%blog}}',
            'id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-blog_category-category_id}}',
            '{{%blog_category}}',
            'category_id'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            '{{%fk-blog_category-category_id}}',
            '{{%blog_category}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_category}}');
    }
}
