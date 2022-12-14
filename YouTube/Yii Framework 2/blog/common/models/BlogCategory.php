<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "blog_category".
 *
 * @property int $blog_id
 * @property int $category_id
 *
 * @property Blog $blog
 * @property Category $category
 */
class BlogCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['blog_id', 'category_id'], 'required'],
            [['blog_id', 'category_id'], 'integer'],
            [['blog_id', 'category_id'], 'unique', 'targetAttribute' => ['blog_id', 'category_id']],
            [['blog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Blog::class, 'targetAttribute' => ['blog_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'blog_id' => 'Blog ID',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * Gets query for [[Blog]].
     *
     * @return \yii\db\ActiveQuery|BlogQuery
     */
    public function getBlog()
    {
        return $this->hasOne(Blog::class, ['id' => 'blog_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|CategoryQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * {@inheritdoc}
     * @return BlogCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlogCategoryQuery(get_called_class());
    }
}
