<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password 密码
 * @property string $create_time 创建时间
 * @property string $modified_time 修改时间
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password'], 'required'],
            [['create_time', 'modified_time'], 'safe'],
            [['username'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => '密码',
            'create_time' => '创建时间',
            'modified_time' => '修改时间',
        ];
    }
}
