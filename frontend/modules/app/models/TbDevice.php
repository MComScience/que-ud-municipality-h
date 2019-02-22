<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_device".
 *
 * @property int $device_id
 * @property string $device_name
 */
class TbDevice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_device';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_name'], 'required'],
            [['device_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'device_id' => 'Device ID',
            'device_name' => 'Device Name',
        ];
    }
}
