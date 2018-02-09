<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "posts_keyword".
 *
 * @property int $posts_id
 * @property int $keyword_id
 */
class PostsKeyword extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts_keyword';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['posts_id', 'keyword_id'], 'required'],
            [['posts_id', 'keyword_id'], 'integer'],
            [['posts_id', 'keyword_id'], 'unique', 'targetAttribute' => ['posts_id', 'keyword_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'posts_id' => 'Posts ID',
            'keyword_id' => 'Keyword ID',
        ];
    }
}
