<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 8/10/2561
 * Time: 9:26
 */
namespace common\components;

use yii\base\Component;

class DateConvert extends Component
{
    public static function convertToDatabase($date, $thaiFormat = true, $separator = '/'){
        $result = '';
        if(!empty($date) && $date != '0000-00-00'){
            $arr = explode($separator, $date);
            $y = $thaiFormat ? ($arr[2] - 543) : $arr[2];
            $m = $arr[1];
            $d = $arr[0];
            $result = "$y-$m-$d";
        }
        return $result;
    }

    public static function convertToDisplay($date, $thaiFormat = true){
        $result = '';
        if(!empty($date) && $date != '0000-00-00'){
            $arr = explode("-", $date);
            $y = $thaiFormat ? ($arr[0] + 543) : $arr[0];
            $m = $arr[1];
            $d = $arr[2];
            $result = "$d/$m/$y";
        }
        return $result;
    }
}