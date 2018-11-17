<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 13:43
 */
?>
<!-- Footer-->
<footer class="footer" style="height: auto">
        <span class="pull-right">
            <?= \lajax\languagepicker\widgets\LanguagePicker::widget([
                'skin' => \lajax\languagepicker\widgets\LanguagePicker::SKIN_BUTTON,
                'size' => \lajax\languagepicker\widgets\LanguagePicker::SIZE_SMALL
            ]); ?>
        </span>
    <?= \Yii::$app->keyStorage->get('copyright', 'Web Application Framework © 2018') ?>
</footer>
