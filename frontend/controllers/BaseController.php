<?php
namespace frontend\controllers;

use common\models\dao\UserDao;
use Yii;
use yii\helpers\Json;

class BaseController extends \yii\rest\ActiveController
{
   // public $serializer = 'common\models\Response';
    /**
     * @var \common\models\User
     */
    protected $_user = null;

    public function init()
    {
        parent::init();
    }

    protected function getUser()
    {
        return $this->_user;
    }

    protected function getUserId()
    {
        return $this->_user->id;
    }
    protected function errorNoLogin()
    {
       self::response("未登录", 403);
    }


    protected function failed($message){
        self::response($message, 400);
    }
    protected function success($message){
       self::response($message);
    }
    protected function response($message, $errorCode = 200){
        $toData = [
            'code' => $errorCode,
            'message' =>$message
        ];
        header('Content-Type: application/json; charset=utf-8');
        exit(Json::encode($toData));
    }



}