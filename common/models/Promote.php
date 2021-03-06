<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property string $url
 * @property string $date
 * @property int $promote
 * @property int $pv
 * @property int $uv
 * @property string $create_time 创建时间
 * @property string $modified_time 修改时间
 */
class Promote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promote';
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        ];
    }
}
