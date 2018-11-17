<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 *
 * @var $this     \yii\web\View
 * @var $provider \probe\provider\ProviderInterface
 */

use common\models\FileStorageItem;
use common\models\User;
use common\assets\SystemInformationAsset;

$this->title = Yii::t('backend', 'System Information');

$this->registerJs("window.paceOptions = { ajax: false }", \yii\web\View::POS_HEAD);
SystemInformationAsset::register($this);

?>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget well" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false"
             data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false"
             data-widget-custombutton="false" data-widget-sortable="false">
            <!-- widget div-->
            <div>
                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                    <!-- This area used as dropdown edit box -->
                </div>
                <!-- end widget edit box -->

                <!-- widget content -->
                <div class="widget-body">
                    <p>
                        <i class="fa fa-hdd-o"></i>
                        <?php echo Yii::t('backend', 'Processor') ?>
                    </p>
                    <hr class="simple">
                    <dl class="dl-horizontal">
                        <dt><?php echo Yii::t('backend', 'Processor') ?></dt>
                        <dd><?php echo $provider->getCpuModel() ?></dd>

                        <dt><?php echo Yii::t('backend', 'Processor Architecture') ?></dt>
                        <dd><?php echo $provider->getArchitecture() ?></dd>

                        <dt><?php echo Yii::t('backend', 'Number of cores') ?></dt>
                        <dd><?php echo $provider->getCpuCores() ?></dd>
                    </dl>
                </div>
                <!-- end widget content -->
            </div>
            <!-- end widget div -->
        </div>
        <!-- end widget -->
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="jarviswidget well" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false"
             data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false"
             data-widget-custombutton="false" data-widget-sortable="false">
            <!-- widget div-->
            <div>
                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                    <!-- This area used as dropdown edit box -->
                </div>
                <!-- end widget edit box -->

                <!-- widget content -->
                <div class="widget-body">
                    <p>
                        <i class="fa fa-hdd-o"></i>
                        <?php echo Yii::t('backend', 'Operating System') ?>
                    </p>
                    <hr class="simple">
                    <dl class="dl-horizontal">
                        <dt><?php echo Yii::t('backend', 'OS') ?></dt>
                        <dd><?php echo $provider->getOsType() ?></dd>

                        <dt><?php echo Yii::t('backend', 'OS Release') ?></dt>
                        <dd><?php echo $provider->getOsRelease() ?></dd>

                        <dt><?php echo Yii::t('backend', 'Kernel version') ?></dt>
                        <dd><?php echo $provider->getOsKernelVersion() ?></dd>
                    </dl>
                </div>
                <!-- end widget content -->
            </div>
            <!-- end widget div -->
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false"
             data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false"
             data-widget-custombutton="false" data-widget-sortable="false">
            <!-- widget div-->
            <div>
                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                    <!-- This area used as dropdown edit box -->
                </div>
                <!-- end widget edit box -->

                <!-- widget content -->
                <div class="widget-body">
                    <p>
                        <i class="fa fa-hdd-o"></i>
                        <?php echo Yii::t('backend', 'Time') ?>
                    </p>
                    <hr class="simple">
                    <dl class="dl-horizontal">
                        <dt><?php echo Yii::t('backend', 'System Date') ?></dt>
                        <dd><?php echo Yii::$app->formatter->asDate(time()) ?></dd>

                        <dt><?php echo Yii::t('backend', 'System Time') ?></dt>
                        <dd><?php echo Yii::$app->formatter->asTime(time()) ?></dd>

                        <dt><?php echo Yii::t('backend', 'Timezone') ?></dt>
                        <dd><?php echo date_default_timezone_get() ?></dd>
                    </dl>
                </div>
                <!-- end widget content -->
            </div>
            <!-- end widget div -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false"
             data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false"
             data-widget-custombutton="false" data-widget-sortable="false">
            <!-- widget div-->
            <div>
                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                    <!-- This area used as dropdown edit box -->
                </div>
                <!-- end widget edit box -->

                <!-- widget content -->
                <div class="widget-body">
                    <p>
                        <i class="fa fa-hdd-o"></i>
                        <?php echo Yii::t('backend', 'Network') ?>
                    </p>
                    <hr class="simple">
                    <dl class="dl-horizontal">
                        <dt><?php echo Yii::t('backend', 'Hostname') ?></dt>
                        <dd><?php echo $provider->getHostname() ?></dd>

                        <dt><?php echo Yii::t('backend', 'Internal IP') ?></dt>
                        <dd><?php echo $provider->getServerIP() ?></dd>

                        <dt><?php echo Yii::t('backend', 'External IP') ?></dt>
                        <dd><?php echo $provider->getExternalIP() ?></dd>

                        <dt><?php echo Yii::t('backend', 'Port') ?></dt>
                        <dd><?php echo $provider->getServerVariable('SERVER_PORT') ?></dd>
                    </dl>
                </div>
                <!-- end widget content -->
            </div>
            <!-- end widget div -->
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="jarviswidget well" id="wid-id-5" data-widget-colorbutton="false" data-widget-editbutton="false"
             data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false"
             data-widget-custombutton="false" data-widget-sortable="false">
            <!-- widget div-->
            <div>
                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                    <!-- This area used as dropdown edit box -->
                </div>
                <!-- end widget edit box -->

                <!-- widget content -->
                <div class="widget-body">
                    <p>
                        <i class="fa fa-hdd-o"></i>
                        <?php echo Yii::t('backend', 'Software') ?>
                    </p>
                    <hr class="simple">
                    <dl class="dl-horizontal">
                        <dt><?php echo Yii::t('backend', 'Web Server') ?></dt>
                        <dd><?php echo $provider->getServerSoftware() ?></dd>

                        <dt><?php echo Yii::t('backend', 'PHP Version') ?></dt>
                        <dd><?php echo $provider->getPhpVersion() ?></dd>

                        <dt><?php echo Yii::t('backend', 'DB Type') ?></dt>
                        <dd><?php echo $provider->getDbType(Yii::$app->db->pdo) ?></dd>

                        <dt><?php echo Yii::t('backend', 'DB Version') ?></dt>
                        <dd><?php echo $provider->getDbVersion(Yii::$app->db->pdo) ?></dd>
                    </dl>
                </div>
                <!-- end widget content -->
            </div>
            <!-- end widget div -->
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="jarviswidget well" id="wid-id-5" data-widget-colorbutton="false" data-widget-editbutton="false"
             data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false"
             data-widget-custombutton="false" data-widget-sortable="false">
            <!-- widget div-->
            <div>
                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                    <!-- This area used as dropdown edit box -->
                </div>
                <!-- end widget edit box -->

                <!-- widget content -->
                <div class="widget-body">
                    <p>
                        <i class="fa fa-hdd-o"></i>
                        <?php echo Yii::t('backend', 'Memory') ?>
                    </p>
                    <hr class="simple">
                    <dl class="dl-horizontal">
                        <dt><?php echo Yii::t('backend', 'Total memory') ?></dt>
                        <dd><?php echo Yii::$app->formatter->asSize($provider->getTotalMem()) ?></dd>

                        <dt><?php echo Yii::t('backend', 'Free memory') ?></dt>
                        <dd><?php echo Yii::$app->formatter->asSize($provider->getFreeMem()) ?></dd>

                        <dt><?php echo Yii::t('backend', 'Total Swap') ?></dt>
                        <dd><?php echo Yii::$app->formatter->asSize($provider->getTotalSwap()) ?></dd>

                        <dt><?php echo Yii::t('backend', 'Free Swap') ?></dt>
                        <dd><?php echo Yii::$app->formatter->asSize($provider->getFreeSwap()) ?></dd>
                    </dl>
                </div>
                <!-- end widget content -->
            </div>
            <!-- end widget div -->
        </div>
    </div>
