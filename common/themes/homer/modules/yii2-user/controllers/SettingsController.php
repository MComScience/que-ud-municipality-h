<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 11:32
 */
namespace homer\user\controllers;

use homer\user\models\Profile;
use Yii;
use dektrium\user\controllers\SettingsController as BaseSettingsController;
use trntv\filekit\actions\UploadAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use dektrium\user\models\SettingsForm;
use Intervention\Image\ImageManagerStatic;
use trntv\filekit\actions\DeleteAction;
use homer\sweetalert2\SweetAlert2;

class SettingsController extends BaseSettingsController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post'],
                    'delete'     => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['profile', 'account', 'networks', 'disconnect', 'delete','upload-avatar','delete-avatar'],
                        'roles'   => ['@'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['confirm'],
                        'roles'   => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'upload-avatar' => [
                'class' => UploadAction::className(),
                'deleteRoute' => 'delete-avatar',
                'on afterSave' => function ($event) {
                    $file = $event->file;
                    $cache = Yii::$app->cache;
                    $key = 'avatar' . Yii::$app->user->id;
                    $cache->delete($key);
                    $img = ImageManagerStatic::make($file->read())->fit(215, 215);
                    $file->put($img->encode());
                },
            ],
            'delete-avatar' => [
                'class' => DeleteAction::className(),
            ],
        ];
    }

    public function actionProfile()
    {
        $model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());

        if ($model == null) {
            $model = \Yii::createObject(Profile::className());
            $model->link('user', \Yii::$app->user->identity);
        }

        $event = $this->getProfileEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash(SweetAlert2::TYPE_SUCCESS, [
                [
                    'title' => 'Successfully',
                    'text' => \Yii::t('user', 'Your profile has been updated'),
                    'timer' => 2000,
                    'showConfirmButton'=> false
                ],
            ]);
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return $this->refresh();
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    public function actionAccount()
    {
        /** @var SettingsForm $model */
        $model = \Yii::createObject(SettingsForm::className());
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_ACCOUNT_UPDATE, $event);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash(SweetAlert2::TYPE_SUCCESS, [
                [
                    'title' => 'Successfully',
                    'text' => \Yii::t('user', 'Your account details have been updated'),
                    'timer' => 2000,
                    'showConfirmButton'=> false
                ],
            ]);
            $this->trigger(self::EVENT_AFTER_ACCOUNT_UPDATE, $event);
            return $this->refresh();
        }

        return $this->render('account', [
            'model' => $model,
        ]);
    }
}