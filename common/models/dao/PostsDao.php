<?php

namespace common\models\dao;

use common\models\Image;
use common\models\Posts;

class PostsDao extends Posts
{
    const STATUS_CHECK = 0;
    const STATUS_DEL = 1;
    const STATUS_OK = 2;

    public static function getAll($key, $pageSize , $page, $categoryId= null, $keywordId = null){
       $from = " from posts as p LEFT JOIN category as c on p.category_id = c.id ";
       $select = "SELECT p.* ,c.name as category_name ";
       $where = "where 1= 1";
       if($key){
           $where .= " and (p.title like '%$key%' or p.id = '$key')";
       }
      if(is_numeric($categoryId)){
           $where .= " and p.category_id =" .$categoryId;
       }
       if(is_numeric($keywordId)){
          // $where .= " and p.category_id =" .$categoryId;
           $idsArr = PostsKeywordDao::getByKeyIds($keywordId);
           if($idsArr){
               $idsArr = implode(",",$idsArr);
               $where .= " and p.id in($idsArr)" ;
           }else{
               $where .= " and p.id in(0)" ;
           }
       }
       if(empty($page)){
           $page=1;
       }
       $page--;
       $offset = $page*$pageSize;
       $result1 = \Yii::$app->db->createCommand("select count(*)  as total".$from.$where)->queryAll();
       $total = $result1[0]['total'];
       $toSql = $select.$from.$where ." order by p.id desc limit $offset, $pageSize";
       $result = \Yii::$app->db->createCommand($toSql)->queryAll();
       foreach ($result as $key => $value){
           $result[$key]['keyword'] = PostsKeywordDao::getKeywords($value['id']);
           $result[$key]['icon_image'] = ImageDao::getById($value['icon_id']);
       }
       return ['total' =>$total, 'pageSize' => $pageSize,'page' => $page+1, 'data' => $result];
   }

