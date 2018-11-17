<?php

namespace frontend\modules\app\models;

use homer\behaviors\CoreMultiValueBehavior;
use homer\utils\CoreUtility;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tb_display".
 *
 * @property int $display_ids
 * @property string $display_name ชื่อจอแสดงผล
 * @property int $page_length จำนวนแถวที่แสดง
 * @property string $text_th_left ข้อความส่วนหัวตาราง 1
 * @property string $text_th_right ข้อความส่วนหัวตาราง 2
 * @property string $text_hold ข้อความตารางพักคิว
 * @property string $color_th_left
 * @property string $color_th_right
 * @property string $display_css
 * @property string $background_color สีพื้นหลัง
 * @property int $display_status สถานะ
 * @property string $counter_service_id เคาท์เตอร์
 * @property string $service_id ประเภทบริการ
 * @property int $que_column_length จำนวนเลขคิวที่แสดง/แถว
 * @property int $show_que_hold แสดงคิวที่เรียกไปแล้ว
 * @property string $text_top_left ข้อความ 1
 * @property string $text_top_center ข้อความ 2
 * @property string $text_top_right ข้อความ 3
 * @property string $text_th_lastq_left ข้อความคิวล่าสุด 1
 * @property string $text_th_lastq_right ข้อความคิวล่าสุด 2
 */