</div>

<div id="system-information-index">
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>
                        <?php echo gmdate('H:i:s', $provider->getUptime()) ?>
                    </h3>
                    <p>
                        <?php echo Yii::t('backend', 'Uptime') ?>
                    </p>
                </div>
                <div class="icon">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="small-box-footer">
                    &nbsp;
                </div>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>
                        <?php echo $provider->getLoadAverage() ?>
                    </h3>
                    <p>
                        <?php echo Yii::t('backend', 'Load average') ?>
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <div class="small-box-footer">
                    &nbsp;
                </div>
            </div>
        </div><!-- ./col -->

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>
                        <?php echo User::find()->count() ?>
                    </h3>
                    <p>
                        <?php echo Yii::t('backend', 'User Registrations') ?>
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="<?php echo Yii::$app->urlManager->createUrl(['/user/admin/index']) ?>" class="small-box-footer">
                    <?php echo Yii::t('backend', 'More info') ?> <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>
                        <?php echo FileStorageItem::find()->count() ?>
                    </h3>
                    <p>
                        <?php echo Yii::t('backend', 'Files in storage') ?>
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="<?php echo Yii::$app->urlManager->createUrl(['/file/storage/index']) ?>"
                   class="small-box-footer">
                    <?php echo Yii::t('backend', 'More info') ?> <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div><!-- ./col -->
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="jarviswidget well" id="cpu-usage" data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false"
                 data-widget-custombutton="false" data-widget-sortable="false">
                <!-- widget div-->
                <div>
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body">
                        <p>
                            <i class="fa fa-hdd-o"></i>
                            <?php echo Yii::t('backend', 'CPU Usage') ?>
                        </p>
                        <?php echo Yii::t('backend', 'Real time') ?>
                        <div class="realtime btn-group" data-toggle="btn-toggle">
                            <button type="button" class="btn btn-default btn-xs active" data-toggle="on">
                                <?php echo Yii::t('backend', 'On') ?>
                            </button>
                            <button type="button" class="btn btn-default btn-xs" data-toggle="off">
                                <?php echo Yii::t('backend', 'Off') ?>
                            </button>
                        </div>
                        <hr class="simple">
                        <div class="chart" style="height: 300px;">
                        </div>
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="jarviswidget well" id="memory-usage" data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false"
                 data-widget-custombutton="false" data-widget-sortable="false">
                <!-- widget div-->
                <div>
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body">
                        <p>
                            <i class="fa fa-hdd-o"></i>
                            <?php echo Yii::t('backend', 'Memory Usage') ?>
                        </p>
                        <?php echo Yii::t('backend', 'Real time') ?>
                        <div class="btn-group realtime" data-toggle="btn-toggle">
                            <button type="button" class="btn btn-default btn-xs active" data-toggle="on">
                                <?php echo Yii::t('backend', 'On') ?>
                            </button>
                            <button type="button" class="btn btn-default btn-xs" data-toggle="off">
                                <?php echo Yii::t('backend', 'Off') ?>
                            </button>
                        </div>
                        <hr class="simple">
                        <div class="chart" style="height: 300px;">
                        </div>
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
