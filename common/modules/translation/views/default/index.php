<?php

use common\modules\translation\models\Source;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use homer\widgets\Icon;
/**
 * @var $this               yii\web\View
 * @var $searchModel        common\modules\translation\models\search\SourceSearch
 * @var $dataProvider       yii\data\ActiveDataProvider
 * @var $model              \common\base\MultiModel
 * @var $languages          array
 */

$this->title = Yii::t('backend', 'Translation');
$this->params['breadcrumbs'][] = $this->title;

?>
<?= \homer\sweetalert2\SweetAlert2::widget(['useSessionFlash' => true]) ?>
    <div class="hpanel collapsed">
        <div class="panel-heading hbuilt">
            <div class="panel-tools">
                <a class="showhide"><i class="fa fa-chevron-up"></i></a>
            </div>
            <?= Icon::show('language') ?> เพิ่มรายการ
        </div>
        <div class="panel-body">
            <?php echo $this->render('_form', [
                'model' => $model,
                'languages' => $languages,
            ]) ?>
        </div>
    </div>
<?php

$translationColumns = [];
foreach ($languages as $language => $name) {
    $translationColumns[] = [
        'attribute' => $language,
        'header' => $name,
        'value' => $language . '.translation',
    ];
}


echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'options' => [
        'class' => 'grid-view table-responsive',
    ],
    'pjax' => true,
    'columns' => ArrayHelper::merge([
        [
            'class' => '\kartik\grid\SerialColumn'
        ],
        [
            'attribute' => 'category',
            'options' => ['style' => 'width: 10%'],
            'filter' => ArrayHelper::map(Source::find()->select('category')->distinct()->all(), 'category', 'category'),
        ],
        'message:ntext',
        [
            'class' => '\kartik\grid\ActionColumn',
            'options' => ['style' => 'width: 5%'],
            'template' => '{update} {delete}',
        ],
    ], $translationColumns),
]); ?>