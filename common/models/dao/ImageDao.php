<?php

namespace common\models\dao;

use common\models\Image;

class ImageDao extends Image
{
    const STATUS_CHECK = 0;
    const STATUS_DEL = 1;
    const STATUS_OK = 2;
    const TYPE_IMAGE = 0;
   public static function getAll($key, $pageSize , $page, $categoryId= null, $keywordId = null){
       $from = " from image as i LEFT JOIN category as c on i.category_id = c.id left join posts p on p.id= i.posts_id ";
       $select = "SELECT i.* ,c.name as category_name,p.title as title ";
       $where = "where 1= 1";
       if($key){
           $where .= " and (i.name like '%$key%' or i.id like '%$key%')";
       }
      if(is_numeric($categoryId)){
           $where .= " and i.category_id =" .$categoryId;
       }
       if(is_numeric($keywordId)){
           $idsArr = ImageKeywordDao::getByKeyIds($keywordId);
           if($idsArr){
               $idsArr = implode(",",$idsArr);
               $where .= " and i.id in($idsArr)" ;
           }

       }
       if(empty($page)){
           $page=1;
       }
       $page--;
       $offset = $page*$pageSize;
       $result1 = \Yii::$app->db->createCommand("select count(*)  as total".$from.$where)->queryAll();
       $total = $result1[0]['total'];
       $toSql = $select.$from.$where ." order by i.id desc limit $offset, $pageSize";
       $result = \Yii::$app->db->createCommand($toSql)->queryAll();
       foreach ($result as $key => $value){
           $result[$key]['keyword'] = ImageKeywordDao::getKeywords($value['id']);
          // $result[$key]['posts'] = PostsDao::getById($value['posts_id']);
       }
       return ['total' =>$total, 'pageSize' => $pageSize,'page' => $page+1, 'data' => $result];
   }

    public static function createOrUpdate($url, $refId, $postsId)
    {

        $model = self::getByRefIdAndPostsId($refId, $postsId);
        if($model){
            return $model;
        }
        $model = new self();
        $model->status = 0;
        $model->remote_url = $url;
        $model->ref_url = $url;
        $model->ref_id = $refId;
        $model->posts_id = $postsId;
        if($model->save()){
            return $model;
        }
    }

    public static function updateStatus($id, $status)
    {
        $model = self::getById($id);
        if(!$model){
            return false;
        }
        $model->status = $status;
        return $model->save();
    }

    public static function updateCategory($id, $categoryId)
    {
        $model = self::getById($id);
        if(!$model){
            return false;
        }
        $model->category_id = $categoryId;
        return $model->save();
    }

    public static function getById($id){

        return self::find()->where(['id'=>$id])->one();
    }
    public static function getByRefIdAndPostsId($refId, $postsId){
        return self::find()->where(['ref_id'=>$refId, 'posts_id' => $postsId])->one();
    }
    public static function getByPostsId($postsId){
        return self::find()->where(['posts_id' => $postsId])->all();
    }
    public static function getListByPostsId($postsId, $pageSize , $page){

        $from = " from image  where posts_id = $postsId ";

        if(empty($page)){
            $page=1;
        }
        $page--;
        $offset = $page*$pageSize;

        $result1 = \Yii::$app->db->createCommand("select count(*)  as total".$from)->queryAll();
        $total = $result1[0]['total'];
        $toSql = "select * ".$from ." order by id desc limit $offset, $pageSize";
        $result = \Yii::$app->db->createCommand($toSql)->queryAll();
        foreach ($result as $key => $value){
            //$result[$key]['keyword_desc'] = PostsKeywordDao::getKeywordDesc($value['id']);
        }
        $posts = PostsDao::getById($postsId);
        return ['total' =>$total, 'pageSize' => $pageSize,'page' => $page+1, 'data' => $result,"posts"=>$posts];
    }
    public static function getByLists($postsId,$pageSize , $page){
        $sql = "select * from image where post";
        return self::find()->where(['posts_id' => $postsId])->all();
    }
    public static function getByFileMd5($md5){
        return  self::find()->where(['file_md5'=>$md5])->one();
    }
    public static function getByFileMd5AndUserId($md5){
        return  self::find()->where(['file_md5'=>$md5])->one();
    }

    public static function addImage($postsId, $imageId)
    {
        $model = self::getById($imageId);
        if($model){
            $model->posts_id = $postsId;
            $result = $model->save();
            if($result){
                return PostsDao::updateImageCount($postsId);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function del($imageId)
    {
        $model = self::getById($imageId);
        if($model){
            $postsId = $model->posts_id;
            $model->posts_id = 0;
            $result = $model->save();
            if($result){
                return PostsDao::updateImageCount($postsId);
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    public static function bindPosts($id, $postsId)
    {
        $model = self::getById($id);
        if($model){
            $model->posts_id = $postsId;
            $result = $model->save();
            if($result){
                return PostsDao::updateImageCount($postsId);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function getNext($id, $page)
    {
        $page --;
        $sql = "select * from image where posts_id = $id limit $page,1";
        $result = \Yii::$app->db->createCommand($sql)->queryAll();
        return $result[0]['remote_url'];
    }

    public static function getCountByPostsId($postsId)
    {
        return self::find()->where(['posts_id' => $postsId])->count();
    }

    public static function updateData($id, $filename, $ext, $width, $height, $size, $md5)
    {
        $image = self::getById($id);
        if(!$image){
            return false;
        }
        $image->file_name= $filename;
        $image->image_ext = $ext;
        $image->image_width= $width;
        $image->image_height= $height;
        $image->file_size = $size;
        $image->file_md5 = $md5;
        return  $image->save();
    }

}