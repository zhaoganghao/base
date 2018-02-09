<?php

namespace common\models\dao;

use common\models\User;
use Yii;
use yii\base\Exception;

class UserDao extends User
{
    const IS_ADMIN_TRUE = 1;
    const IS_ADMIN_FALSE = 0;
    const IS_CHANNEL_TRUE = 1;
    const IS_CHANNEL_FALSE = 0;

    const ACC_TYPE_COMMON = 0;
    const ACC_TYPE_CHANNEL = 1;
    const ACC_TYPE_ADMIIN= 2;
    const ACC_TYPE_BD= 3;
    const ACC_TYPE_BD_ADMIN = 4;

    const SOURCE_QTT= 1;
    const SOURCE_HZ= 2;
    const SOURCE_CPC= 3;
    const SOURCE_HB= 4;

    static $source = [
            0 => "未设置",
            1 => "QTT",
            2 => "HZ",
            3 => "CPC",
            4 => "HB",
        ];
    static $ACC_TYPE = [
        self::ACC_TYPE_COMMON  => "普通用户",
        self::ACC_TYPE_ADMIIN  => "管理员",
        self::ACC_TYPE_CHANNEL  => "渠道用户",
        self::ACC_TYPE_BD  => "商务用户",
        self::ACC_TYPE_BD_ADMIN  => "商务管理员",
    ];
    /**
     * @param $username
     * @return array|null|\common\core\ActiveRecord
     */
    public static function getUserByUserName($username){
        return  self::find()->where(['username'=>$username])->one();
    }
    /**
     * @param $username
     * @return array|null|\common\core\ActiveRecord
     */
    public static function getUserById($id, $isArray = false){
        return  self::find()->where(['id'=>$id])->asArray($isArray)->one();
    }
    public static function getUserByBdId($id, $isArray = false){
        return  self::find()->where(['bd_id'=>$id])->asArray($isArray)->one();
    }
    public static function validatePassword($password, $password_hash){
        return Yii::$app->security->validatePassword($password, $password_hash);
    }

    public static function updateUser($uid, $data)
    {
        $contentBefore = json_encode(self::getUserById($uid, true));
        $exitUser = self::getUserByUserName($data['username']);
        if($exitUser && $exitUser->id != $uid){
            throw  new \Exception("用户名已经被使用");
        }
        $user = self::find()->where(['id' => $uid])->one();
        if(!$user){
            throw  new \Exception("当前用户不存在");
        }
        $user->username = $data['username'];
        $user->display_name = $data['displayName'];
        $user->phone = $data['phone'];
        $user->email = $data['email'];
        if(isset($data['company'])){
            $user->company= $data['company'];
        }
        if(isset($data['bank'])){
            $user->bank= $data['bank'];
        }
        if(isset($data['bankAccount'])){
            $user->bank_account= $data['bankAccount'];
        }
        if($data['password']){
            $user->password = Yii::$app->security->generatePasswordHash($data['password']);
        }
        $result = $user->save();
        if($result){
            ModifyLogDao::create('user', $user->id,$user->id,$contentBefore);
            return $user;
        }else{
            throw new \Exception($user->getErrors());
        }
    }

    public static function getUsers($key, $source = null, $bdId = null, $accType = null)
    {
        $select = "select u.* from user as u left join adslot as a on a.user_id = u.id LEFT join media as m on m.user_id=u.id where 1 = 1  ";
        $where = "";
        if($key){
            $where .= " and (u.id like '%{$key}%' or  u.username like '%{$key}%' or u.display_name 
             like '%{$key}%' or a.id like '%{$key}%'  or m.id like '%{$key}%' or a.name like '%{$key}%' or m.name like '%{$key}%')";
        }
        if(is_numeric($source)){
            $where .= "  and source= {$source}";
        }
        if(is_numeric($bdId)){
            $where .= "  and bd_id= {$bdId}";
        }
        if(is_numeric($accType)){
            $where .= "  and acc_type= {$accType}";
        }
        $where .=" group by u.id";
        $result1 = \Yii::$app->union->createCommand($select . $where )->queryAll();
        return $result1;
    }
    public static function getChannels()
    {
        return self::find()->where(['acc_type' => self::ACC_TYPE_CHANNEL])->all();
    }
    public static function getBds()
    {
        return self::find()->where(['acc_type' => self::ACC_TYPE_BD])->all();
    }
    public static function getUsersByChannelId($channelId)
    {
        return self::find()->where(['default_channel' => $channelId])->all();
    }
    public static function getUsersByBdId($bdId)
    {
        return self::find()->where(['bd_id' => $bdId])->all();
    }
    public static function resetPassword($uid)
    {
        $contentBefore = json_encode(self::getUserById($uid, true));
        $user = self::find()->where(['id' => $uid])->one();
        if(!$user){
            return false;
        }
        $user->password = Yii::$app->security->generatePasswordHash('123456');
        $result = $user->save();
        if($result){
            ModifyLogDao::create('user', $user->id,$user->id,$contentBefore, ModifyLogDao::OPERATE_TYPE_RESETPASSWORD);
            return $user;
        }else{
            throw new \Exception(json_encode($user->getErrors()));
        }
    }

    public static function updateOrCreate($data)
    {
        $contentBefore = '';
        $exitUser = self::getUserByUserName($data['username']);
        if($exitUser && $exitUser->id != $data['uid']){
            throw  new \Exception("用户名已经被使用");
        }
        if($data['uid']){
            $user = self::getUserById($data['uid']);
            $contentBefore = json_encode(self::getUserById($data['uid'],true));
        }else{
            $user = new UserDao();
        }
        $user->username = $data['username'];
        $user->display_name = $data['displayName'];
        $user->phone = $data['phone'];
        $user->email = $data['email'];
        $user->acc_type = $data['accType'];
        $user->default_channel = $data['channelId'];
        if(isset($data['company'])){
            $user->company= $data['company'];
        }
        if(isset($data['bank'])){
            $user->bank= $data['bank'];
        }
        if(isset($data['bankAccount'])){
            $user->bank_account= $data['bankAccount'];
        }
        if(isset($data['source'])){
            $user->source = $data['source'];
        }
        if($data['accType'] != UserDao::ACC_TYPE_BD){
            $user->bd_id = $data['bdId'];
        }else{
            $user->bd_id = 0;
            if(isset($data['bdAdminId'])){
                $user->bd_admin_id = $data['bdAdminId'];
            }
        }
      /*  $user->is_admin = $data['isAdmin'] == 1 ? 1 : 0;
        $user->is_channel = $data['isChannel'] == 1 ? 1 : 0;*/


        if($data['password']){
            $user->password = Yii::$app->security->generatePasswordHash($data['password']);
        }
        $result = $user->save();
        if($result){
            if($data['uid']){
                ModifyLogDao::create('user', $user->id,$user->id, $contentBefore);
            } else{
                ModifyLogDao::create('user', $user->id,$user->id,  $contentBefore, ModifyLogDao::OPERATE_TYPE_CREATE);
            }
            return $user;
        }else{
            throw new \Exception($user->getErrors());
        }
    }

    public static function getById($id, $isArray = false)
    {
        return self::find()->where(['id' => $id])->asArray($isArray)->one();
    }

    public static function getAllBd($uid)
    {
        return self::find()->where(['acc_type' => UserDao::ACC_TYPE_BD, 'bd_admin_id' => $uid])->all();
    }

    public static function getBdAdminUsers()
    {
        return self::find()->where(['acc_type' => UserDao::ACC_TYPE_BD_ADMIN])->all();
    }

}