    public static function createOrUpdate($title, $description, $id = 0, $refId = 0)
    {
        if($id){
            $model = self::getById($id);
        }else{
            $model = new self();
        }
        $model->title = $title;
        $model->description = $description;
        if($refId){
            $model->ref_id = $refId;
        }

        if($model->save()){
            return $model;
        }else{
            return false;
        }
    }
    public static function getById($id){
        return self::find()->where(['id' => $id])->one();
    }
    public static function getByRefId($id){
        return self::find()->where(['ref_id' => $id])->one();
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

    public static function updateRank($id, $rank)
    {
        $model = self::getById($id);
        if(!$model){
            return false;
        }
        $model->rank = $rank;
        return $model->save();
    }


    public static function addIcon($id, $imageId)
    {
        $model = self::getById($id);
        if(!$model){
            return false;
        }
        $model->icon_id = $imageId;
        return $model->save();
    }


    public static function getByCategoyId($categroyId, $page, $pageSize)
    {
        $ok =  self::STATUS_OK;
        $sql = "from posts where category_id = $categroyId and status = {$ok}";
        $page--;
        $offset = $page*$pageSize;
        $result1 = \Yii::$app->db->createCommand("select count(*)  as total ".$sql)->queryAll();
        $total = $result1[0]['total'];
        $toSql = "select id,title,icon_id ".$sql." order by rank asc limit $offset, $pageSize";

        $result = \Yii::$app->db->createCommand($toSql)->queryAll();
        foreach ($result as $key => $value){
            $image = ImageDao::getById($value['icon_id']);
            $result[$key]['icon_image'] = $image->remote_url;
        }
        return ['total' =>$total, 'pageSize' => $pageSize,'page' => $page+1, 'data' => $result];

    }

    public static function getByKeywordId($keywordId, $page, $pageSize)
    {
        $sql = "from posts as p left JOIN posts_keyword as pk on p.id = pk.posts_id where pk.keyword_id = $keywordId ";
        $page--;
        $offset = $page*$pageSize;
        $result1 = \Yii::$app->db->createCommand("select count(*)  as total ".$sql)->queryAll();
        $total = $result1[0]['total'];
        $toSql = "select p.* ".$sql." order by rank asc limit $offset, $pageSize";
        $result = \Yii::$app->db->createCommand($toSql)->queryAll();
        foreach ($result as $key => $value){
            $image = ImageDao::getById($value['icon_id']);
            $result[$key]['icon_image'] = $image ? $image->remote_url : "";
        }
        return ['total' =>$total, 'pageSize' => $pageSize,'page' => $page+1, 'data' => $result];
    }

    public static function getInfo($id, $page)
    {
        $model = self::getById($id);
        $count = ImageDao::getCountByPostsId($id);
        if($page > $count){
            return ['title' => $model->title, "image_url" => "","total" => $count,"keywords" => PostsKeywordDao::getKeywords($id)];
        }
        $url =ImageDao::getNext($id, $page);
        return ['title' => $model->title, "image_url" => $url,"total" => $count,"keywords" => PostsKeywordDao::getKeywords($id)];
    }

    public static function index()
    {
        $ok =  self::STATUS_OK;
        $sql ="select p.id ,p.title,p.icon_id from posts as p left join posts_keyword as kw on p.id = kw.posts_id where kw.keyword_id = 7 and p.status = {$ok} limit 3";
        $result = \Yii::$app->db->createCommand($sql)->queryAll();
        $carousel = [];
        foreach($result as $key => $value){
            $image = ImageDao::getById($value['icon_id']);
            if(!$image){
                continue;
            }
            $value['icon_image'] = $image ? $image->remote_url : "";
            $carousel[] = $value;
        }
        $categorys  = CategoryDao::getAll();
        $categoryData = [];
        foreach ($categorys as $key => $value){
            $posts = self::getIndexByCategoyId($value['id'], 6);
            if(!$posts){
                continue;
            }
            $categoryData[] = [
                'category' => $value,
                'posts'=> $posts
            ];
        }
        return ['carousel' =>$carousel,'categoryData' => $categoryData ];
    }

    private static function getIndexByCategoyId($categoryId, $size)
    {
        $ok =  self::STATUS_OK;
        $sql = "select id ,title,icon_id  from posts where category_id = $categoryId and status = {$ok} order by rank asc limit $size";
        $result = \Yii::$app->db->createCommand($sql)->queryAll();
        $toResult = [];
        foreach($result as $key => $value){
            $image = ImageDao::getById($value['icon_id']);
            if($image){
                $toResult[] = [
                    'id' => $value['id'],
                    'title' => $value['title'],
                    'icon_image' =>  $image->remote_url
                ];
            }

        }
        return $toResult;
    }

    public static function recommend()
    {
        $ok =  self::STATUS_OK;
        $sql ="select p.id ,p.title,p.icon_id from posts as p left join posts_keyword as kw on p.id = kw.posts_id where   kw.keyword_id = 8 and p.status = {$ok}  limit 6";
        $result = \Yii::$app->db->createCommand($sql)->queryAll();
        foreach($result as $key => $value){
            $image = ImageDao::getById($value['icon_id']);
            $result[$key]['icon_image'] = $image->remote_url;
        }
        return $result;
    }

    public static function search($key)
    {
        $ok =  self::STATUS_OK;
        $sql ="select id ,title,icon_id from posts where title  like '%{$key}%'  and status = {$ok}  limit 100 ";
        $result = \Yii::$app->db->createCommand($sql)->queryAll();
        foreach($result as $key => $value){
            $image = ImageDao::getById($value['icon_id']);
            $result[$key]['icon_image'] =$image ? $image->remote_url : "";
        }
        return $result;
    }

    public static function updateImageCount($postsId)
    {
        $model = self::getById($postsId);
        if(!$model){
            return false;
        }
        $model->image_count =  ImageDao::getCountByPostsId($postsId);
        return $model->save();
    }


}