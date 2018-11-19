<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_rating".
 *
 * @property int $rating_id
 * @property int $rating_value คะแนน
 * @property int $user_id ผู้ให้บริการ
 * @property string $created_at วันที่บันทึก
 */
class TbRating extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_rating';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rating_value', 'user_id', 'created_at'], 'required'],
            [['rating_value', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rating_id' => Yii::t('frontend', 'Rating ID'),
            'rating_value' => Yii::t('frontend', 'Rating Value'),
            'user_id' => Yii::t('frontend', 'User ID'),
            'created_at' => Yii::t('frontend', 'Created At'),
        ];
    }
}
