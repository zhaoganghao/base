<?php

namespace common\models\dao;


use common\models\ReportPromoteDate;

class ReportPromoteDateDao extends ReportPromoteDate
{
    public static function updateMap($date, $map)
    {
        foreach ($map as $key => $value){
            foreach ($value as $key2 => $value2){
                self::createOrUpdate($date, $key, $key2, $value2);
            }
        }
    }
    public static function createOrUpdate($date, $url, $source, $pv)
    {


        $data = self::find()->where(['date' => $date, 'url' => $url, 'promote' => $source])->one();
        if(!$data){
            $data = new self();
            $data->date = $date;
            $data->url = $url;
            $data->promote = $source;
        }
        $data->pv = $pv;
        return $data->save();
    }

    public static function getPromoteByHour($date)
    {

        $sql = " select * from report_promote_date where `date` = '$date' order by  url,`hour`  asc ";
        return \Yii::$app->db->createCommand($sql)->queryAll();
    }

    public static function getPromoteByDate($startDate, $endDate)
    {
        $sql = " select date,promote,sum(pv) as pv from report_promote_date where `date` >= '$startDate' and `date` <= '$endDate'  group  by date,promote order by promote desc";
        return \Yii::$app->db->createCommand($sql)->queryAll();
    }
}
