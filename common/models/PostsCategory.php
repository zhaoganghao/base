<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "posts_category".
 *
 * @property int $posts_id
 * @property int $category_id
 */
class PostsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['posts_id', 'category_id'], 'required'],
            [['posts_id', 'category_id'], 'integer'],
            [['posts_id', 'category_id'], 'unique', 'targetAttribute' => ['posts_id', 'category_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'posts_id' => 'Posts ID',
            'category_id' => 'Category ID',
        ];
    }
}
