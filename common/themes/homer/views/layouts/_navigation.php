<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 13:41
 */
use homer\widgets\Menu;
use yii\helpers\Html;
use yii\helpers\Url;

$identity = Yii::$app->user->identity;
?>
<!-- Navigation -->
<aside id="menu" style="top: 80px;">
    <div id="navigation">
        <div class="profile-picture">
            <?php if(!Yii::$app->user->isGuest):?>
            <a href="<?= Url::to(['/user/settings/profile']) ?>">
                <img src="<?= $identity->profile->getAvatar(); ?>" class="img-circle m-b" alt="logo" width="76px" height="76px">
            </a>

            <div class="stats-label text-color">
                <span class="font-extra-bold font-uppercase"><?= $identity->username ?></span>

                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                        <small class="text-muted">Founder of App <b class="caret"></b></small>
                    </a>
                    <ul class="dropdown-menu animated flipInX m-t-xs">
                        <li><?= Html::a('Profile',['/user/settings/profile']) ?></li>
                        <li class="divider"></li>
                        <li><?= Html::a('Logout',['/auth/logout'],['data-method' => 'post']) ?></li>
                    </ul>
                </div>
                <div id="sparkline1" class="small-chart m-t-sm"></div>
            </div>
            <?php endif; ?>
        </div>

        <?php
        echo Menu::widget([
            'key' => Yii::$app->id,
            'options' => [
                'class' => 'nav',
                'id' => 'side-menu',
            ],
            'activateParents' => true,
            'encodeLabels' => false,
        ]);
        ?>
    </div>
</aside>
