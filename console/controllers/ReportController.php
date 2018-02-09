<?php
namespace console\controllers;

use common\models\dao\ReportDateDao;
use common\models\dao\ReportHourDao;
use common\models\dao\ReportMinuteDao;
use common\models\dao\ReportPromoteDateDao;
use common\utils\NginxParser;
use yii\console\Controller;
class ReportController extends Controller
{
    public static $root = "/home/work/openresty/nginx/logs";

    public function actionDate(){
        date_default_timezone_set('PRC');
        $date = date("Y-m-d", strtotime("-1 days"));
        $yes = date("Ymd", strtotime("-1 days"));
        $path = self::$root."/beauty-api.log.".$yes;
        $ng = new NginxParser();
        for($i = 0 ; $i < 24 ; $i++){
            if($i <10 ){
                $realPath = $path."0".$i."0000";
            }else{
                $realPath = $path.$i."0000";
            }
            if(file_exists($realPath)){
                $ng->parserDate($realPath);
            }
        }
        $ng->updateUvCount();
        ReportDateDao::createOrUpdate($date,$ng->mapDate['pv'],$ng->mapDate['uv']);
    }
    public function actionHour(){
        date_default_timezone_set('PRC');
        $date = date("Ymd");
        $date2 = date("Y-m-d");
        $hour = date("H", strtotime("-1 hour"));
        $path = self::$root."/beauty-api.log.".$date;
        if($hour <10 ){
            $realPath = $path."0".$hour."0000";
        }else{
            $realPath = $path.$hour."0000";
        }
        if(file_exists($realPath)){
            $ng = new NginxParser();
            $ng->parserHour($realPath);
            if($ng->map){
                ReportHourDao::updateMap($date2, $hour, $ng->map);
            }
        }
    }
    public function actionHourDate(){
        date_default_timezone_set('PRC');
        $date = date("Y-m-d", strtotime("-0 days"));
        $yes = date("Ymd", strtotime("-0 days"));
        $path = self::$root."/beauty-api.log.".$yes;
        for($i = 0 ; $i < 24 ; $i++){
            if($i <10 ){
                $realPath = $path."0".$i."0000";
            }else{
                $realPath = $path.$i."0000";
            }
            if(file_exists($realPath)){
                $ng = new NginxParser();
                $ng->parserHour($realPath);
                if($ng->map){
                    ReportHourDao::updateMap($date, $i, $ng->map);
                }
            }
        }
    }
    public function actionPromoteDate(){
        date_default_timezone_set('PRC');
        $date = date("Y-m-d", strtotime("-1 days"));
        $yes = date("Ymd", strtotime("-1 days"));
        $path = self::$root."/beauty-api.log.".$yes;
        $ng = new NginxParser();
        for($i = 0 ; $i < 24 ; $i++){
            if($i <10 ){
                $realPath = $path."0".$i."0000";
            }else{
                $realPath = $path.$i."0000";
            }
            if(file_exists($realPath)){
                $ng->parserSourceDate($realPath);
            }
        }
        ReportPromoteDateDao::updateMap($date,$ng->map);
    }

    public function actionMinute(){
        date_default_timezone_set('PRC');
        $date = date("Ymd");
        $path = self::$root."/beauty-api.log.".$date;
        $ng = new NginxParser();
        for($i = 0 ; $i < 24 ; $i++){
            if($i <10 ){
                $realPath = $path."0".$i."0000";
            }else{
                $realPath = $path.$i."0000";
            }
            if(file_exists($realPath)){
                $ng->parserMinute($realPath);
            }
        }
        $ng->parserMinute(self::$root."/beauty-api.log");
        echo $ng->pv."\n";
        ReportMinuteDao::create($ng->pv);

    }
}