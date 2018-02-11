<?php
namespace backend\controllers;


use common\models\dao\CategoryDao;
use common\models\dao\KeywordDao;
use common\models\dao\ReportDateDao;
use common\models\dao\ReportHourDao;
use common\models\dao\ReportMinuteDao;
use common\models\dao\ReportPromoteDateDao;
use common\models\union\dao\UserDao;

class ReportController extends BaseController
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
    public function actionRealTime(){
        $startDate = date("Y-m-d 00:00:00");
        $endDate = date("Y-m-d 23:59:59");
        $today = ReportMinuteDao::getByDate($startDate, $endDate);
        $todayData = [];
        foreach ($today as $value){
            $todayData[] =$value['pv'];
        }

        $startDate = date("Y-m-d 00:00:00", strtotime(" -1 days"));
        $endDate = date("Y-m-d 23:59:59", strtotime(" -1 days"));
        $yestoday = ReportMinuteDao::getByDate($startDate, $endDate);
        $yesData = [];
        foreach ($today as $value){
            $yesData[] =$value['pv'];
        }

        $this->success(['today' => $today, 'yestoday' =>$yestoday]);
    }

    public function actionDate(){
        $request = \Yii::$app->request;
        $startDate = trim($request->get("startDate"));
        $endDate =  trim($request->get("endDate"));
        if(!$startDate){
            $this->failed('startDate不能为空');
        }
        if(!$endDate){
            $this->failed('endDate不能为空');
        }
        $this->success(ReportDateDao::getByDate($startDate, $endDate));
    }
    public function actionHour(){
        $request = \Yii::$app->request;
        $date =  trim($request->get("date"));
        if(!$date){
            $this->failed('date不能为空');
        }
        $this->success(ReportHourDao::getHourPvByDate($date));
    }
/*    public function actionPromoteHour(){
        $request = \Yii::$app->request;
        $date =  trim($request->get("date"));
        if(!$date){
            $this->failed('date不能为空');
        }
        $this->success(ReportPromoteDateDao::getPromoteByHour($date));
    }*/
    public function actionPromoteDate(){
        $request = \Yii::$app->request;
        $startDate = trim($request->get("startDate"));
        $endDate =  trim($request->get("endDate"));
        if(!$startDate){
            $this->failed('startDate不能为空');
        }
        if(!$endDate){
            $this->failed('endDate不能为空');
        }
        $this->success(ReportPromoteDateDao::getPromoteByDate($startDate, $endDate));
    }
    public function actionApiDate(){
        $request = \Yii::$app->request;
        $startDate = trim($request->get("startDate"));
        $endDate =  trim($request->get("endDate"));
        if(!$startDate){
            $this->failed('startDate不能为空');
        }
        if(!$endDate){
            $this->failed('endDate不能为空');
        }
        $this->success(ReportHourDao::getApiByDate($startDate, $endDate));
    }
}