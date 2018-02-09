<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "image_keyword".
 *
 * @property int $image_id
 * @property int $keyword_id
 */
class ImageKeyword extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image_keyword';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_id', 'keyword_id'], 'required'],
            [['image_id', 'keyword_id'], 'integer'],
            [['image_id', 'keyword_id'], 'unique', 'targetAttribute' => ['image_id', 'keyword_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image_id' => 'image ID',
            'keyword_id' => 'Keyword ID',
        ];
    }
}
