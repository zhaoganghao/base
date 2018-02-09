<?php

namespace common\models\dao;

use common\models\ImageKeyword;

class ImageKeywordDao extends ImageKeyword
{

    public static function addImageKeyWord($imageId, $keywordId){
       $model = self::getById($imageId, $keywordId);
       if($model){
           return true;
       }else{
           $model = new self();
           $model->keyword_id = $keywordId;
           $model->image_id= $imageId;
           return $model->save();
       }
    }
    public static function del($keywordId, $imageId){
        $model = self::getById($keywordId, $imageId);
        if($model){
           return  $model->delete();
        }else{
            return true;
        }
    }
    public static function getById($imageId, $keywordId){
        return self::find()->where(['keyword_id' => $keywordId, 'image_id' => $imageId])->one();
    }

    public static function getKeywordDesc($id)
    {
        $result =  self::find()->where(['image_id' => $id])->all();
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
    public static function getKeywords($id)
    {
        $result =  self::find()->where(['image_id' => $id])->all();
        $toResult = [];
        foreach ($result as $value){
            $toResult = KeywordDao::getById($value->keyword_id);
        }
        return $toResult;
    }
    public static function getByImageId($imageId){
        return self::find()->where(['image_id' => $imageId])->all();
    }
    public static function getByKeyId($keyId){
        return self::find()->where(['keyword_id' => $keyId])->all();
    }
    public static function getByKeyIds($keyId){
        $result = self::find()->where(['keyword_id' => $keyId])->all();
        $toResult = [];
        foreach ($result as $value){
            $toResult[] =$value->image_id;
        }
        return $toResult;
    }
}