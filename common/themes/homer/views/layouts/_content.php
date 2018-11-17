<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 13:40
 */
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use kartik\icons\Icon;
?>
<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">
            <a class="small-header-action" href="">
                <div class="clip-header">
                    <?= Icon::show('arrow-up') ?>
                </div>
            </a>

            <div id="hbreadcrumb" class="pull-right">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'options' => ['class' => 'hbreadcrumb breadcrumb'],
                    'tag' => 'ol',
                ]) ?>
            </div>
            <h2 class="font-light m-b-xs">
                <?= $this->title; ?>
            </h2>
            <small></small>
        </div>
    </div>
</div>
<div class="content animate-panel">
    <?= Alert::widget() ?>
    <?= $content ?>
</div>
