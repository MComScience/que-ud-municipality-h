<?php
namespace frontend\controllers;

use common\components\ChartBuilder;
use frontend\modules\app\models\TbQue;
use frontend\modules\app\models\TbService;
use frontend\modules\app\models\TbServiceGroup;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        //\Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $data = [];
        $serviceGroups = TbServiceGroup::find()->all();
        foreach($serviceGroups as $serviceGroup){
            $chartBuilder = new ChartBuilder([
                'service_group_id' => $serviceGroup['service_group_id'],
            ]);
            $services = TbService::find()->where(['service_status' => 1, 'service_group_id' => $serviceGroup['service_group_id']])->all();
            $arr = [];
            foreach ($services as $service) {
                $count = TbQue::find()->where(['service_id' => $service['service_id'],'service_group_id' => $serviceGroup['service_group_id']])->count();

                $arr[] = [
                    'service_name' => $service['service_name'],
                    'count' => $count,
                ];
            }
            $dataChartRangeTime = $chartBuilder->getDataChartRangeTime();
            $dataChartRangeTime2 = $chartBuilder->getDataChartRangeTime2();
            $chart3 = [
                'series' => ArrayHelper::merge($dataChartRangeTime['series'], $dataChartRangeTime2['series']),
                'subseries' => ArrayHelper::merge($dataChartRangeTime['subseries'], $dataChartRangeTime2['subseries'])
            ];
            $data[] = [
                'service_group_id' => $serviceGroup['service_group_id'],
                'service_group_name' => $serviceGroup['service_group_name'],
                'services' => $arr,
                'pieData' => $chartBuilder->getDataChart1(),
                'dataChart2' => $chartBuilder->getDataChart2(),
                'dataChartRangeTime' => $chart3,
                'dataChartRangeTime2' => $chartBuilder->getDataChartRangeTime2(),
            ];
        }
        //return $data;
        return $this->render('index', [
            'data' => $data,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionClearCache()
    {
        $frontendAssetPath = \Yii::getAlias('@frontend') . '/web/assets/';
        $backendAssetPath = \Yii::getAlias('@backend') . '/web/assets/';

        $this->recursiveDelete($frontendAssetPath);
        $this->recursiveDelete($backendAssetPath);

        if (\Yii::$app->cache->flush()) {
            \Yii::$app->session->setFlash('crudMessage', 'Cache has been flushed.');
        } else {
            \Yii::$app->session->setFlash('crudMessage', 'Failed to flush cache.');
        }

        return \Yii::$app->getResponse()->redirect(Yii::$app->getRequest()->referrer);
    }

    public static function recursiveDelete($path)
    {
        if (is_file($path)) {
            return @unlink($path);
        } elseif (is_dir($path)) {
            $scan = glob(rtrim($path, '/') . '/*');
            foreach ($scan as $index => $newPath) {
                self::recursiveDelete($newPath);
            }
            return @rmdir($path);
        }
    }
}
