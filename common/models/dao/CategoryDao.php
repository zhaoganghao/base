<?php

namespace common\models\dao;

use common\models\Category;
use Yii;

class CategoryDao extends Category
{
   public static function getAll(){
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