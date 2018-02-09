<?php
namespace frontend\controllers;


use common\models\dao\ImageDao;
use common\models\dao\PostsDao;
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
    public function actionCategoyList()
    {
        //$cid, $page, $pageSize
        $request = \Yii::$app->request;
        $cid= intval($request->get("cid"));
        $page= intval($request->get("page"));
        $pageSize= intval($request->get("pageSize"));
        if(empty($pageSize)){
            $pageSize = 12;
        }
        if(!$cid){
            $this->failed("cid不能为空");
        }
        if(!$page){
            $page = 1;
        }
        $this->success(PostsDao::getByCategoyId($cid,$page,$pageSize ));
    }
    public function actionKeywordList()
    {
        $request = \Yii::$app->request;
        $keywordId= intval($request->get("kid"));
        $page= intval($request->get("page"));
        $pageSize= intval($request->get("pageSize"));
        if(empty($pageSize)){
            $pageSize = 20;
        }
        if(!$keywordId){
            $this->failed("kid不能为空");
        }
        if(!$page){
            $page = 1;
        }
        $this->success(PostsDao::getByKeywordId($keywordId,$page,$pageSize ));
    }
    public function actionIndex()
    {
        $this->success(PostsDao::index());
    }
    public function actionInfo()
    {
        $request = \Yii::$app->request;
        $id= intval($request->get("id"));
        $page= intval($request->get("page"));
        if(!$id){
            $this->failed("id不能为空");
        }
        if(!$page){
            $page = 1;
        }
        $this->success(PostsDao::getInfo($id,$page));
    }
    public function actionRecommend()
    {
        $this->success(PostsDao::recommend());

    }
    public function actionSearch()
    {
        $request = \Yii::$app->request;
        $key= trim($request->get("key"));
        if(!$key){
            $this->failed("请先填写关键字");
        }
        $this->success(PostsDao::search($key));

    }
}