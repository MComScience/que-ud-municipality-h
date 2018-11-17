<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 15/11/2561
 * Time: 13:48
 */
namespace frontend\modules\app\models;

use Yii;
use yii\base\Model;

class ModelScan extends Model
{
    public $card_id;
    public $service_group_id;

    public function rules()
    {
        return [
            [['service_group_id'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'card_id' => Yii::t('frontend', 'Card ID'),
            'service_group_id' => Yii::t('frontend', 'Service Group'),
        ];
    }
}