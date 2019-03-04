<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_service".
 *
 * @property int $service_id รหัสบริการ
 * @property string $service_name ชื่อบริการ
 * @property int $service_group_id รหัสกลุ่มบริการ
 * @property int $print_template_id แบบการพิมพ์บัตรคิว
 * @property int $print_copy_qty จำนวนพิมพ์/ครั้ง
 * @property string $service_prefix ตัวอักษร/ตัวเลข นำหน้าคิว
 * @property int $service_numdigit จำนวนหลักหมายเลขคิว
 * @property string $service_status สถานะคิว
 */
class TbService extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_name', 'print_template_id', 'service_prefix', 'service_numdigit', 'service_status', 'print_copy_qty'], 'required'],
            [['service_group_id', 'print_template_id', 'print_copy_qty', 'service_numdigit'], 'integer'],
            [['service_name'], 'string', 'max' => 255],
            [['service_prefix', 'service_status'], 'string', 'max' => 10],
            //['service_prefix', 'match', 'pattern' => '/^[a-z]\w*$/i']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'service_id' => Yii::t('frontend', 'Service ID'),
            'service_name' => Yii::t('frontend', 'Service Name'),
            'service_group_id' => Yii::t('frontend', 'Service Group ID'),
            'print_template_id' => Yii::t('frontend', 'Print Template ID'),
            'print_copy_qty' => Yii::t('frontend', 'Print Copy Qty'),
            'service_prefix' => Yii::t('frontend', 'Service Prefix'),
            'service_numdigit' => Yii::t('frontend', 'Service Numdigit'),
            'service_status' => Yii::t('frontend', 'Service Status'),
        ];
    }

    public function getServiceGroup()
    {
        return $this->hasOne(TbServiceGroup::className(), ['service_group_id' => 'service_group_id']);
    }
}
