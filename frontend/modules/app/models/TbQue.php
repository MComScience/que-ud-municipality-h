<?php

namespace frontend\modules\app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\components\AutoQueNumber;
use frontend\modules\app\traits\ModelTrait;
use homer\behaviors\CoreMultiValueBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "tb_que".
 *
 * @property int $que_ids
 * @property string $que_num หมายเลขคิว
 * @property string $que_hn HN
 * @property string $pt_name ชื่อ-สกุล ผู้ป่วย
 * @property int $service_id ประเภทบริการ
 * @property int $service_group_id กลุ่มบริการ
 * @property string $created_at วันที่บันทึก
 * @property string $updated_at วันที่แก้ไข
 * @property int $created_by ผู้บันทึก
 * @property int $updated_by ผู้แก้ไข
 * @property int $que_status_id สถานะ
 * @property string $wait_time เวลารอ(ประมาณ)
 * @property string $status_times เวลาสถานะ
 *
 * @property TbQueStatus $queStatus
 */
class TbQue extends \yii\db\ActiveRecord
{
    use ModelTrait;

    const STATUS_WAIT = 1;
    const STATUS_CALL = 2;
    const STATUS_HOLD = 3;
    const STATUS_SUCCESS = 4;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_que';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by','updated_by'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_by'],
                ],
                'value' => Yii::$app->user->isGuest ? null : Yii::$app->user->id
            ],
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => CoreMultiValueBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'que_num',
                ],
                'value' => function ($event) {
                    if(empty($this->que_num)){
                        return $this->generateQnumber();
                    }else{
                        return $event->sender[$event->data];
                    }
                },
            ],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['ticket'] = ['que_hn'];
        $scenarios['create'] = ['service_id', 'service_group_id', 'que_status_id','que_hn','pt_name','que_num'];
        $scenarios['call'] = ['que_status_id'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'service_group_id', 'que_status_id'], 'required'],
            [['service_id', 'service_group_id', 'created_by', 'updated_by', 'que_status_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['status_times'], 'string'],
            [['que_num', 'que_hn'], 'string', 'max' => 100],
            [['pt_name'], 'string', 'max' => 255],
            [['id_card'], 'string', 'max' => 50],
            [['que_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => TbQueStatus::className(), 'targetAttribute' => ['que_status_id' => 'que_status_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'que_ids' => Yii::t('frontend', 'Que Ids'),
            'que_num' => Yii::t('frontend', 'Que Num'),
            'que_hn' => Yii::t('frontend', 'Que Hn'),
            'id_card' => Yii::t('frontend', 'ID Card'),
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

    public function generateQnumber()
    {
        $service = $this->findModelService($this->service_id);
        $queue = ArrayHelper::map($this->find()->where(['service_id' => $this->service_id])->all(), 'que_ids', 'que_num');
        $qnums = [];
        $maxqnum = null;
        $qid = null;
        if (count($queue) > 0) {
            foreach ($queue as $key => $q) {
                $qnums[$key] = preg_replace("/[^0-9\.]/", '', $q);
            }
            $maxqnum = max($qnums);
            $qid = array_search($maxqnum, $qnums);
        }
        $component = \Yii::createObject([
            'class' => AutoQueNumber::className(),
            'prefix' => $service ? $service['service_prefix'] : 'A',
            'number' => ArrayHelper::getValue($queue, $qid, null),
            'digit' => $service ? $service['service_numdigit'] : 3,
        ]);
        return $component->generate();
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if(!$this->isNewRecord && !empty($this->status_times)){
            $status_times = Json::decode($this->status_times);
            $keys = ArrayHelper::getColumn($status_times,'id');
            if(ArrayHelper::isIn($this->que_status_id,$keys)){
                foreach($status_times as $key => $status_time){
                    if($status_time['id'] == $this->que_status_id){
                        $status_times[$key]['time'] = \Yii::$app->formatter->asDate('now', 'php:H:i:s');
                        break;
                    }
                }
                $this->status_times = Json::encode($status_times);
            }else{
                $status_times = ArrayHelper::merge($status_times, [
                    [
                        'id' => $this->que_status_id,
                        'time' => \Yii::$app->formatter->asDate('now', 'php:H:i:s'),
                    ]
                ]);
                $this->status_times = Json::encode($status_times);
            }
        }else{
            $this->status_times = Json::encode([
                [
                    'id' => $this->que_status_id,
                    'time' => \Yii::$app->formatter->asDate('now', 'php:H:i:s'),
                ]
            ]);
        }
        return true;
    }

    public function getTbService()
    {
        return $this->hasOne(TbService::className(), ['service_id' => 'service_id']);
    }
}
