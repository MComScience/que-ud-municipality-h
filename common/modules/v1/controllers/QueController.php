<?php
namespace common\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\web\HttpException;
use yii\helpers\Json;
use frontend\modules\app\traits\ModelTrait;
use homer\utils\CoreUtility;
use frontend\modules\app\models\TbCaller;
use frontend\modules\app\models\TbQue;
use frontend\modules\app\components\SoundComponent;

class QueController extends ActiveController
{
    use ModelTrait;

    public $modelClass = 'frontend\modules\app\models\TbQue';

    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create'], $actions['update'], $actions['delete'], $actions['view']);

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],
        ];
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'view' => ['get'],
                'create' => ['post'],
                'update' => ['put'],
                'delete' => ['delete'],
                'call-waiting' => ['post'],
                'recall' => ['post'],
                'hold' => ['post'],
                'end' => ['post'],
                'call-hold' => ['post'],
                'end-hold' => ['post']
            ],
        ];
        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
            'options',
            'call-waiting',
            'recall',
            'hold',
            'end',
            'call-hold',
            'end-hold',
        ];
        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'view', 'create', 'update', 'delete'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => [
                        'index', 'view', 'create', 'update', 'delete',
                    ],
                    'roles' => ['@'],
                ],
                [
                    'allow' => true,
                    'actions' => ['call-waiting','recall','hold','end','call-hold','end-hold'],
                    'roles' => ['?'],
                ]
            ],
        ];
        return $behaviors;
    }

    public function actionCallWaiting()
    {
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        $modelProfile = $this->findModelServiceProfile($bodyParams['profileId']);
        $modelQue = $this->findModelQue($bodyParams['queId']);
        $attributes = [
            'service_profile_id' => $modelProfile['service_profile_id'],
            'service_id' => CoreUtility::string2Array($modelProfile['service_id']),
            'counter_service_type_id' => $modelProfile['counter_service_type_id'],
            'counter_service_id' => $bodyParams['counterId'],
        ];
        $formData = $attributes;
        $modelProfile->setAttributes($attributes);
        $modelCaller = new TbCaller();
        $modelCaller->setAttributes([
            'que_ids' => $bodyParams['queId'],
            'service_profile_id' => $formData['service_profile_id'],
            'counter_service_id' => $formData['counter_service_id'],
            'call_timestp' => \Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s'),
            'call_status_id' => TbCaller::STATUS_CALLING #เรียกคิว
        ]);
        $modelQue->setAttributes([
            'que_status_id' => TbQue::STATUS_CALL,
        ]);
        $data = (new \yii\db\Query())
            ->select([
                'tb_que.que_ids',
                'tb_que.que_num',
                'tb_que.que_hn',
                'tb_que.pt_name',
                'tb_que.service_id',
                'tb_que.service_group_id',
                'tb_que.created_at',
                'tb_que.que_status_id',
                'DATE_FORMAT(DATE_ADD(tb_que.created_at, INTERVAL 543 YEAR),\'%H:%i:%s\') AS created_time',
                'tb_que_status.que_status_name',
                'tb_service.service_name',
                'tb_service_group.service_group_name'
            ])
            ->from('tb_que')
            ->innerJoin('tb_que_status', 'tb_que.que_status_id = tb_que_status.que_status_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->innerJoin('tb_service_group', 'tb_service_group.service_group_id = tb_service.service_group_id')
            ->where([
                'tb_que.que_status_id' => 1, 
                'tb_que.service_id' => $modelQue['service_id'],
                'tb_que.que_ids' => $modelQue['que_ids'],
            ])
            ->one();
        if ($modelCaller->save() && $modelQue->save()) {
            $modelCounterService = $this->findModelCounterService($formData['counter_service_id']);
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);
            $responseData = [
                'data' => $data,
                'modelQue' => $modelQue,
                'modelProfile' => $modelProfile,
                'formData' => $formData,
                'modelCaller' => $modelCaller,
                'modelCounterService' => $modelCounterService,
                'media_files' => $this->getMediaFiles($modelQue['que_num'], $formData['counter_service_id']),
            ];

            return [
                'success' => true,
                'status' => $response->statusCode,
                'data' => $responseData,
            ];
        } else {
            throw new HttpException(422, Json::encode($modelCaller->errors));
        }
    }

    public function actionRecall()
    {
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        $modelProfile = $this->findModelServiceProfile($bodyParams['profileId']);
        $modelCaller = $this->findModelCaller($bodyParams['callerId']);
        $modelQue = $this->findModelQue($modelCaller['que_ids']);
        $attributes = [
            'service_profile_id' => $modelProfile['service_profile_id'],
            'service_id' => CoreUtility::string2Array($modelProfile['service_id']),
            'counter_service_type_id' => $modelProfile['counter_service_type_id'],
            'counter_service_id' => $bodyParams['counterId'],
        ];
        $formData = $attributes;
        $modelProfile->setAttributes($attributes);
        $modelCaller->setAttributes([
            'counter_service_id' => $formData['counter_service_id'],
            'call_timestp' => \Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s'),
            'call_status_id' => TbCaller::STATUS_CALLING,
        ]);
        $modelQue->setAttributes([
            'que_status_id' => TbQue::STATUS_CALL,
        ]);
        
        $data = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.que_ids',
                'tb_caller.service_profile_id',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_caller_status.caller_status_name',
                'tb_que.que_num',
                'tb_que.pt_name',
                'tb_que.created_at',
                'DATE_FORMAT(DATE_ADD(tb_que.created_at, INTERVAL 543 YEAR),\'%H:%i:%s\') AS created_time',
                'tb_counter_service.counter_service_name',
                'tb_counter_service.counter_service_call_number',
                'tb_service.service_name',
                'tb_service_group.service_group_name',
                'tb_que_status.que_status_name',
                'tb_service_profile.service_profile_name'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_caller_status', 'tb_caller_status.caller_status_id = tb_caller.call_status_id')
            ->innerJoin('tb_que', 'tb_que.que_ids = tb_caller.que_ids')
            ->innerJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller.counter_service_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->innerJoin('tb_service_group', 'tb_service_group.service_group_id = tb_service.service_group_id')
            ->innerJoin('tb_que_status', 'tb_que.que_status_id = tb_que_status.que_status_id')
            ->innerJoin('tb_service_profile', 'tb_service_profile.service_profile_id = tb_caller.service_profile_id')
            ->where([
                'tb_caller_status.caller_status_id' => [1, 3],
                'tb_caller.counter_service_id' => $formData['counter_service_id'],
                'tb_caller.service_profile_id' => $formData['service_profile_id'],
                'tb_que.que_ids' => $modelQue['que_ids'],
                'tb_que.que_status_id' => 2,
            ])
            ->orderBy(['tb_caller.call_timestp' => SORT_ASC])
            ->one();
        if($modelCaller->save() && $modelQue->save()){
            $modelCounterService = $this->findModelCounterService($formData['counter_service_id']);
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);
            $responseData = [
                'data' => $data,
                'modelQue' => $modelQue,
                'modelProfile' => $modelProfile,
                'formData' => $formData,
                'modelCaller' => $modelCaller,
                'modelCounterService' => $modelCounterService,
                'media_files' => $this->getMediaFiles($modelQue['que_num'],$formData['counter_service_id']),
            ];
            return [
                'success' => true,
                'status' => $response->statusCode,
                'data' => $responseData,
            ];
        } else {
            throw new HttpException(422, Json::encode($modelCaller->errors));
        }
    }

    public function actionHold()
    {
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        $modelProfile = $this->findModelServiceProfile($bodyParams['profileId']);
        $modelCaller = $this->findModelCaller($bodyParams['callerId']);
        $modelQue = $this->findModelQue($modelCaller['que_ids']);
        $attributes = [
            'service_profile_id' => $modelProfile['service_profile_id'],
            'service_id' => CoreUtility::string2Array($modelProfile['service_id']),
            'counter_service_type_id' => $modelProfile['counter_service_type_id'],
            'counter_service_id' => $bodyParams['counterId'],
        ];
        $formData = $attributes;
        $modelCaller->setAttributes([
            'call_status_id' => TbCaller::STATUS_HOLD,
        ]);
        $modelQue->setAttributes([
            'que_status_id' => TbQue::STATUS_HOLD,
        ]);

        $data = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.que_ids',
                'tb_caller.service_profile_id',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_caller_status.caller_status_name',
                'tb_que.que_num',
                'tb_que.pt_name',
                'tb_que.created_at',
                'DATE_FORMAT(DATE_ADD(tb_que.created_at, INTERVAL 543 YEAR),\'%H:%i:%s\') AS created_time',
                'tb_counter_service.counter_service_name',
                'tb_counter_service.counter_service_call_number',
                'tb_service.service_name',
                'tb_service_group.service_group_name',
                'tb_que_status.que_status_name',
                'tb_service_profile.service_profile_name'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_caller_status', 'tb_caller_status.caller_status_id = tb_caller.call_status_id')
            ->innerJoin('tb_que', 'tb_que.que_ids = tb_caller.que_ids')
            ->innerJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller.counter_service_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->innerJoin('tb_service_group', 'tb_service_group.service_group_id = tb_service.service_group_id')
            ->innerJoin('tb_que_status', 'tb_que.que_status_id = tb_que_status.que_status_id')
            ->innerJoin('tb_service_profile', 'tb_service_profile.service_profile_id = tb_caller.service_profile_id')
            ->where([
                'tb_caller_status.caller_status_id' => 2,
                'tb_caller.counter_service_id' => $formData['counter_service_id'],
                'tb_caller.service_profile_id' => $formData['service_profile_id'],
                'tb_que.que_ids' => $modelQue['que_ids'],
                'tb_que.que_status_id' => 3
            ])
            ->orderBy(['tb_caller.call_timestp' => SORT_ASC])
            ->one();
        if($modelCaller->save() && $modelQue->save()){
            $modelCounterService = $this->findModelCounterService($formData['counter_service_id']);
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);
            $responseData = [
                'data' => $data,
                'modelQue' => $modelQue,
                'modelProfile' => $modelProfile,
                'formData' => $formData,
                'modelCaller' => $modelCaller,
                'modelCounterService' => $modelCounterService,
            ];
            return [
                'success' => true,
                'status' => $response->statusCode,
                'data' => $responseData,
            ];
        } else {
            throw new HttpException(422, Json::encode($modelCaller->errors));
        }
    }

    public function actionEnd()
    {
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        $modelProfile = $this->findModelServiceProfile($bodyParams['profileId']);
        $modelCaller = $this->findModelCaller($bodyParams['callerId']);
        $modelQue = $this->findModelQue($modelCaller['que_ids']);
        $attributes = [
            'service_profile_id' => $modelProfile['service_profile_id'],
            'service_id' => CoreUtility::string2Array($modelProfile['service_id']),
            'counter_service_type_id' => $modelProfile['counter_service_type_id'],
            'counter_service_id' => $bodyParams['counterId'],
        ];
        $formData = $attributes;
        $modelCaller->setAttributes([
            'call_status_id' => TbCaller::STATUS_END
        ]);
        $modelQue->setAttributes([
            'que_status_id' => TbQue::STATUS_SUCCESS,
        ]);
        $data = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.que_ids',
                'tb_caller.service_profile_id',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_caller_status.caller_status_name',
                'tb_que.que_num',
                'tb_que.pt_name',
                'tb_que.created_at',
                'DATE_FORMAT(DATE_ADD(tb_que.created_at, INTERVAL 543 YEAR),\'%H:%i:%s\') AS created_time',
                'tb_counter_service.counter_service_name',
                'tb_counter_service.counter_service_call_number',
                'tb_service.service_name',
                'tb_service_group.service_group_name',
                'tb_que_status.que_status_name',
                'tb_service_profile.service_profile_name'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_caller_status', 'tb_caller_status.caller_status_id = tb_caller.call_status_id')
            ->innerJoin('tb_que', 'tb_que.que_ids = tb_caller.que_ids')
            ->innerJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller.counter_service_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->innerJoin('tb_service_group', 'tb_service_group.service_group_id = tb_service.service_group_id')
            ->innerJoin('tb_que_status', 'tb_que.que_status_id = tb_que_status.que_status_id')
            ->innerJoin('tb_service_profile', 'tb_service_profile.service_profile_id = tb_caller.service_profile_id')
            ->where([
                'tb_caller_status.caller_status_id' => [1, 3],
                'tb_caller.counter_service_id' => $formData['counter_service_id'],
                'tb_caller.service_profile_id' => $formData['service_profile_id'],
                'tb_que.que_ids' => $modelQue['que_ids'],
                'tb_que.que_status_id' => 2,
            ])
            ->orderBy(['tb_caller.call_timestp' => SORT_ASC])
            ->one();
        if($modelQue->save() && $modelCaller->save()){
            $modelCounterService = $this->findModelCounterService($formData['counter_service_id']);
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);
            $responseData = [
                'data' => $data,
                'modelQue' => $modelQue,
                'modelProfile' => $modelProfile,
                'formData' => $formData,
                'modelCaller' => $modelCaller,
                'modelCounterService' => $modelCounterService,
            ];

            return [
                'success' => true,
                'status' => $response->statusCode,
                'data' => $responseData,
            ];
        }else {
            throw new HttpException(422, Json::encode($modelCaller->errors));
        }
    }

    public function actionCallHold()
    {
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        $modelProfile = $this->findModelServiceProfile($bodyParams['profileId']);
        $modelCaller = $this->findModelCaller($bodyParams['callerId']);
        $modelQue = $this->findModelQue($modelCaller['que_ids']);
        $attributes = [
            'service_profile_id' => $modelProfile['service_profile_id'],
            'service_id' => CoreUtility::string2Array($modelProfile['service_id']),
            'counter_service_type_id' => $modelProfile['counter_service_type_id'],
            'counter_service_id' => $bodyParams['counterId'],
        ];
        $formData = $attributes;
        $modelProfile->setAttributes($attributes);
        $modelCaller->setAttributes([
            'counter_service_id' => $formData['counter_service_id'],
            'call_timestp' => \Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s'),
            'call_status_id' => TbCaller::STATUS_CALLING,
        ]);
        $modelQue->setAttributes([
            'que_status_id' => TbQue::STATUS_CALL,
        ]);
        
        $data = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.que_ids',
                'tb_caller.service_profile_id',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_caller_status.caller_status_name',
                'tb_que.que_num',
                'tb_que.pt_name',
                'tb_que.created_at',
                'DATE_FORMAT(DATE_ADD(tb_que.created_at, INTERVAL 543 YEAR),\'%H:%i:%s\') AS created_time',
                'tb_counter_service.counter_service_name',
                'tb_counter_service.counter_service_call_number',
                'tb_service.service_name',
                'tb_service_group.service_group_name',
                'tb_que_status.que_status_name',
                'tb_service_profile.service_profile_name'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_caller_status', 'tb_caller_status.caller_status_id = tb_caller.call_status_id')
            ->innerJoin('tb_que', 'tb_que.que_ids = tb_caller.que_ids')
            ->innerJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller.counter_service_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->innerJoin('tb_service_group', 'tb_service_group.service_group_id = tb_service.service_group_id')
            ->innerJoin('tb_que_status', 'tb_que.que_status_id = tb_que_status.que_status_id')
            ->innerJoin('tb_service_profile', 'tb_service_profile.service_profile_id = tb_caller.service_profile_id')
            ->where([
                'tb_caller_status.caller_status_id' => 2,
                'tb_caller.counter_service_id' => $formData['counter_service_id'],
                'tb_caller.service_profile_id' => $formData['service_profile_id'],
                'tb_que.que_ids' => $modelQue['que_ids'],
                'tb_que.que_status_id' => 3
            ])
            ->orderBy(['tb_caller.call_timestp' => SORT_ASC])
            ->one();
        if($modelCaller->save() && $modelQue->save()){
            $modelCounterService = $this->findModelCounterService($formData['counter_service_id']);
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);
            $responseData = [
                'data' => $data,
                'modelQue' => $modelQue,
                'modelProfile' => $modelProfile,
                'formData' => $formData,
                'modelCaller' => $modelCaller,
                'modelCounterService' => $modelCounterService,
                'media_files' => $this->getMediaFiles($modelQue['que_num'],$formData['counter_service_id']),
            ];
            return [
                'success' => true,
                'status' => $response->statusCode,
                'data' => $responseData,
            ];
        } else {
            throw new HttpException(422, Json::encode($modelCaller->errors));
        }
    }

    public function actionEndHold()
    {
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        $modelProfile = $this->findModelServiceProfile($bodyParams['profileId']);
        $modelCaller = $this->findModelCaller($bodyParams['callerId']);
        $modelQue = $this->findModelQue($modelCaller['que_ids']);
        $attributes = [
            'service_profile_id' => $modelProfile['service_profile_id'],
            'service_id' => CoreUtility::string2Array($modelProfile['service_id']),
            'counter_service_type_id' => $modelProfile['counter_service_type_id'],
            'counter_service_id' => $bodyParams['counterId'],
        ];
        $formData = $attributes;
        $modelCaller->setAttributes([
            'call_status_id' => TbCaller::STATUS_END
        ]);
        $modelQue->setAttributes([
            'que_status_id' => TbQue::STATUS_SUCCESS,
        ]);
        $data = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.que_ids',
                'tb_caller.service_profile_id',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_caller_status.caller_status_name',
                'tb_que.que_num',
                'tb_que.pt_name',
                'tb_que.created_at',
                'DATE_FORMAT(DATE_ADD(tb_que.created_at, INTERVAL 543 YEAR),\'%H:%i:%s\') AS created_time',
                'tb_counter_service.counter_service_name',
                'tb_counter_service.counter_service_call_number',
                'tb_service.service_name',
                'tb_service_group.service_group_name',
                'tb_que_status.que_status_name',
                'tb_service_profile.service_profile_name'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_caller_status', 'tb_caller_status.caller_status_id = tb_caller.call_status_id')
            ->innerJoin('tb_que', 'tb_que.que_ids = tb_caller.que_ids')
            ->innerJoin('tb_counter_service', 'tb_counter_service.counter_service_id = tb_caller.counter_service_id')
            ->innerJoin('tb_service', 'tb_service.service_id = tb_que.service_id')
            ->innerJoin('tb_service_group', 'tb_service_group.service_group_id = tb_service.service_group_id')
            ->innerJoin('tb_que_status', 'tb_que.que_status_id = tb_que_status.que_status_id')
            ->innerJoin('tb_service_profile', 'tb_service_profile.service_profile_id = tb_caller.service_profile_id')
            ->where([
                'tb_caller_status.caller_status_id' => 2,
                'tb_caller.counter_service_id' => $formData['counter_service_id'],
                'tb_caller.service_profile_id' => $formData['service_profile_id'],
                'tb_que.que_ids' => $modelQue['que_ids'],
                'tb_que.que_status_id' => 3
            ])
            ->orderBy(['tb_caller.call_timestp' => SORT_ASC])
            ->one();
        if($modelQue->save() && $modelCaller->save()){
            $modelCounterService = $this->findModelCounterService($formData['counter_service_id']);
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);
            $responseData = [
                'data' => $data,
                'modelQue' => $modelQue,
                'modelProfile' => $modelProfile,
                'formData' => $formData,
                'modelCaller' => $modelCaller,
                'modelCounterService' => $modelCounterService,
            ];

            return [
                'success' => true,
                'status' => $response->statusCode,
                'data' => $responseData,
            ];
        }else {
            throw new HttpException(422, Json::encode($modelCaller->errors));
        }
    }

    protected function getMediaFiles($que_number, $counter_service_id)
    {
        $component = \Yii::createObject([
            'class' => SoundComponent::className(),
            'que_number' => $que_number,
            'counter_id' => $counter_service_id,
        ]);
        return $component->getSource();
    }
}