<?php

namespace common\models\dao;


use common\models\Promote;

class PromoteDao extends Promote
{


    public static function getAll()
    {
        return self::find()->all();
    }
    public static function getById($id){
        return self::find()->where(['id' => $id])->one();
    }
    public static function createOrUpdate($name, $id)
    {
        if($id){
            $model = self::getById($id);
        }
        if(!isset($model)){
            $model = new self();
            $model->id = self::generateId();
        }
        $model->name = $name;
        return $model->save();
    }
    public static function generateId(){
        return self::find()->max("id") + 1;
    }
}
