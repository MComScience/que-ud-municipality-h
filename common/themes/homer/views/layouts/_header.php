<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 13:39
 */
use homer\widgets\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

Icon::map($this, Icon::FI);
?>
<!-- Simple splash screen-->
<div class="splash">
    <div class="color-line"></div>
    <div class="splash-title">
        <h1><?= Yii::$app->name ?></h1>
        <p></p>
        <div class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
        </div>
    </div>
</div>
<!-- Header -->
<div id="header">
    <div class="color-line">
    </div>
    <a href="/">
        <div id="logo" class="light-version" style="padding: 10px 10px 18px 18px;">
        <span>
            <?= Yii::$app->name ?>
        </span>
        </div>
    </a>
    <nav role="navigation">
        <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
        <div class="small-logo">
            <span class="text-primary"><?= Yii::$app->name ?></span>
        </div>
        <div class="mobile-menu">
            <button type="button" class="navbar-toggle mobile-menu-toggle" data-toggle="collapse" data-target="#mobile-collapse">
                <i class="fa fa-chevron-down"></i>
            </button>
            <div class="collapse mobile-navbar" id="mobile-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <?= Html::a(Icon::show('home').'หน้าหลัก',['/'],['title' => 'หน้าหลัก','data-pjax' => '0']); ?>
                    </li>
                    <li>
                        <?= Html::a(Icon::show('dashboard').'แดชบอร์ด',['/dashboard'],['title' => 'Dashboard']); ?>
                    </li>
                    <li>
                        <?= Html::a('<i class="pe-7s-speaker"></i>'.' โปรแกรมเสียง',['/app/calling/play-sound'],['title' => 'Sound']); ?>
                    </li>
                    <li>
                        <?= Html::a('<i class="pe-7s-monitor"></i>'.' จอแสดงผล',['/app/display/index'],['title' => 'จอแสดงผล']); ?>
                    </li>
                    <?php if(!Yii::$app->user->isGuest):?>
                        <li>
                            <?= Html::a('<i class="pe-7s-note"></i> ความพึงพอใจ',['/site/satis'],['title' => 'ความพึงพอใจ','data-pjax' => '0']); ?>
                        </li>
                        <li>
                            <?= Html::a(Icon::show('newspaper-o').' ข้อมูลส่วนตัว',['/user/settings/profile'],['title' => 'ข้อมูลส่วนตัว','data-pjax' => '0']); ?>
                        </li>
                        <li>
                            <?= Html::a(Icon::show('sign-out').' ออกจากระบบ',['/user/security/logout'],['title' => 'ออกจากระบบ','data-method' => 'post']); ?>
                        </li>
                    <?php endif; ?>
                    <?php if(Yii::$app->user->isGuest):?>
                        <li>
                            <?= Html::a('<i class="pe-7s-upload pe-rotate-90"></i>'.' เข้าสู่ระบบ',['/user/security/login'],['title' => 'Login']); ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav no-borders">
                <li class="dropdown">
                    <?= Html::a('<i class="fa fa-dashboard"></i>',['/dashboard'],['title' => 'Dashboard']); ?>
                </li>
                <li class="dropdown">
                    <?= Html::a('<i class="pe-7s-speaker"></i>',['/app/calling/play-sound'],['title' => 'โปรแกรมเสียง']); ?>
                </li>
                <li class="dropdown">
                    <?= Html::a('<i class="pe-7s-monitor"></i>',['/app/display/index'],['title' => 'จอแสดงผล']); ?>
                </li>
                <?php if(!Yii::$app->user->isGuest):?>
                    <li class="dropdown">
                        <?= Html::a('<i class="pe-7s-note"></i>',['/site/satis'],['title' => 'แบบประเมินความพึงพอใจ','target' => '_blank']); ?>
                    </li>
                    <li class="dropdown">
                        <?= Html::a('<i class="pe-7s-config"></i>',['/app/settings/service-group'],['title' => 'ตั้งค่า']); ?>
                    </li>
                    <li class="dropdown" style="border-left: 1px solid #ddd;">
                        <?= Html::a(Html::tag('span',Yii::$app->user->identity->username,['style' => 'font-size:14px;']).' '.Icon::show('sign-out'),['/user/security/logout'],['title' => 'Sign Out','data-method' => 'post']); ?>
                    </li>
                <?php endif; ?>
                <?php if(Yii::$app->user->isGuest):?>
                    <li class="dropdown">
                        <?= Html::a(Icon::show('sign-in').'เข้าสู่ระบบ',['/user/security/login'],['title' => 'Sign In']); ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</div>
