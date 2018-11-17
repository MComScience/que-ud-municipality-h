<?php

namespace common\modules\translation\controllers;


use common\modules\translation\models\search\SourceSearch;
use common\modules\translation\models\Source;
use common\modules\translation\models\Translation;
use common\modules\translation\traits\ModuleTrait;
use common\base\MultiModel;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use homer\sweetalert2\SweetAlert2;

class DefaultController extends Controller
{
    use ModuleTrait;

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $source = new Source();

        $model = new MultiModel(['models' => ['source' => $source]]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(SweetAlert2::TYPE_SUCCESS, \Yii::t('backend', 'Created successfully!'));
            return $this->redirect(['update', 'id' => $model->getModel('source')->id]);
        } else {
            $searchModel = new SourceSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            Url::remember(Yii::$app->request->getUrl(), 'translation-filter');

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
                'languages' => $this->getLanguages(),
            ]);
        }
    }

    /**
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $source = $this->findModel($id);

        $translationModels = [];
        foreach ($this->getLanguages() as $language => $name) {
            $translationModels[$language] = ($translation = $source->getTranslation($language)) != null
                ? $translation
                : new Translation(['id' => $source->id, 'language' => $language]);
        }

        $model = new MultiModel(['models' => ArrayHelper::merge([
            'source' => $source,
        ], $translationModels)]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(SweetAlert2::TYPE_SUCCESS, \Yii::t('backend', 'Updated successfully!'));
            return $this->redirect(Url::previous('translation-filter') ?: ['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'languages' => $this->getLanguages(),
            ]);
        }
    }

    /**
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Translation::deleteAll(['id' => $id]);
        \Yii::$app->session->setFlash(SweetAlert2::TYPE_SUCCESS, \Yii::t('backend', 'Deleted!'));

        return $this->redirect(Url::previous('translation-filter') ?: ['index']);
    }

    /**
     * @param integer $id
     *
     * @return Source the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Source::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}