<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $created_at
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'title', 'description'], 'required'],
            [['description'], 'string'],
            [['created_at'], 'safe'],
            [['slug', 'title'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'title' => 'Title',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['newpost'] = ['title', 'description'];

        return $scenarios;
    }
}
