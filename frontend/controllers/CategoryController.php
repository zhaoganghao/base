<?php
namespace frontend\controllers;


use common\models\dao\CategoryDao;
use common\models\union\dao\UserDao;

class CategoryController extends BaseController
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
       $this->success(CategoryDao::getAll());
    }

}