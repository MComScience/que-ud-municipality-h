<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_caller_data".
 *
 * @property int $ids
 * @property int $caller_ids running
 * @property int $que_ids รหัสคิว
 * @property int $service_profile_id เซอร์วิสโปรไฟล์
 * @property int $counter_service_id เคาท์เตอร์
 * @property string $call_timestp เวลาเรียก
 * @property int $created_by ผู้เรียก
 * @property string $created_at เวลาบันทึก
 * @property int $updated_by ผู้แก้ไข
 * @property string $updated_at เวลาแก้ไข
 * @property int $call_status_id สถานะ
 */
class TbCallerData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_caller_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['caller_ids', 'que_ids', 'service_profile_id', 'counter_service_id', 'call_timestp', 'created_at', 'updated_at', 'call_status_id'], 'required'],
            [['caller_ids', 'que_ids', 'service_profile_id', 'counter_service_id', 'created_by', 'updated_by', 'call_status_id'], 'integer'],
            [['call_timestp', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ids' => Yii::t('frontend', 'Ids'),
            'caller_ids' => Yii::t('frontend', 'Caller Ids'),
            'que_ids' => Yii::t('frontend', 'Que Ids'),
            'service_profile_id' => Yii::t('frontend', 'Service Profile ID'),
            'counter_service_id' => Yii::t('frontend', 'Counter Service ID'),
            'call_timestp' => Yii::t('frontend', 'Call Timestp'),
            'created_by' => Yii::t('frontend', 'Created By'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_by' => Yii::t('frontend', 'Updated By'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
            'call_status_id' => Yii::t('frontend', 'Call Status ID'),
        ];
    }
}
