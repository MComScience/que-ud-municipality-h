<?php

namespace frontend\modules\app\controllers;

use frontend\modules\app\classes\AppQuery;
use frontend\modules\app\components\SoundComponent;
use frontend\modules\app\models\TbCaller;
use frontend\modules\app\models\TbCounterService;
use frontend\modules\app\models\TbQue;
use frontend\modules\app\models\TbService;
use frontend\modules\app\models\TbServiceProfile;
use frontend\modules\app\models\TbSoundStation;
use homer\utils\CoreUtility;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use frontend\modules\app\traits\ModelTrait;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;

class CallingController extends \yii\web\Controller
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
                    [
                        'allow' => true,
                        'actions' => ['play-sound', 'autoplay-media', 'update-status-called'],
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actionIndex($profile_id = null, $counter_service_id = null)
    {
        $request = \Yii::$app->request;
        $modelProfile = new TbServiceProfile();
        $modelProfile->scenario = 'call';
        $services = [];
        $formData = [];
        if ($profile_id !== null || $counter_service_id !== null) {
            $modelProfile = $this->findModelServiceProfile($profile_id);
            $postdata = $request->post('TbServiceProfile', []);
            $attributes = [
                'service_profile_id' => $modelProfile['service_profile_id'],
                'service_id' => CoreUtility::string2Array($modelProfile['service_id']),
                'counter_service_type_id' => $modelProfile['counter_service_type_id'],
                'counter_service_id' => $counter_service_id,
            ];
            $formData = $attributes;
            $modelProfile->setAttributes($attributes);
            $services = TbService::find()->where(['service_id' => $modelProfile['service_id'], 'service_status' => 1])->asArray()->all();
        }
        return $this->render('index', [
            'modelProfile' => $modelProfile,
            'services' => $services,
            'formData' => $formData
        ]);
    }

    public function actionChildProfile()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $modelProfile = TbServiceProfile::findOne($id);
            if (!$modelProfile) {
                echo Json::encode(['output' => '', 'selected' => '']);
                return;
            }
            $list = TbCounterService::find()->andWhere(['counter_service_type_id' => $modelProfile['counter_service_type_id'], 'counter_service_status' => 1])->asArray()->all();
            $selected = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $counter) {
                    $out[] = ['id' => $counter['counter_service_id'], 'name' => $counter['counter_service_name']];
                    /*if ($i == 0) {
                        $selected = $counter['counter_service_id'];
                    }*/
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected' => $selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
        return;
    }

    public function actionDataQueWait()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (!$request->post('data')) {
                return ['data' => []];
            }
            $data = AppQuery::getDataQueWait($request->post('data'));
            return ['data' => $data];
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    public function actionDataQueCalling()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (!$request->post('data')) {
                return ['data' => []];
            }
            $data = AppQuery::getDataQueCalling($request->post('data'), $request->post('formData'));
            return ['data' => $data];
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    public function actionDataQueHold()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (!$request->post('data')) {
                return ['data' => []];
            }
            $data = AppQuery::getDataQueHold($request->post('data'), $request->post('formData'));
            return ['data' => $data];
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    // เรียกคิว
    public function actionCallWaiting($que_ids)
    {
        $request = Yii::$app->request;
        $modelQue = $this->findModelQue($que_ids);
        $modelQue->scenario = 'call';
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = $request->post();
            $data = $post['data'];
            $modelProfile = $post['modelProfile'];
            $formData = $post['formData'];
            $modelCaller = new TbCaller();
            $modelCaller->setAttributes([
                'que_ids' => $que_ids,
                'service_profile_id' => $formData['service_profile_id'],
                'counter_service_id' => $formData['counter_service_id'],
                'call_timestp' => Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s'),
                'call_status_id' => TbCaller::STATUS_CALLING #เรียกคิว
            ]);
            $modelQue->setAttributes([
                'que_status_id' => TbQue::STATUS_CALL,
            ]);
            if ($modelCaller->save() && $modelQue->save()) {
                $modelCounterService = $this->findModelCounterService($formData['counter_service_id']);
                return [
                    'data' => $data,
                    'modelQue' => $modelQue,
                    'modelProfile' => $modelProfile,
                    'formData' => $formData,
                    'modelCaller' => $modelCaller,
                    'modelCounterService' => $modelCounterService,
                    'media_files' => $this->getMediaFiles($modelQue['que_num'], $modelCounterService['counter_service_id']),
                ];
            } else {
                throw new HttpException(422, Json::encode($modelCaller->errors));
            }
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    //เสร็จสิ้น
    public function actionEndWaiting($que_ids)
    {
        $request = Yii::$app->request;
        $modelQue = $this->findModelQue($que_ids);
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = $request->post();
            $data = $post['data'];
            $modelProfile = $post['modelProfile'];
            $formData = $post['formData'];
            $modelCaller = new TbCaller();
            $modelCaller->setAttributes([
                'que_ids' => $que_ids,
                'service_profile_id' => $formData['service_profile_id'],
                'counter_service_id' => $formData['counter_service_id'],
                'call_timestp' => Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s'),
                'call_status_id' => TbCaller::STATUS_END #เรียกคิว
            ]);
            $modelQue->setAttributes([
                'que_status_id' => TbQue::STATUS_SUCCESS,
            ]);
            if ($modelCaller->save() && $modelQue->save()) {
                $modelCounterService = $this->findModelCounterService($formData['counter_service_id']);
                return [
                    'data' => $data,
                    'modelQue' => $modelQue,
                    'modelProfile' => $modelProfile,
                    'formData' => $formData,
                    'modelCaller' => $modelCaller,
                    'modelCounterService' => $modelCounterService,
                    'media_files' => $this->getMediaFiles($modelQue['que_num'], $modelCounterService['counter_service_id']),
                ];
            } else {
                throw new HttpException(422, Json::encode($modelCaller->errors));
            }
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    #เรียกคิวซ้ำ
    public function actionRecall($caller_ids)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = $request->post();
            $data = $post['data'];
            $modelProfile = $post['modelProfile'];
            $formData = $post['formData'];
            $modelQue = $this->findModelQue($data['que_ids']);
            $modelCaller = $this->findModelCaller($caller_ids);
            $modelCaller->setAttributes([
                'counter_service_id' => $formData['counter_service_id'],
                'call_timestp' => Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s'),
                'call_status_id' => TbCaller::STATUS_CALLING,
            ]);
            $modelQue->setAttributes([
                'que_status_id' => TbQue::STATUS_CALL,
            ]);
            if ($modelCaller->save() && $modelQue->save()) {
                $modelCounterService = $this->findModelCounterService($formData['counter_service_id']);
                return [
                    'data' => $data,
                    'modelQue' => $modelQue,
                    'modelProfile' => $modelProfile,
                    'formData' => $formData,
                    'modelCaller' => $modelCaller,
                    'modelCounterService' => $modelCounterService,
                    'media_files' => $this->getMediaFiles($modelQue['que_num'], $modelCounterService['counter_service_id']),
                ];
            } else {
                throw new HttpException(422, Json::encode($modelCaller->errors));
            }
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    #พักคิว
    public function actionHold($caller_ids)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = $request->post();
            $data = $post['data'];
            $modelProfile = $post['modelProfile'];
            $formData = $post['formData'];
            $modelQue = $this->findModelQue($data['que_ids']);
            $modelCaller = $this->findModelCaller($caller_ids);
            $modelCaller->setAttributes([
                'call_status_id' => TbCaller::STATUS_HOLD,
            ]);
            $modelQue->setAttributes([
                'que_status_id' => TbQue::STATUS_HOLD,
            ]);
            if ($modelCaller->save() && $modelQue->save()) {
                $modelCounterService = $this->findModelCounterService($formData['counter_service_id']);
                return [
                    'data' => $data,
                    'modelQue' => $modelQue,
                    'modelProfile' => $modelProfile,
                    'formData' => $formData,
                    'modelCaller' => $modelCaller,
                    'modelCounterService' => $modelCounterService,
                ];
            } else {
                throw new HttpException(422, Json::encode($modelCaller->errors));
            }
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    #เสร็จสิ้น
    public function actionEnd($caller_ids)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = $request->post();
            $data = $post['data'];
            $modelProfile = $post['modelProfile'];
            $formData = $post['formData'];
            $modelQue = $this->findModelQue($data['que_ids']);
            $modelCaller = $this->findModelCaller($caller_ids);
            $modelCaller->setAttributes([
                'call_status_id' => TbCaller::STATUS_END
            ]);
            $modelQue->setAttributes([
                'que_status_id' => TbQue::STATUS_SUCCESS,
            ]);
            if ($modelQue->save() && $modelCaller->save()) {
                $modelCounterService = $this->findModelCounterService($formData['counter_service_id']);
                return [
                    'data' => $data,
                    'modelQue' => $modelQue,
                    'modelProfile' => $modelProfile,
                    'formData' => $formData,
                    'modelCaller' => $modelCaller,
                    'modelCounterService' => $modelCounterService,
                ];
            } else {
                throw new HttpException(422, Json::encode($modelCaller->errors));
            }
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    public function actionPlaySound()
    {
        $request = Yii::$app->request;
        $model = new TbSoundStation();
        if ($model->load($request->post())) {
            $data = $request->post('TbSoundStation', []);
            if (isset($data['sound_station_id']) && !empty($data['sound_station_id'])) {
                $model = $this->findModelSoundStation($data['sound_station_id']);
                $model->counter_service_id = CoreUtility::string2Array($model['counter_service_id']);
            }
        }
        return $this->render('play-sound', ['model' => $model]);
    }

    public function actionAutoplayMedia()
    {
        $request = Yii::$app->request;
        $response = [];
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data = $request->post();
            if ($data) {
                $query = TbCaller::find()->where(['call_status_id' => TbCaller::STATUS_CALLING, 'counter_service_id' => $data['counter_service_id']])->orderBy(['call_timestp' => SORT_ASC])->all();
                foreach ($query as $item) {
                    $modelCounterService = $this->findModelCounterService($item['counter_service_id']);
                    $response[] = [
                        'data' => [],
                        'modelQue' => $item->que,
                        'modelProfile' => $item->serviceProfile,
                        'formData' => [],
                        'modelCaller' => $item,
                        'modelCounterService' => $modelCounterService,
                        'media_files' => $this->getMediaFiles($item->que->que_num, $item['counter_service_id']),
                    ];
                }
            }
            return $response;
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    public function actionUpdateStatusCalled($caller_ids)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $modelCaller = $this->findModelCaller($caller_ids);
            $modelCaller->call_status_id = TbCaller::STATUS_CALLED;
            $modelCaller->save(false);
            return $modelCaller;
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
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

    public function actionOnMobile()
    {
        return $this->render('_call_on_mobile');
    }

    public function actionDataProfileOptions()
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $profiles = ArrayHelper::map(TbServiceProfile::find()->where(['service_profile_status' => 1])->asArray()->all(), 'service_profile_id', 'service_profile_name');
        return $profiles;
    }

    public function actionDataCounterOptions($profileId)
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $modelProfile = $this->findModelServiceProfile($profileId);
        $counters = ArrayHelper::map(TbCounterService::find()->where(['counter_service_type_id' => $modelProfile['counter_service_type_id'],'counter_service_status' => 1])->asArray()->all(),'counter_service_id','counter_service_name');
        return $counters;
    }

    public function actionDataCallingOptions($profileId, $counterId)
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;

        $modelProfile = $this->findModelServiceProfile($profileId);
        $attributes = [
            'service_profile_id' => $modelProfile['service_profile_id'],
            'service_id' => CoreUtility::string2Array($modelProfile['service_id']),
            'counter_service_type_id' => $modelProfile['counter_service_type_id'],
            'counter_service_id' => $counterId,
        ];
        $formData = $attributes;
        $modelProfile->setAttributes($attributes);
        return [
            'modelProfile' => $modelProfile,
            'formData' => $formData
        ];
    }

}
