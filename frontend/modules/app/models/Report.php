<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 17/11/2561
 * Time: 17:09
 */
namespace frontend\modules\app\models;

use yii\base\Model;

class Report extends Model {
    public $from_date;
    public $to_date;
    public $times;
    public $date_range;
    public $user;

    public function rules()
    {
        return [
            [['from_date', 'from_date','times','date_range', 'user'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'from_date' => 'วันที่',
            'to_date' => 'ถึงวันที่',
            'times' => 'ช่วงเวลา',
            'user' => 'User'
        ];
    }
}