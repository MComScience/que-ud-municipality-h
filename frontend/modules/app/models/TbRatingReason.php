<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_rating_reason".
 *
 * @property int $rating_id
 * @property int $rating_value คะแนน
 * @property int $reason_value เหตุผล
 * @property int $user_id ผู้ให้บริการ
 * @property string $created_at วันที่บันทึก
 */
class TbRatingReason extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_rating_reason';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rating_value', 'reason_value', 'user_id', 'created_at'], 'required'],
            [['rating_value', 'reason_value', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rating_id' => 'Rating ID',
            'rating_value' => 'Rating Value',
            'reason_value' => 'Reason Value',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }
}
