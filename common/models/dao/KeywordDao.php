<?php

namespace common\models\dao;

use common\models\Keyword;
use common\models\Posts;

class KeywordDao extends Keyword
{
   public static function getAll(){
       return self::find()->where(['status' => 1])->all();
   }
    public static function getAll2(){
        return self::find()->all();
    }
    public static function createOrUpdate($name, $id)
    {
        if($id){
            $model = self::getById($id);
        }else{
            $model = new self();
        }
        $model->name = $name;
        return $model->save();
    }
    public static function getById($id){
        return self::find()->where(['id' => $id])->one();
    }
}