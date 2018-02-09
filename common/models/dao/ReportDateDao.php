<?php

namespace common\models\dao;


use common\models\ReportDate;

class ReportDateDao extends ReportDate
{
    public static function createOrUpdate($date, $pv, $uv)
    {
        $data = self::find()->where(['date' => $date])->one();
        if(!$data){
            $data = new self();
            $data->date = $date;
        }
        $data->pv = $pv;
        $data->uv = $uv;
        return $data->save();
    }

    public static function getByDate($startDate, $endDate)
    {
        $sql = " select * from report_date where `date` >= '$startDate' and `date` <= '$endDate' order by  `date`  desc ";
        return \Yii::$app->db->createCommand($sql)->queryAll();
    }
}
