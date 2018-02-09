<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "image_category".
 *
 * @property int $image_id
 * @property int $category_id
 */
class ImageCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_id', 'category_id'], 'required'],
            [['image_id', 'category_id'], 'integer'],
            [['image_id', 'category_id'], 'unique', 'targetAttribute' => ['image_id', 'category_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image_id' => 'image ID',
            'category_id' => 'Category ID',
        ];
    }
}
