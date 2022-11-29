<?php

namespace app\api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property int $poster
 * @property string $slug
 * @property string $title
 * @property string $body
 * @property int $category_id
 * @property int $status
 * @property string $created_at
 */
class Posts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['poster', 'slug', 'title', 'body', 'category_id'], 'required'],
            [['poster', 'category_id', 'status'], 'integer'],
            [['body'], 'string'],
            [['created_at'], 'safe'],
            [['slug', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'poster' => 'Poster',
            'slug' => 'Slug',
            'title' => 'Title',
            'body' => 'Body',
            'category_id' => 'Category ID',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
}
