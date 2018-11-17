<?php
/* @var $this yii\web\View */
use yii\widgets\Pjax;
use frontend\assets\SocketIOAsset;
use yii\bootstrap\Tabs;
SocketIOAsset::register($this);

$this->title = 'Dashboard';
?>
<?php Pjax::begin(['id' => 'pjax-dashboard']); ?>
<div class="hpanel">
    <div class="panel-heading hbuilt">
        <div class="panel-tools">
            <a class="showhide"><i class="fa fa-chevron-up"></i></a>
        </div>
        จำนวนคิววันนี้
    </div>
    <div class="panel-body">
        <div class="hpanel">
            <?php
            $items = [];
            foreach($data as $key => $item){
                $items[] = [
                    'label' => $item['service_group_name'],
                    'content' => $this->render('_content_dashboard',[
                        'model' => $item,
                    ]),
                    'active' => $key == 0 ? true : false,
                ];
            }
            echo Tabs::widget([
                'items' => $items,
            ]);
            ?>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
<br>

<?php
$this->registerJsFile(
    '@web/js/dashboard.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
?>
