<?php

namespace frontend\modules\app\controllers;

use frontend\modules\app\models\TbCaller;
use frontend\modules\app\models\TbCallerData;
use frontend\modules\app\models\TbDisplay;
use frontend\modules\app\models\TbQue;
use frontend\modules\app\models\TbQueData;
use frontend\modules\app\models\TbTicket;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use Yii;
use yii\base\Model;
use common\models\MultipleModel;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;
use frontend\modules\app\classes\AppQuery;
use frontend\modules\app\models\TbService;
use frontend\modules\app\models\TbServiceGroup;
use frontend\modules\app\models\TbCounterService;
use frontend\modules\app\models\TbCounterServiceType;
use frontend\modules\app\models\TbSoundStation;
use frontend\modules\app\models\TbServiceProfile;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use frontend\modules\app\traits\ModelTrait;
use homer\utils\CoreUtility;

class SettingsController extends \yii\web\Controller
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
                    'delete-service-group' => ['post'],
                    'delete-ticket' => ['post'],
                    'delete-counter-service' => ['post'],
                    'delete-sound' => ['post'],
                    'delete-sound-station' => ['post'],
                    'delete-service-profile' => ['post'],
                    'delete-display' => ['post'],
                    'delete-range-time' => ['post']
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'file-upload' => [
                'class' => UploadAction::className(),
                'deleteRoute' => 'file-delete',
            ],
            'file-delete' => [
                'class' => DeleteAction::className()
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionServiceGroup()
    {
        return $this->render('index',[
            'title' => 'Service Group',
        ]);
    }

    public function actionCounter()
    {
        return $this->render('index',[
            'title' => 'Counter Service',
        ]);
    }

    public function actionSoundStation()
    {
        return $this->render('index',[
            'title' => 'Sound Station',
        ]);
    }

    public function actionServiceProfile()
    {
        return $this->render('index',[
            'title' => 'Service Profile',
        ]);
    }

    public function actionTicket()
    {
        return $this->render('index',[
            'title' => 'Ticket',
        ]);
    }

    public function actionDisplay()
    {
        return $this->render('index',[
            'title' => 'Display',
        ]);
    }

    public function actionReset()
    {
        return $this->render('index',[
            'title' => 'Reset Queue',
        ]);
    }

    public function actionDataServiceGroup()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $data = AppQuery::getDataServiceGroup();
            return ['data' => $data];
        } else {
            throw new BadRequestHttpException(\Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    public function actionDataCounterService()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $data = AppQuery::getDataCounterService();
            return ['data' => $data];
        } else {
            throw new BadRequestHttpException(\Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    public function actionDataSoundStation()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data = AppQuery::getDataSoundStation();
            return ['data' => $data];
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    public function actionDataServiceProfile()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data = AppQuery::getDataServiceProfile();
            return ['data' => $data];
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    public function actionDataTicket()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data = AppQuery::getDataTicket();
            return ['data' => $data];
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    public function actionDataDisplay()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data = AppQuery::getDataDisplay();
            return ['data' => $data];
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'The system could not process your request. Please check and try again.'));
        }
    }

    public function actionDeleteServiceGroup($id, $service_id = null)
    {
        $request = Yii::$app->request;
        if ($service_id != null) {
            TbService::findOne($service_id)->delete();
        }
        if (TbService::find()->where(['service_group_id' => $id])->count() == 0) {
            TbServiceGroup::findOne($id)->delete();
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['service-group']);
        }
    }

    public function actionDeleteCounterService($counter_service_type_id, $counter_service_id = null)
    {
        $request = Yii::$app->request;
        if ($counter_service_id != null) {
            $this->findModelCounterService($counter_service_id)->delete();
        }

        if (TbCounterService::find()->where(['counter_service_type_id' => $counter_service_type_id])->count() == 0) {
            $this->findModelCounterServiceType($counter_service_type_id)->delete();
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['counter-service']);
        }
    }

    public function actionDeleteSoundStation($id)
    {
        $request = Yii::$app->request;
        $this->findModelSoundStation($id)->delete();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['sound-station']);
        }
    }

    public function actionDeleteServiceProfile($id)
    {
        $request = Yii::$app->request;
        $this->findModelServiceProfile($id)->delete();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['service-profile']);
        }
    }

    public function actionDeleteTicket($id)
    {
        $request = Yii::$app->request;
        $this->findModelTicket($id)->delete();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['ticket']);
        }
    }

    public function actionDeleteDisplay($id)
    {
        $request = Yii::$app->request;
        $this->findModelDisplay($id)->delete();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['display']);
        }
    }


    public function actionCreateServiceGroup()
    {
        $request = Yii::$app->request;
        $model = new TbServiceGroup();
        $modelServices = [new TbService()];
        $title = 'บันทึกกลุ่มบริการ';

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => $title,
                    'content' => $this->renderAjax('_form_service_group', [
                        'model' => $model,
                        'modelServices' => (empty($modelServices)) ? [new TbService()] : $modelServices,
                    ]),
                    'footer' => '',
                ];
            } elseif ($model->load($request->post())) {
                $oldIDs = ArrayHelper::map($modelServices, 'service_id', 'service_id');
                $modelServices = MultipleModel::createMultiple(TbService::classname(), $modelServices, 'service_id');
                MultipleModel::loadMultiple($modelServices, $request->post());
                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelServices, 'service_id', 'service_id')));

                // validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelServices) && $valid;
                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $model->service_group_status = 1; // default value
                        if ($flag = $model->save(false)) {
                            if (!empty($deletedIDs)) {
                                TbService::deleteAll(['service_id' => $deletedIDs]);
                            }
                            foreach ($modelServices as $modelService) {
                                $modelService->service_group_id = $model['service_group_id'];
                                if (!($flag = $modelService->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return [
                                'title' => $title,
                                'content' => '<span class="text-success">บันทึกสำเร็จ!</span>',
                                'footer' => Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]),
                                'status' => 200,
                            ];
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                    }
                } else {
                    return [
                        'title' => $title,
                        'content' => $this->renderAjax('_form_service_group', [
                            'model' => $model,
                            'modelServices' => (empty($modelServices)) ? [new TbService()] : $modelServices,
                        ]),
                        'footer' => '',
                        'validate' => ArrayHelper::merge(ActiveForm::validateMultiple($modelServices), ActiveForm::validate($model)),
                        'status' => 422,
                    ];
                }
            } else {
                return [
                    'title' => $title,
                    'content' => $this->renderAjax('_form_service_group', [
                        'model' => $model,
                        'modelServices' => (empty($modelServices)) ? [new TbService()] : $modelServices,
                    ]),
                    'footer' => '',
                    'validate' => ArrayHelper::merge(ActiveForm::validateMultiple($modelServices), ActiveForm::validate($model)),
                    'status' => 422,
                ];
            }
        } else {
            throw new MethodNotAllowedHttpException('method not allowed.');
        }
    }

    public function actionUpdateServiceGroup($id, $service_id = null)
    {
        $request = Yii::$app->request;
        $model = $this->findModelServiceGroup($id);
        $modelServices = TbService::find()->where(['service_group_id' => $id, 'service_id' => $service_id])->all();
        $title = 'บันทึกกลุ่มบริการ';

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => $title,
                    'content' => $this->renderAjax('_form_service_group', [
                        'model' => $model,
                        'modelServices' => (empty($modelServices)) ? [new TbService()] : $modelServices,
                    ]),
                    'footer' => '',
                ];
            } elseif ($model->load($request->post())) {
                $oldIDs = ArrayHelper::map($modelServices, 'service_id', 'service_id');
                $modelServices = MultipleModel::createMultiple(TbService::classname(), $modelServices, 'service_id');
                MultipleModel::loadMultiple($modelServices, $request->post());
                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelServices, 'service_id', 'service_id')));

                // validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelServices) && $valid;
                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            if (!empty($deletedIDs)) {
                                //TbService::deleteAll(['service_id' => $deletedIDs]);
                            }
                            foreach ($modelServices as $modelService) {
                                $modelService->service_group_id = $id;
                                if (!($flag = $modelService->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return [
                                'title' => $title,
                                'content' => '<span class="text-success">บันทึกสำเร็จ!</span>',
                                'footer' => Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]),
                                'status' => 200,
                            ];
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                    }
                } else {
                    return [
                        'title' => $title,
                        'content' => $this->renderAjax('_form_service_group', [
                            'model' => $model,
                            'modelServices' => (empty($modelServices)) ? [new TbService()] : $modelServices,
                        ]),
                        'footer' => '',
                        'validate' => ArrayHelper::merge(ActiveForm::validateMultiple($modelServices), ActiveForm::validate($model)),
                        'status' => 422,
                    ];
                }
            } else {
                return [
                    'title' => $title,
                    'content' => $this->renderAjax('_form_service_group', [
                        'model' => $model,
                        'modelServices' => (empty($modelServices)) ? [new TbService()] : $modelServices,
                    ]),
                    'footer' => '',
                    'validate' => ArrayHelper::merge(ActiveForm::validateMultiple($modelServices), ActiveForm::validate($model)),
                    'status' => 422,
                ];
            }
        } else {
            throw new MethodNotAllowedHttpException('method not allowed.');
        }
    }

    public function actionCreateCounterService()
    {
        $request = Yii::$app->request;
        $model = new TbCounterServiceType();
        $modelCounterServices = [new TbCounterService()];

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "บันทึกจุดบริการ",
                    'content' => $this->renderAjax('_form_counter_service', [
                        'model' => $model,
                        'modelCounterServices' => (empty($modelCounterServices)) ? [new TbCounterService()] : $modelCounterServices,
                    ]),
                    'footer' => ''
                ];
            } elseif ($model->load($request->post())) {
                $oldIDs = ArrayHelper::map($modelCounterServices, 'counter_service_id', 'counter_service_id');
                $modelCounterServices = MultipleModel::createMultiple(TbCounterService::classname(), $modelCounterServices, 'counter_service_id');
                MultipleModel::loadMultiple($modelCounterServices, $request->post());
                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelCounterServices, 'counter_service_id', 'counter_service_id')));

                // validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelCounterServices) && $valid;
                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            if (!empty($deletedIDs)) {
                                TbCounterService::deleteAll(['counter_service_id' => $deletedIDs]);
                            }
                            foreach ($modelCounterServices as $modelCounterService) {
                                $modelCounterService->counter_service_type_id = $model['counter_service_type_id'];
                                if (!($flag = $modelCounterService->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return [
                                'title' => "บันทึกจุดบริการ",
                                'content' => '<span class="text-success">บันทึกสำเร็จ!</span>',
                                'footer' => Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]),
                                'status' => 200
                            ];
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                    }
                } else {
                    return [
                        'title' => "บันทึกจุดบริการ",
                        'content' => $this->renderAjax('_form_counter_service', [
                            'model' => $model,
                            'modelCounterServices' => (empty($modelCounterServices)) ? [new TbCounterService()] : $modelCounterServices,
                        ]),
                        'footer' => '',
                        'validate' => ArrayHelper::merge(ActiveForm::validateMultiple($modelCounterServices), ActiveForm::validate($model)),
                        'status' => 422
                    ];
                }
            } else {
                return [
                    'title' => "บันทึกจุดบริการ",
                    'content' => $this->renderAjax('_form_counter_service', [
                        'model' => $model,
                        'modelCounterServices' => (empty($modelCounterServices)) ? [new TbCounterService()] : $modelCounterServices,
                    ]),
                    'footer' => '',
                    'validate' => ArrayHelper::merge(ActiveForm::validateMultiple($modelCounterServices), ActiveForm::validate($model)),
                    'status' => 422
                ];
            }
        } else {
            throw new MethodNotAllowedHttpException('method not allowed.');
        }
    }

    public function actionUpdateCounterService($id, $counter_service_id = null)
    {
        $request = Yii::$app->request;
        $model = $this->findModelCounterServiceType($id);
        $modelCounterServices = TbCounterService::find()->where(['counter_service_type_id' => $id, 'counter_service_id' => $counter_service_id])->all();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "บันทึกจุดบริการ",
                    'content' => $this->renderAjax('_form_counter_service', [
                        'model' => $model,
                        'modelCounterServices' => (empty($modelCounterServices)) ? [new TbCounterService()] : $modelCounterServices,
                    ]),
                    'footer' => ''
                ];
            } elseif ($model->load($request->post())) {
                $oldIDs = ArrayHelper::map($modelCounterServices, 'counter_service_id', 'counter_service_id');
                $modelCounterServices = MultipleModel::createMultiple(TbCounterService::classname(), $modelCounterServices, 'counter_service_id');
                MultipleModel::loadMultiple($modelCounterServices, $request->post());
                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelCounterServices, 'counter_service_id', 'counter_service_id')));

                // validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelCounterServices) && $valid;
                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            if (!empty($deletedIDs)) {
                                //TbCounterService::deleteAll(['counter_service_id' => $deletedIDs]);
                            }
                            foreach ($modelCounterServices as $modelCounterService) {
                                $modelCounterService->counter_service_type_id = $model['counter_service_type_id'];
                                if (!($flag = $modelCounterService->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return [
                                'title' => "บันทึกจุดบริการ",
                                'content' => '<span class="text-success">บันทึกสำเร็จ!</span>',
                                'footer' => Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]),
                                'status' => 200
                            ];
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                    }
                } else {
                    return [
                        'title' => "บันทึกจุดบริการ",
                        'content' => $this->renderAjax('_form_counter_service', [
                            'model' => $model,
                            'modelCounterServices' => (empty($modelCounterServices)) ? [new TbCounterService()] : $modelCounterServices,
                        ]),
                        'footer' => '',
                        'validate' => ArrayHelper::merge(ActiveForm::validateMultiple($modelCounterServices), ActiveForm::validate($model)),
                        'status' => 422
                    ];
                }
            } else {
                return [
                    'title' => "บันทึกจุดบริการ",
                    'content' => $this->renderAjax('_form_counter_service', [
                        'model' => $model,
                        'modelCounterServices' => (empty($modelCounterServices)) ? [new TbCounterService()] : $modelCounterServices,
                    ]),
                    'footer' => '',
                    'validate' => ArrayHelper::merge(ActiveForm::validateMultiple($modelCounterServices), ActiveForm::validate($model)),
                    'status' => 422
                ];
            }
        } else {
            throw new MethodNotAllowedHttpException('method not allowed.');
        }
    }

    public function actionCreateSoundStation()
    {
        $request = Yii::$app->request;
        $model = new TbSoundStation();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "บันทึกโปรแกรมเสียง",
                    'content' => $this->renderAjax('_form_sound_station', [
                        'model' => $model,
                    ]),
                    'footer' => '',

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                $data = $request->post('TbSoundStation');
                TbCounterService::updateAll(['sound_station_id' => null],['sound_station_id' => $model['sound_station_id']]);
                if (is_array($data['counter_service_id'])){
                    foreach ($data['counter_service_id'] as $counter){
                        $modelCounter = $this->findModelCounterService($counter);
                        $modelCounter->sound_station_id = $model['sound_station_id'];
                        $modelCounter->save();
                    }
                }
                return [
                    'title' => "บันทึกโปรแกรมเสียง",
                    'content' => '<span class="text-success">บันทึกสำเร็จ!</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]),
                    'status' => 200,
                    'url' => Url::to(['update', 'id' => $model['sound_station_id']]),
                ];
            } else {
                return [
                    'title' => "บันทึกโปรแกรมเสียง",
                    'content' => $this->renderAjax('_form_sound_station', [
                        'model' => $model,
                    ]),
                    'footer' => '',
                    'status' => 422,
                    'validate' => ActiveForm::validate($model),
                ];
            }
        } else {
            throw new MethodNotAllowedHttpException('method not allowed.');
        }
    }

    public function actionUpdateSoundStation($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModelSoundStation($id);
        $model->counter_service_id = CoreUtility::string2Array($model['counter_service_id']);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "บันทึกโปรแกรมเสียง",
                    'content' => $this->renderAjax('_form_sound_station', [
                        'model' => $model,
                    ]),
                    'footer' => '',

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                $data = $request->post('TbSoundStation');
                TbCounterService::updateAll(['sound_station_id' => null],['sound_station_id' => $model['sound_station_id']]);
                if (is_array($data['counter_service_id'])){
                    foreach ($data['counter_service_id'] as $counter){
                        $modelCounter = $this->findModelCounterService($counter);
                        $modelCounter->sound_station_id = $model['sound_station_id'];
                        $modelCounter->save();
                    }
                }
                return [
                    'title' => "บันทึกโปรแกรมเสียง",
                    'content' => '<span class="text-success">บันทึกสำเร็จ!</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]),
                    'status' => 200,
                    'url' => Url::to(['update', 'id' => $model['sound_station_id']]),
                ];
            } else {
                return [
                    'title' => "บันทึกโปรแกรมเสียง",
                    'content' => $this->renderAjax('_form_sound_station', [
                        'model' => $model,
                    ]),
                    'footer' => '',
                    'status' => 422,
                    'validate' => ActiveForm::validate($model),
                ];
            }
        } else {
            throw new MethodNotAllowedHttpException('method not allowed.');
        }
    }

    public function actionCreateServiceProfile()
    {
        $request = Yii::$app->request;
        $model = new TbServiceProfile();
        $model->scenario = 'create';

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "บันทึกเซอร์วิสโปรไฟล์",
                    'content' => $this->renderAjax('_form_service_profile', [
                        'model' => $model,
                    ]),
                    'footer' => '',

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'title' => "บันทึกเซอร์วิสโปรไฟล์",
                    'content' => '<span class="text-success">บันทึกสำเร็จ!</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]),
                    'status' => 200,
                ];
            } else {
                return [
                    'title' => "บันทึกเซอร์วิสโปรไฟล์",
                    'content' => $this->renderAjax('_form_service_profile', [
                        'model' => $model,
                    ]),
                    'footer' => '',
                    'status' => 422,
                    'validate' => ActiveForm::validate($model),
                ];
            }
        } else {
            throw new MethodNotAllowedHttpException('method not allowed.');
        }
    }

    public function actionUpdateServiceProfile($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModelServiceProfile($id);
        $model->scenario = 'update';
        $model->service_id = CoreUtility::string2Array($model['service_id']);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "บันทึกเซอร์วิสโปรไฟล์",
                    'content' => $this->renderAjax('_form_service_profile', [
                        'model' => $model,
                    ]),
                    'footer' => '',

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'title' => "บันทึกเซอร์วิสโปรไฟล์",
                    'content' => '<span class="text-success">บันทึกสำเร็จ!</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]),
                    'status' => 200,
                ];
            } else {
                return [
                    'title' => "บันทึกเซอร์วิสโปรไฟล์",
                    'content' => $this->renderAjax('_form_service_profile', [
                        'model' => $model,
                    ]),
                    'footer' => '',
                    'status' => 422,
                    'validate' => ActiveForm::validate($model),
                ];
            }
        } else {
            throw new MethodNotAllowedHttpException('method not allowed.');
        }
    }

    public function actionCreateTicket()
    {
        $request = Yii::$app->request;
        $model = new TbTicket();
        $model->ticket_status = $model->isNewRecord ? 1 : $model['ticket_status'];

        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash(\homer\sweetalert2\SweetAlert2::TYPE_SUCCESS, [
                [
                    'title' => 'Successfully',
                    'text' => 'บันทึกสำเร็จ',
                    'timer' => 2000,
                    'showConfirmButton' => false
                ],
            ]);
            return $this->redirect(['update-ticket', 'id' => $model['ticket_ids']]);
        } else {
            return $this->render('_form_ticket', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdateTicket($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModelTicket($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "จัดการข้อมูลบัตรคิว",
                    'content' => $this->renderAjax('_form_ticket', [
                        'model' => $model,
                    ]),
                    'footer' => '',

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'title' => "จัดการข้อมูลบัตรคิว",
                    'content' => '<span class="text-success">บันทึกสำเร็จ!</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]),
                    'status' => 200,
                    'url' => Url::to(['update', 'id' => $model['ticket_ids']]),
                ];
            } else {
                return [
                    'title' => "จัดการข้อมูลบัตรคิว",
                    'content' => $this->renderAjax('_form_ticket', [
                        'model' => $model,
                    ]),
                    'footer' => '',
                    'status' => 422,
                    'validate' => ActiveForm::validate($model),
                ];
            }
        } else {
            return $this->render('_form_ticket', [
                'model' => $model,
            ]);
        }
    }

    public function actionCopyDisplay($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $oldModel = $this->findModelDisplay($id);
            $newModel = new TbDisplay();
            $newModel->isNewRecord = true;
            $newModel->attributes = $oldModel->attributes;
            if ($newModel->save()) {
                return $newModel;
            } else {
                throw new HttpException(422, Json::encode($newModel->errors));
            }
        } else {
            throw new MethodNotAllowedHttpException('method not allowed.');
        }
    }

    public function actionCreateDisplay()
    {
        $request = Yii::$app->request;
        $model = new TbDisplay();
        $style = '';

        if ($model->load($request->post()) && !$request->isAjax) {
            $data = $request->post('TbDisplay', []);
            $style = strip_tags($data['display_css']);
            return $this->render('_form_display', [
                'model' => $model,
                'style' => $style,
            ]);
        } elseif ($request->isAjax && $model->load($request->post()) && $model->save()) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'url' => Url::to(['update-display', 'id' => $model['display_ids']]),
                'model' => $model,
            ];
        } elseif ($request->isGet) {
            $model->display_css = $model->isNewRecord ? $model->defaultCss : $model->display_css;
            $style = strip_tags($model['display_css']);
            return $this->render('_form_display', [
                'model' => $model,
                'style' => $style,
            ]);
        } else {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'validate' => ActiveForm::validate($model),
            ];
        }
    }

    public function actionUpdateDisplay($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModelDisplay($id);
        $model->service_id = CoreUtility::string2Array($model['service_id']);
        $model->counter_service_id = CoreUtility::string2Array($model['counter_service_id']);
        $style = '';

        if ($model->load($request->post()) && !$request->isAjax) {
            $data = $request->post('TbDisplay', []);
            $style = strip_tags($data['display_css']);
            return $this->render('_form_display', [
                'model' => $model,
                'style' => $style,
            ]);
        } elseif ($request->isAjax && $model->load($request->post()) && $model->save()) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'url' => Url::to(['update-display', 'id' => $model['display_ids']]),
                'model' => $model,
            ];
        } elseif ($request->isGet) {
            $style = strip_tags($model['display_css']);
            return $this->render('_form_display', [
                'model' => $model,
                'style' => $style,
            ]);
        } else {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'validate' => ActiveForm::validate($model),
            ];
        }
    }

    public function actionResetDataQue()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $modelQueOld = TbQue::find()->all();
                foreach ($modelQueOld as $model) {
                    $modelQue = new TbQueData();
                    $modelQue->setAttributes($model->attributes);
                    if (!$modelQue->save()) {
                        throw new HttpException(422, Json::encode($modelQue->errors));
                    }
                }

                $modelCallerOld = TbCaller::find()->all();
                foreach ($modelCallerOld as $model) {
                    $modelCaller = new TbCallerData();
                    $modelCaller->setAttributes($model->attributes);
                    if (!$modelCaller->save()) {
                        throw new HttpException(422, Json::encode($modelCaller->errors));
                    }
                }

                TbQue::deleteAll();
                TbCaller::deleteAll();

                $transaction->commit();
                return 'Success!';
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            throw new MethodNotAllowedHttpException('method not allowed.');
        }
    }

}
