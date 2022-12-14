<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BlogCategory[] $blogCategories
 * @property Blog[] $blogs
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
            [['name', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[BlogCategories]].
     *
     * @return \yii\db\ActiveQuery|BlogCategoryQuery
     */
    public function getBlogCategories()
    {
        return $this->hasMany(BlogCategory::class, ['category_id' => 'id']);
    }

    /**
     * Gets query for [[Blogs]].
     *
     * @return \yii\db\ActiveQuery|BlogQuery
     */
    public function getBlogs()
    {
        return $this->hasMany(Blog::class, ['id' => 'blog_id'])->viaTable('blog_category', ['category_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }
}
