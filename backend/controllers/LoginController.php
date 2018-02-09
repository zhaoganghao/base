<?php
namespace backend\controllers;

use common\models\dao\UserDao;
use Yii;
class LoginController extends \yii\rest\ActiveController
{
    public $modelClass = 'ssp\models\union\User';

    public function actionLogin()
    {
        $request = Yii::$app->getRequest();
        $username = $request->getBodyParam('username');
        $password =$request->getBodyParam('password');
        $userModel = UserDao::getUserByUserName($username);
        if(!$userModel){
            $this->failed('账户不存在');
        }
        //var_dump(Yii::$app->security->generatePasswordHash("123456"));die;
        $flag = UserDao::validatePassword($password, $userModel->password);
        if($flag){
            Yii::$app->session->set('userId',$userModel->id);
            Yii::$app->session->set('username',$userModel->username);
            return [
                'id' => $userModel->id,
            ];
        }else{
            $this->failed('账户或者用户名密码错误');
        }
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