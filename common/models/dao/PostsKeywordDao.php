<?php

namespace common\models\dao;

use common\models\PostsKeyword;

class PostsKeywordDao extends PostsKeyword
{

    public static function addPostsImage($postsId, $keywordId){
       $model = self::getById($postsId, $keywordId);
       if($model){
           return true;
       }else{
           $model = new self();
           $model->posts_id = $postsId;
           $model->keyword_id= $keywordId;
           return $model->save();
       }
    }
    public static function del($postsId, $keywordId){
        $model = self::getById($postsId, $keywordId);
        if($model){
           return  $model->delete();
        }else{
            return true;
        }
    }
    public static function getById($postsId, $keywordId){
        return self::find()->where(['posts_id' => $postsId,'keyword_id' => $keywordId])->one();
    }
    public static function getByPostsId($postsId){
        return self::find()->where(['posts_id' => $postsId])->all();
    }
    public static function getKeywordDesc($postsId){
        $result =  self::find()->where(['posts_id' => $postsId])->all();
        $toResult = '';
        foreach ($result as $value){
            $keywordModel = KeywordDao::getById($value->keyword_id);
            if(!$keywordModel){
                break;
            }
            if($toResult){

                $toResult .= ",".$keywordModel->name;

            }else{
                $toResult .= $keywordModel->name;
            }
        }
        return $toResult;
    }
    public static function getKeywords($postsId){
        $result =  self::find()->where(['posts_id' => $postsId])->all();
        $toResult = [];
        foreach ($result as $value){
            $toResult[] = KeywordDao::getById($value->keyword_id);
        }
        return $toResult;
    }
    public static function getByKeyIds($keyId){
        $result = self::find()->where(['keyword_id' => $keyId])->all();
        $toResult = [];
        foreach ($result as $value){
            $toResult[] =$value->posts_id;
        }
        return $toResult;
    }
}