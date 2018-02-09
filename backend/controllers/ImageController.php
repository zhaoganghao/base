<?php
namespace backend\controllers;


use common\helpers\Image;
use common\models\dao\CategoryDao;
use common\models\dao\ImageDao;
use common\models\dao\ImageKeywordDao;
use common\models\dao\PostsDao;
use common\models\dao\PostsImageDao;
use common\models\ImageKeyword;
use common\models\union\dao\UserDao;
use common\models\UploadForm;
use Yii;
use yii\web\UploadedFile;

class ImageController extends BaseController
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
        $this->success(ImageDao::getAll($key,$pageSize , $page, $categoryId, $keywordId));
    }
    public function actionBindPosts()
    {
        $request = \Yii::$app->request;
        $id= intval($request->post("id"));
        $postsId= intval($request->post("postsId"));
        if(!$id){
            $this->failed("id不能为空");
        }
        if(!$postsId){
            $this->failed("postsId不能为空");
        }
        $result = ImageDao::bindPosts($id, $postsId);
        if($result){
            $this->success("绑定成功");
        }else{
            $this->failed("绑定失败");
        }

    }
    public function actionUpload(){
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            try{
                $model->imageFile = UploadedFile::getInstanceByName('file');
                if(!$model->imageFile){
                    $this->failed('文件不能为空');
                }
                $postsId = intval(Yii::$app->request->post("postsId"));
                $result = $model->upload($postsId);
                if ($result) {
                    $this->success(ImageDao::getById($result->id));
                }else{
                    $this->failed('上传失败');
                }
            }catch (\Exception $e){
                $this->failed($e->getMessage());
            }
        }else{
            $this->failed('非post请求出错');
        }
        return [];


    }

    public function actionUpdateStatus(){
        $request = \Yii::$app->request;
        $id = intval($request->post("id"));
        $status = intval($request->post("status"));
        if(!$id){
            $this->failed('id不能为空');
        }
        try{
            $result  = ImageDao::updateStatus($id, $status);
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
            $result  = ImageDao::updateCategory($id, $categoryId);
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
            $result  = ImageKeywordDao::addImageKeyWord($id, $keywordId);
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
            $result  = ImageKeywordDao::del($id, $keywordId);
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