class TbDisplay extends \yii\db\ActiveRecord
{
    public $color_code;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_display';
    }

    public function behaviors()
    {
        return [
            [
                'class' => CoreMultiValueBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['service_id','counter_service_id'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['service_id','counter_service_id'],
                ],
                'value' => function ($event) {
                    return CoreUtility::array2String($event->sender[$event->data]);
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['display_name', 'page_length', 'display_status', 'counter_service_id', 'service_id', 'que_column_length', 'text_top_left', 'text_top_center', 'text_top_right'], 'required'],
            [['page_length', 'display_status', 'que_column_length', 'show_que_hold'], 'integer'],
            [['display_css', 'text_top_left', 'text_top_center', 'text_top_right'], 'string'],
            [['counter_service_id', 'service_id'],'safe'],
            [['display_name', 'text_th_left', 'text_th_right'], 'string', 'max' => 255],
            [['text_hold', 'color_th_left', 'color_th_right', 'background_color', 'text_th_lastq_left', 'text_th_lastq_right'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'display_ids' => Yii::t('frontend', 'Display Ids'),
            'display_name' => Yii::t('frontend', 'Display Name'),
            'page_length' => Yii::t('frontend', 'Page Length'),
            'text_th_left' => Yii::t('frontend', 'Text Th Left'),
            'text_th_right' => Yii::t('frontend', 'Text Th Right'),
            'text_hold' => Yii::t('frontend', 'Text Hold'),
            'color_th_left' => Yii::t('frontend', 'Color Th Left'),
            'color_th_right' => Yii::t('frontend', 'Color Th Right'),
            'display_css' => Yii::t('frontend', 'Display Css'),
            'background_color' => Yii::t('frontend', 'Background Color'),
            'display_status' => Yii::t('frontend', 'Display Status'),
            'counter_service_id' => Yii::t('frontend', 'Counter Service'),
            'service_id' => Yii::t('frontend', 'Service Type'),
            'que_column_length' => Yii::t('frontend', 'Que Column Length'),
            'show_que_hold' => Yii::t('frontend', 'Show Que Hold'),
            'text_top_left' => Yii::t('frontend', 'Text Top Left'),
            'text_top_center' => Yii::t('frontend', 'Text Top Center'),
            'text_top_right' => Yii::t('frontend', 'Text Top Right'),
            'text_th_lastq_left' => Yii::t('frontend', 'Text Th Lastq Left'),
            'text_th_lastq_right' => Yii::t('frontend', 'Text Th Lastq Right'),
        ];
    }

    public function getDefaultCss(){
        return '<pre class=" css" data-pbcklang="css" data-pbcktabsize="4">
        /*สีพื้นหลัง*/
        body {
            background-color: #204d74;
        }
        table#tb-display thead tr th.th-right {
            border-top: 5px solid #ffffff !important;
            border-bottom: 5px solid #ffffff !important;
            border-right: 5px solid #ffffff !important;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            /*font-size: 40px !important;*/
        }
        
        table#tb-display thead tr th.th-left {
            border-top: 5px solid #ffffff !important;
            border-bottom: 5px solid #ffffff !important;
            border-left: 5px solid #ffffff !important;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            /*font-size: 40px !important;*/
        }
        
        table#tb-display tbody tr td.td-left {
            border-top: 5px solid #ffffff !important;
            border-bottom: 5px solid #ffffff !important;
            border-left: 5px solid #ffffff !important;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            /*font-size: 40px !important;*/
        }
        
        table#tb-display tbody tr td.td-right {
            border-top: 5px solid #ffffff !important;
            border-bottom: 5px solid #ffffff !important;
            border-right: 5px solid #ffffff !important;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            /*font-size: 40px !important;*/
        }
        /*background-color = สีพื้นหลังตารางส่วนหัว*/
        /*color = สีตัวอักษร*/
        table#tb-display thead tr {
            width: 50%;
            border-radius: 15px;
            border: 5px solid white;
            background-color: #000000;
            color: #ffffff;
            font-weight: bold;
        }
        /*background-color = สีพื้นหลังแถว*/
        /*color = สีตัวอักษร*/
        table#tb-display tbody tr {
            width: 50%;
            border-radius: 15px;
            border: 5px solid white;
            background-color: #666666;
            color: #ffffff;
            font-weight: bold;
        }
        table#tb-hold tbody tr td.td-left{
            width: 50%;
            border-top: 5px solid #ffffff !important;
            border-bottom: 5px solid #ffffff !important;
            border-right: 5px solid #ffffff !important;
            border-left: 5px solid #ffffff !important;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            background-color: #666666;
            color: #ffffff;
            vertical-align: middle;
            /*font-size: 40px !important;*/
        }
        
        table#tb-hold tbody tr td.td-right{
            width: 50%;
            border-top: 0px solid white !important;
            color: yellow;
            font-weight: bold;
            vertical-align: middle;
            /*font-size: 40px !important;*/
        }
        /* คิวล่าสุด */
        table#tb-lastque thead tr th.th-right {
            border-top: 5px solid #ffffff !important;
            border-bottom: 5px solid #ffffff !important;
            border-right: 5px solid #ffffff !important;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            /*font-size: 40px !important;*/
        }
        
        table#tb-lastque thead tr th.th-left {
            border-top: 5px solid #ffffff !important;
            border-bottom: 5px solid #ffffff !important;
            border-left: 5px solid #ffffff !important;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            /*font-size: 40px !important;*/
        }
        table#tb-lastque tbody tr td.td-left {
            border-top: 5px solid #ffffff !important;
            border-bottom: 5px solid #ffffff !important;
            border-left: 5px solid #ffffff !important;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            /*font-size: 40px !important;*/
        }
        
        table#tb-lastque tbody tr td.td-right {
            border-top: 5px solid #ffffff !important;
            border-bottom: 5px solid #ffffff !important;
            border-right: 5px solid #ffffff !important;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            /*font-size: 40px !important;*/
        }
        /*color = สีตัวอักษร*/
        table#tb-lastque thead tr {
            width: 50%;
            border-radius: 15px;
            border: 5px solid white;
            background-color: #000000;
            color: #ffffff;
            font-weight: bold;
        }
        table#tb-lastque tbody tr {
            width: 50%;
            border-radius: 15px;
            border: 5px solid white;
            background-color: #666666;
            color: #ffffff;
            font-weight: bold;
        }</pre>';
    }

    public function getServiceData(){
        $query = Yii::$app->db->createCommand('SELECT
                tb_service.service_id,
                CONCAT(tb_service_group.service_group_name,\' : \',tb_service.service_prefix,\': \', tb_service.service_name) as service_name
                FROM
                tb_service
                INNER JOIN tb_service_group ON tb_service_group.service_group_id = tb_service.service_group_id
                WHERE
                tb_service.service_status = 1')->queryAll();
        return ArrayHelper::map($query,'service_id','service_name');
    }

    public function getCounterServiceData(){
        $query = Yii::$app->db->createCommand('SELECT
                tb_counter_service_type.counter_service_type_id,
                tb_counter_service_type.counter_service_type_name
                FROM
                tb_counter_service_type')->queryAll();
        return ArrayHelper::map($query,'counter_service_type_id','counter_service_type_name');
    }
}
