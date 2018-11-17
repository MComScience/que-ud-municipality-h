<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_que_data".
 *
 * @property int $ids
 * @property int $que_ids
 * @property string $que_num หมายเลขคิว
 * @property string $que_hn HN
 * @property string $id_card รหัสบัตรประชาชน
 * @property string $pt_name ชื่อ-สกุล ผู้ป่วย
 * @property int $service_id ประเภทบริการ
 * @property int $service_group_id กลุ่มบริการ
 * @property string $created_at วันที่บันทึก
 * @property string $updated_at วันที่แก้ไข
 * @property int $created_by ผู้บันทึก
 * @property int $updated_by ผู้แก้ไข
 * @property int $que_status_id สถานะ
 * @property string $status_times เวลาสถานะ
 *
 * @property TbQueStatus $queStatus
 */
class TbQueData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_que_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['que_ids', 'que_num', 'que_hn', 'pt_name', 'service_id', 'service_group_id', 'created_at', 'created_by', 'que_status_id'], 'required'],
            [['que_ids', 'service_id', 'service_group_id', 'created_by', 'updated_by', 'que_status_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['status_times'], 'string'],
            [['que_num', 'que_hn'], 'string', 'max' => 100],
            [['id_card'], 'string', 'max' => 50],
            [['pt_name'], 'string', 'max' => 255],
            [['que_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => TbQueStatus::className(), 'targetAttribute' => ['que_status_id' => 'que_status_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ids' => Yii::t('frontend', 'Ids'),
            'que_ids' => Yii::t('frontend', 'Que Ids'),
            'que_num' => Yii::t('frontend', 'Que Num'),
            'que_hn' => Yii::t('frontend', 'Que Hn'),
            'id_card' => Yii::t('frontend', 'Id Card'),
            'pt_name' => Yii::t('frontend', 'Pt Name'),
            'service_id' => Yii::t('frontend', 'Service ID'),
            'service_group_id' => Yii::t('frontend', 'Service Group ID'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
            'created_by' => Yii::t('frontend', 'Created By'),
            'updated_by' => Yii::t('frontend', 'Updated By'),
            'que_status_id' => Yii::t('frontend', 'Que Status ID'),
            'status_times' => Yii::t('frontend', 'Status Times'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQueStatus()
    {
        return $this->hasOne(TbQueStatus::className(), ['que_status_id' => 'que_status_id']);
    }
}
