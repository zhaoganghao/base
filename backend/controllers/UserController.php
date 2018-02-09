<?php
namespace backend\controllers;

/*
'PUT,PATCH {id}' => 'update',
'DELETE {id}' => 'delete',
'GET,HEAD {id}' => 'view',
'POST' => 'create',
'GET,HEAD' => 'index',
'{id}' => 'options',
 */
use common\models\union\dao\UserDao;

class UserController extends BaseController
{
    public $modelClass = 'ssp\models\union\User';
    public function actions()
    {
        return [];
    }
    public function init()
    {
        parent::init();
        $this->needAgentAuthority();
    }
    public function actionLoadUser()
    {
        $optUid = $this->getAgentId();
        $user = UserDao::getUserById($optUid);
        $data = [
            'id' => $user->id,
            'username' => $user->username,
            'isAdmin'=> $user->is_admin,
            'isChannel'=> $user->is_channel,
            'display_name'=> $user->display_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'acc_type' => $user->acc_type,
            'qq' => $user->qq,
            'company' => $user->company,
            'bank_account' => $user->bank_account,
            'bank' => $user->bank,
      /*      'pay_company' => $user->pay_company,
            'company' => $user->company,
            'bank' => $user->bank,
            'bank_address' => $user->bank_address,
            'bank_account' => $user->bank_account,
            'payee' => $user->payee,*/
        ];
        return $data;
    }
    public function actionUpdateUser(){
        $request = \Yii::$app->request;
        $username = $request->post("username");
        $password = $request->post("password");
        $displayName = $request->post("displayName");
        $phone = $request->post("phone");
        $email = $request->post("email");
        $company = trim($request->post("company"));
        $bank = trim($request->post("bank"));
        $bankAccount = trim($request->post("bankAccount"));
        if(!$username){
            $this->errorMessage('账户名不能为空');
        }
        if(!$displayName){
            $this->errorMessage('联系人不能为空');
        }
        if(!$phone){
            $this->errorMessage('电话不能为空');
        }
        if(strlen($phone) != 11){
            $this->errorMessage( '手机号格式不对');
        }
        if(!$email){
            $this->errorMessage('邮件不能为空');
        }
        if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
            $this->errorMessage( '邮件格式不对');
        }
        if($password && strlen($password) < 6){
            $this->errorMessage( '密码至少6位');
        }
        $data = [
            'username' => $username,
            'displayName' => $displayName,
            'password' => $password,
            'phone' => $phone,
            'email' => $email,
            'company' => $company,
            'bank' => $bank,
            'bankAccount' => $bankAccount,
        ];
        try{

            $result1 = UserDao::updateUser($this->getAgentId(), $data);
            UserDao::setDb(\Yii::$app->get('other'));
            $result2 = UserDao::updateUser($this->getAgentId(), $data);
            UserDao::setDb(\Yii::$app->get('union'));
            if($result1 && $result2){
                $this->success('修改成功');
            }else{
                return $this->errorMessage('修改失败');
            }
        }catch (\Exception $e){
            $this->errorMessage($e->getMessage());
        }
        return null;
    }
}