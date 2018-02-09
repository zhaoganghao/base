<?php
namespace backend\controllers;

use Yii;

class LogoutController extends BaseController
{

    public $modelClass = 'frontend\models\User';
    public function actions()
    {
        return [];
    }

    public function actionLogout()
    {
        Yii::$app->session->remove('userId');
        Yii::$app->session->remove('username');
        $this->success("登出成功");
    }

}