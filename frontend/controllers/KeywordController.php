<?php
namespace frontend\controllers;


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
       $this->success(KeywordDao::getAll());
    }
}