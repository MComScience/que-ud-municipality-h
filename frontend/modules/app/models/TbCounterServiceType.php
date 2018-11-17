<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_counter_service_type".
 *
 * @property int $counter_service_type_id รหัสประเภทบริการ
 * @property string $counter_service_type_name ประเภทบริการ
 *
 * @property TbCounterService[] $tbCounterServices
 */
class TbCounterServiceType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_counter_service_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['counter_service_type_name'], 'required'],
            [['counter_service_type_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'counter_service_type_id' => Yii::t('frontend', 'Counter Service Type ID'),
            'counter_service_type_name' => Yii::t('frontend', 'Counter Service Type Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTbCounterServices()
    {
        return $this->hasMany(TbCounterService::className(), ['counter_service_type_id' => 'counter_service_type_id']);
    }
}
