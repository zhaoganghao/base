<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property int $id 推广创意ID
 * @property int $category_id
 * @property int $status 推广单元状态，0为有效，1为搁置 -1为删除 -2为计划删除连带
 * @property string $title 标题
 * @property string $description 描述
 * @property int $rank
 * @property int $icon_id
 * @property int $image_count
 * @property string $create_time 推广计划创建时间
 * @property string $modified_time 推广计划修改时间
 */
class Posts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['category_id', 'icon_id'], 'required'],
            [['category_id', 'status', 'rank', 'icon_id'], 'integer'],
            [['create_time', 'modified_time'], 'safe'],
            [['title', 'description'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '推广创意ID',
            'category_id' => 'Category ID',
            'status' => '推广单元状态，0为有效，1为搁置 -1为删除 -2为计划删除连带',
            'title' => '标题',
            'description' => '描述',
            'rank' => 'Rank',
            'icon_id' => 'Icon ID',
            'create_time' => '推广计划创建时间',
            'modified_time' => '推广计划修改时间',
        ];
    }
}
