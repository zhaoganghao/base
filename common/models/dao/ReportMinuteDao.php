<?php

namespace common\models\dao;

use common\models\ReportMinute;

class ReportMinuteDao extends ReportMinute
{

    public static function create($pv)
    {
        $model = new self();
        $model->date = date("Y-m-d H:i:s");
        $model->pv = $pv;
        $model->save();
    }

    public static function getByDate($startDate, $endDate)
    {
        $sql = " select * from report_minute where `date` >= '$startDate' and `date` <= '$endDate' order by  `date`  desc ";
        return \Yii::$app->db->createCommand($sql)->queryAll();
    }

}
