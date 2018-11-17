<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_que_status".
 *
 * @property int $que_status_id
 * @property string $que_status_name ชื่อสถานะ
 *
 * @property TbQue[] $tbQues
 */
class TbQueStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_que_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['que_status_name'], 'required'],
            [['que_status_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'que_status_id' => Yii::t('frontend', 'Que Status ID'),
            'que_status_name' => Yii::t('frontend', 'Que Status Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTbQues()
    {
        return $this->hasMany(TbQue::className(), ['que_status_id' => 'que_status_id']);
    }
}
