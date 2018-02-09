<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id 素材资源ID
 * @property string $status 素材名称
 * @property string $category_id 素材名称
 * @property int $type 素材类型，0图片
 * @property string $file_name 资源文件名
 * @property string $remote_url 远程下载地址
 * @property string $file_size 资源文件大小
 * @property string $file_md5 资源文件摘要，用于去重
 * @property int $image_width 图片素材宽度
 * @property int $image_height 图片素材高度
 * @property string $image_ext 图片素材类型，即扩展名
 * @property string $create_time 推广计划创建时间
 * @property string $modified_time 推广计划修改时间
 * @property string $ref_url
 * @property string $ref_id
 * @property string $posts_id
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['name', 'type', 'file_name', 'file_size', 'file_md5'], 'required'],
            [['type', 'file_size', 'image_width', 'image_height'], 'integer'],
            [['create_time', 'modified_time'], 'safe'],
            [['name', 'file_name', 'remote_url'], 'string', 'max' => 256],
            [['file_md5'], 'string', 'max' => 32],
            [['image_ext'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '素材资源ID',
            'name' => '素材名称',
            'type' => '素材类型，0图片',
            'file_name' => '资源文件名',
            'remote_url' => '远程下载地址',
            'file_size' => '资源文件大小',
            'file_md5' => '资源文件摘要，用于去重',
            'image_width' => '图片素材宽度',
            'image_height' => '图片素材高度',
            'image_ext' => '图片素材类型，即扩展名',
            'create_time' => '推广计划创建时间',
            'modified_time' => '推广计划修改时间',
        ];
    }
}
