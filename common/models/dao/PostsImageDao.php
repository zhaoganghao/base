<?php

namespace common\models\dao;

use common\models\PostsImage;

class PostsImageDao extends PostsImage
{

    public static function addPostsImage($postsId, $imageId){
       $model = self::getById($postsId, $imageId);
       if($model){
           return true;
       }else{
           $model = new self();
           $model->posts_id = $postsId;
           $model->image_id= $imageId;
           return $model->save();
       }
    }
    public static function del($postsId, $imageId){
        $model = self::getById($postsId, $imageId);
        if($model){
           return  $model->delete();
        }else{
            return true;
        }
    }
    public static function getById($postsId, $imageId){
        return self::find()->where(['posts_id' => $postsId,'image_id' => $imageId])->one();
    }
    public static function getImagesById($id)
    {
       $result = self::find()->where(['posts_id' => $id])->all();
        $imageModelArr = [];
       foreach ($result as $key => $value){
           $imageModelArr[] = ImageDao::getById($value->image_id);
       }
       return $imageModelArr;
    }
}