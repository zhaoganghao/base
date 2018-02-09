<?php

namespace common\models\dao;

use common\models\ReportHour;

class ReportHourDao extends ReportHour
{

    public static function updateMap($date, $hour, $map)
    {
        foreach ($map as $key => $value){
                self::createOrUpdate($date, $hour, $key, $value);
        }
    }
    public static function createOrUpdate($date, $hour,$url, $pv)
    {
        $data = self::find()->where(['date' => $date, 'url' => $url,'hour' => $hour])->one();
        if(!$data){
            $data = new self();
            $data->date = $date;
            $data->hour = $hour;
            $data->url = $url;
        }
        $data->pv = $pv;
        return $data->save();
    }

    public static function getPromoteByHour($date)
    {
        $sql = " select * from report_hour where `date` = '$date' order by  url,`hour`  asc ";
        return \Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function getApiByDate($startDate, $endDate)
    {
        $sql = " select date,url,sum(pv) as pv from report_hour where `date` >= '$startDate' and `date` <= '$endDate'  group  by date,url order by url desc";
        return \Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function getHourPvByDate($date)
    {
        $sql = " select hour,sum(pv) as pv from report_hour where `date` = '$date' group  by hour order by  url,`hour`  asc ";
        return \Yii::$app->db->createCommand($sql)->queryAll();
    }
}
