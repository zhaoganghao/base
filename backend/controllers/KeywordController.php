<?php
namespace backend\controllers;


use common\models\dao\CategoryDao;
use common\models\dao\KeywordDao;
use common\models\union\dao\UserDao;

class KeywordController extends BaseController
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
       $this->success(KeywordDao::getAll2());
    }
    public function actionCreateOrUpdate(){
        $request = \Yii::$app->request;
        $name = $request->post("name");
        $id = intval($request->post("id"));
        if(!$name){
            $this->failed('名称不能为空');
        }
        try{
            $result  = KeywordDao::createOrUpdate($name, $id);
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