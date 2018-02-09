<?php
namespace backend\controllers;


use common\models\dao\CategoryDao;
use common\models\dao\ImageDao;
use common\models\dao\PostsDao;
use common\models\dao\PostsImageDao;
use common\models\dao\PostsKeywordDao;

class PostsController extends BaseController
{
    public $modelClass = 'ssp\models\union\User';
    public function actions()
    {
        return [];
    }
    public function init()
    {
        parent::init();
    }
    public function actionList()
    {
        $request = \Yii::$app->request;
        $page= intval($request->get("page"));
        $pageSize= intval($request->get("pageSize"));
        if(empty($pageSize)){
            $pageSize = 20;
        }
        if(isset($_GET['categoryId']) && $_GET['categoryId'] != ""){
            $categoryId =  intval($request->get("categoryId"));
        }else{
            $categoryId= null;
        }
        if(isset($_GET['keywordId']) && $_GET['keywordId'] != ""){
            $keywordId =  intval($request->get("keywordId"));
        }else{
            $keywordId= null;
        }
        $key = trim($request->get("key"));
       $this->success(PostsDao::getAll($key,$pageSize , $page, $categoryId, $keywordId));
    }
    public function actionImages()
    {
        $request = \Yii::$app->request;
        $id = intval($request->get("id"));
        $page= intval($request->get("page"));
        $pageSize= intval($request->get("pageSize"));
        if(empty($pageSize)){
            $pageSize = 20;
        }
        if(!$id){
            $this->failed('id不能为空');
        }
        $this->success(ImageDao::getListByPostsId($id, $pageSize , $page));
    }
    public function actionCreateOrUpdate(){
        $request = \Yii::$app->request;
        $title = $request->post("title");
        $description = $request->post("description");
        $id = intval($request->post("id"));
        if(!$title){
            $this->failed('标题不能为空');
        }
        try{
            $result  = PostsDao::createOrUpdate($title, $description, $id);
            if($result){
                $this->success('成功');
            }else{
                return $this->failed('失败');
            }
        }catch (\Exception $e){
            $this->failed($e->getMessage());
        }
        return null;
    }
    public function actionUpdateStatus(){
        $request = \Yii::$app->request;
        $id = intval($request->post("id"));
        $status = intval($request->post("status"));
        if(!$id){
            $this->failed('id不能为空');
        }
        try{
            $result  = PostsDao::updateStatus($id, $status);
            if($result){
                $this->success('成功');
            }else{
                return $this->failed('失败');
            }
        }catch (\Exception $e){
            $this->failed($e->getMessage());
        }
        return null;
    }
    public function actionUpdateCategory(){
        $request = \Yii::$app->request;
        $id = intval($request->post("id"));
        $categoryId = intval($request->post("categoryId"));
        if(!$id){
            $this->failed('id不能为空');
        }
        try{
            $result  = PostsDao::updateCategory($id, $categoryId);
            if($result){
                $this->success('成功');
            }else{
                return $this->failed('失败');
            }
        }catch (\Exception $e){
            $this->failed($e->getMessage());
        }
        return null;
    }
    public function actionUpdateRank(){
        $request = \Yii::$app->request;
        $id = intval($request->post("id"));
        $rank = intval($request->post("rank"));
        if(!$id){
            $this->failed('id不能为空');
        }
        try{
            $result  = PostsDao::updateRank($id, $rank);
            if($result){
                $this->success('成功');
            }else{
                return $this->failed('失败');
            }
        }catch (\Exception $e){
            $this->failed($e->getMessage());
        }
        return null;
    }
    public function actionAddKeyword(){
        $request = \Yii::$app->request;
        $id = intval($request->post("id"));
        $keywordId = intval($request->post("keywordId"));
        if(!$id){
            $this->failed('id不能为空');
        }
        if(!$keywordId){
            $this->failed('keywordId不能为空');
        }
        try{
            $result  = PostsKeywordDao::addPostsImage($id, $keywordId);
            if($result){
                $this->success('成功');
            }else{
                return $this->failed('失败');
            }
        }catch (\Exception $e){
            $this->failed($e->getMessage());
        }
        return null;
    }
    public function actionDelKeyword(){
        $request = \Yii::$app->request;
        $id = intval($request->post("id"));
        $keywordId = intval($request->post("keywordId"));
        if(!$id){
            $this->failed('id不能为空');
        }
        if(!$keywordId){
            $this->failed('keywordId不能为空');
        }
        try{
            $result  = PostsKeywordDao::del($id, $keywordId);
            if($result){
                $this->success('成功');
            }else{
                return $this->failed('失败');
            }
        }catch (\Exception $e){
            $this->failed($e->getMessage());
        }
        return null;
    }
    public function actionAddImage(){
        $request = \Yii::$app->request;
        $id = intval($request->post("id"));
        $imageId = intval($request->post("imageId"));
        if(!$id){
            $this->failed('id不能为空');
        }
        if(!$imageId){
            $this->failed('imageId不能为空');
        }
        try{
            $result  = ImageDao::addImage($id, $imageId);
            if($result){
                $this->success('成功');
            }else{
                return $this->failed('失败');
            }
        }catch (\Exception $e){
            $this->failed($e->getMessage());
        }
        return null;
    }
    public function actionDelImage(){
        $request = \Yii::$app->request;
        $id = intval($request->post("id"));
        $imageId = intval($request->post("imageId"));
        if(!$id){
            $this->failed('id不能为空');
        }
        if(!$imageId){
            $this->failed('imageId不能为空');
        }
        try{
            $result  =  ImageDao::del($imageId);
            if($result){
                $this->success('成功');
            }else{
                return $this->failed('失败');
            }
        }catch (\Exception $e){
            $this->failed($e->getMessage());
        }
        return null;
    }
    public function actionAddIcon(){
        $request = \Yii::$app->request;
        $id = intval($request->post("id"));
        $imageId = intval($request->post("imageId"));
        if(!$id){
            $this->failed('id不能为空');
        }
        if(!$imageId){
            $this->failed('imageId不能为空');
        }
        try{
            $result  = PostsDao::addIcon($id, $imageId);
            if($result){
                $this->success('成功');
            }else{
                return $this->failed('失败');
            }
        }catch (\Exception $e){
            $this->failed($e->getMessage());
        }
        return null;
    }
}