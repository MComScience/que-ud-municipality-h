<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 11:32
 */
namespace homer\user\controllers;

use dektrium\user\controllers\AdminController as BaseAdminController;
use homer\user\models\User;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use Intervention\Image\ImageManagerStatic;
use homer\sweetalert2\SweetAlert2;
use yii\helpers\Url;

class AdminController extends BaseAdminController
{
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

    public function actionCreate()
    {
        /** @var User $user */
        $user = \Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'create',
        ]);
        $event = $this->getUserEvent($user);

        $this->performAjaxValidation($user);

        $this->trigger(self::EVENT_BEFORE_CREATE, $event);
        if ($user->load(\Yii::$app->request->post()) && $user->create()) {
            \Yii::$app->session->setFlash(SweetAlert2::TYPE_SUCCESS, [
                [
                    'title' => 'Successfully',
                    'text' => \Yii::t('user', 'User has been created'),
                    'timer' => 3000,
                    'showConfirmButton'=> false
                ],
            ]);
            $this->trigger(self::EVENT_AFTER_CREATE, $event);
            return $this->redirect(['update', 'id' => $user->id]);
        }

        return $this->render('create', [
            'user' => $user,
        ]);
    }

    public function actionDelete($id)
    {
        if ($id == \Yii::$app->user->getId()) {
            \Yii::$app->session->setFlash(SweetAlert2::TYPE_ERROR, [
                [
                    'title' => 'Oops...!',
                    'text' => \Yii::t('user', 'You can not remove your own account'),
                    'timer' => 3000,
                    'showConfirmButton'=> false
                ],
            ]);
        } else {
            $model = $this->findModel($id);
            $event = $this->getUserEvent($model);
            $this->trigger(self::EVENT_BEFORE_DELETE, $event);
            $model->delete();
            $this->trigger(self::EVENT_AFTER_DELETE, $event);
            \Yii::$app->session->setFlash(SweetAlert2::TYPE_SUCCESS, [
                [
                    'title' => 'Successfully',
                    'text' => \Yii::t('user', 'User has been deleted'),
                    'timer' => 3000,
                    'showConfirmButton'=> false
                ],
            ]);
        }

        return $this->redirect(['index']);
    }

    public function actionUpdate($id)
    {
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);
        $user->scenario = 'update';
        $event = $this->getUserEvent($user);

        $this->performAjaxValidation($user);

        $this->trigger(self::EVENT_BEFORE_UPDATE, $event);
        if ($user->load(\Yii::$app->request->post()) && $user->save()) {
            \Yii::$app->session->setFlash(SweetAlert2::TYPE_SUCCESS, [
                [
                    'title' => 'Successfully',
                    'text' => \Yii::t('user', 'Account details have been updated'),
                    'timer' => 3000,
                    'showConfirmButton'=> false
                ],
            ]);
            $this->trigger(self::EVENT_AFTER_UPDATE, $event);
            return $this->refresh();
        }

        return $this->render('_account', [
            'user' => $user,
        ]);
    }

    public function actionUpdateProfile($id)
    {
        Url::remember('', 'actions-redirect');
        $user    = $this->findModel($id);
        $profile = $user->profile;

        if ($profile == null) {
            $profile = \Yii::createObject(Profile::className());
            $profile->link('user', $user);
        }
        $event = $this->getProfileEvent($profile);

        $this->performAjaxValidation($profile);

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);

        if ($profile->load(\Yii::$app->request->post()) && $profile->save()) {
            \Yii::$app->session->setFlash(SweetAlert2::TYPE_SUCCESS, [
                [
                    'title' => 'Successfully',
                    'text' => \Yii::t('user', 'Profile details have been updated'),
                    'timer' => 3000,
                    'showConfirmButton'=> false
                ],
            ]);
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return $this->refresh();
        }

        return $this->render('_profile', [
            'user'    => $user,
            'profile' => $profile,
        ]);
    }
}