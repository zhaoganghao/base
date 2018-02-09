<?php
namespace backend\controllers;

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
        $user_id = Yii::$app->session->get('userId');
        if(!$user_id){
            $this->errorNoLogin();
        }elseif($this->_user === null){
            $this->_user = UserDao::getUserById($user_id);
        }
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