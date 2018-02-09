<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "posts_image".
 *
 * @property int $posts_id
 * @property int $image_id
 */
class PostsImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['posts_id', 'image_id'], 'required'],
            [['posts_id', 'image_id'], 'integer'],
            [['posts_id', 'image_id'], 'unique', 'targetAttribute' => ['posts_id', 'image_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'posts_id' => 'Posts ID',
            'image_id' => 'Image ID',
        ];
    }
}
