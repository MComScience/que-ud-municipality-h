<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_service_group".
 *
 * @property int $service_group_id รหัสกลุ่มบริการ
 * @property string $service_group_name ชื่อกลุ่มบริการ
 * @property int $service_group_status สถานะ
 */
class TbServiceGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_service_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_group_name', 'service_group_status'], 'required'],
            [['service_group_status'], 'integer'],
            [['service_group_name'], 'string', 'max' => 255],
            ['service_group_name', 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'service_group_id' => Yii::t('frontend', 'Service Group ID'),
            'service_group_name' => Yii::t('frontend', 'Service Group Name'),
            'service_group_status' => Yii::t('frontend', 'Service Group Status'),
        ];
    }

    public function getServices()
    {
        return $this->hasMany(TbService::className(), ['service_group_id' => 'service_group_id']);
    }
}
