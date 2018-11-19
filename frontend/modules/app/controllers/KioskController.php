<?php

namespace frontend\modules\app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use frontend\modules\app\models\TbServiceGroup;
use frontend\modules\app\models\TbCaller;
use frontend\modules\app\classes\AppQuery;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;
use frontend\modules\app\traits\ModelTrait;
use frontend\modules\app\models\ModelScan;
use common\modules\v1\jwt\JWT;
use frontend\modules\app\models\TbQue;
use frontend\modules\app\models\TbService;
use kartik\widgets\ActiveForm;
use Probe\ProviderFactory;
use yii\helpers\Url;

class KioskController extends \yii\web\Controller
{
    use ModelTrait;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete-que' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex($service_group_id = null)
    {
        $services = [];
        $provider = ProviderFactory::create();
        $model = new ModelScan();
        $modelServiceGroup = new TbServiceGroup();
        $model->service_group_id = $service_group_id;
        if ($service_group_id !== null) {
            $modelServiceGroup = $this->findModelServiceGroup($service_group_id);
            $services = TbService::find()->where(['service_group_id' => $service_group_id, 'service_status' => 1])->all();
        }
        return $this->render('index', [
            'model' => $model,
            'services' => $services,
            'modelServiceGroup' => $modelServiceGroup,
            'provider' => $provider,
        ]);
    }

    public function actionDataQueList()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data = AppQuery::getDataQueList();
            return ['data' => $data];
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    public function actionDeleteQue($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModelQue($id);
        $model->delete();
        TbCaller::deleteAll(['que_ids' => $id]);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true];
        } else {
            return $this->redirect(['ticket']);
        }
    }

    public function actionUpdateQue($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModelQue($id);
        $model->scenario = 'create';
        $oldservice = $model['service_id'];

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "แก้ไขรายการคิว #" . $model['que_num'],
                    'content' => $this->renderAjax('_form_que', [
                        'model' => $model,
                    ]),
                    'footer' => '',
                ];
            } else if ($model->load($request->post())) {
                $data = $request->post('TbQue', []);
                if ($oldservice != $data['service_id']) {
                    $model->que_num = $model->generateQnumber();
                }
                if ($model->save()) {
                    return [
                        'title' => "แก้ไขรายการคิว #" . $model['que_num'],
                        'content' => '<span class="text-success">บันทึกสำเร็จ!</span>',
                        'footer' => Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]),
                        'status' => '200',
                    ];
                } else {
                    return [
                        'title' => "แก้ไขรายการคิว #" . $model['que_num'],
                        'content' => $this->renderAjax('_form_que', [
                            'model' => $model,
                        ]),
                        'footer' => '',
                        'status' => 'validate',
                        'validate' => ActiveForm::validate($model),
                    ];
                }
            } else {
                return [
                    'title' => "แก้ไขรายการคิว #" . $model['que_num'],
                    'content' => $this->renderAjax('_form_que', [
                        'model' => $model,
                    ]),
                    'footer' => '',
                    'status' => 'validate',
                    'validate' => ActiveForm::validate($model),
                ];
            }
        } else {
            throw new MethodNotAllowedHttpException('method not allowed.');
        }
    }

    public function actionChildServiceGroup()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = TbService::find()->andWhere(['service_group_id' => $id])->asArray()->all();
            $selected = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $data) {
                    $out[] = ['id' => $data['service_id'], 'name' => $data['service_name']];
                    if ($i == 0) {
                        $selected = $data['service_id'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected' => $selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionRegister()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $profile = $request->post('profile');
            $modelServiceGroup = $request->post('modelServiceGroup');
            $modelService = $this->findModelService($request->post('service_id'));
            $token = $profile['data']['id_token'];
            $data = static::decodeToken($token);
            $modelQue = new TbQue();
            $modelQue->scenario = 'create';
            if ($data) {
                $dataProfile = (array)$data;
                $modelQue->pt_name = $dataProfile['th_fullname'];
                $modelQue->service_id = $modelService['service_id'];
                $modelQue->service_group_id = $modelServiceGroup['service_group_id'];
                $modelQue->id_card = $dataProfile['citizen_id'];
                $modelQue->que_status_id = TbQue::STATUS_WAIT;
                if ($modelQue->save()) {
                    return [
                        'success' => true,
                        'message' => 'บันทึกสำเร็จ!',
                        'modelQue' => $modelQue,
                        'modelService' => $modelService,
                        'modelServiceGroup' => $modelServiceGroup,
                        'url' => Url::to(['/app/kiosk/print-ticket', 'que_ids' => $modelQue['que_ids']])
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => ActiveForm::validate($modelQue),
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาด!',
                ];
            }
        }
    }

    public function actionPrintTicket($que_ids){
        $formatter = \Yii::$app->formatter;
        $modelQue = $this->findModelQue($que_ids);
        $modelService = $this->findModelService($modelQue['service_id']);
        $modelTicket = $this->findModelTicket($modelService['print_template_id']);
        $year = $formatter->asDate('now', 'php:Y') + 543;
        $count = (new \yii\db\Query())
            ->select(['Count(tb_que.que_ids) as count'])
            ->from('tb_que')
            ->where([
                'tb_que.service_group_id' => $modelQue['service_group_id'],
                'tb_que.created_at' => $modelQue['created_at'],
                'tb_que.que_status_id' => 1
            ])
            ->count();

        $template = strtr($modelTicket->template, [
            '{hos_name_th}' => $modelTicket->hos_name_th,
            '{q_hn}' => $modelQue->que_hn,
            '{pt_name}' => $modelQue->pt_name,
            '{q_num}' => $modelQue->que_num,
            '{service_name}' => $modelQue->tbService->service_name,
            '{sec_name}' => '',
            '{time}' => $formatter->asDate('now', 'php:d M '.substr($year, 2)).' '.$formatter->asDate('now','php:H:i').' น.',
            '{user_print}' => Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->profile->name,
            '{qwaiting}' => $count,
            '/imgs/udh-logo.png' => $modelTicket->logo_path ? $modelTicket->logo_base_url.'/'.$modelTicket->logo_path : '/imgs/udh-logo.png'
        ]);
        return $this->renderAjax('print-ticket',[
            'modelQue' => $modelQue,
            'modelTicket' => $modelTicket,
            'template' => $template,
            'modelService' => $modelService,
        ]);
    }

    public static function decodeToken($token)
    {
        $secret = Yii::$app->params['jwtSecretCode'];
        try {
            $decoded = JWT::decode($token, $secret, [static::getAlgo()]);
            return $decoded;
        } catch (\Exception $e) {
            return false;
        }

    }

    public static function getAlgo()
    {
        return 'HS256';
    }

}