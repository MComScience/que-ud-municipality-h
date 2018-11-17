<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 12/11/2561
 * Time: 13:43
 */
$themeAsset = Yii::$app->assetManager->getPublishedUrl('@homer/assets/dist');
?>
<?php $this->beginContent('@homer/views/layouts/_base.php', ['class' => \Yii::$app->keyStorage->get('theme.body.class', 'fixed-navbar fixed-sidebar')]); ?>
<?= $this->render('_header', ['themeAsset' => $themeAsset]); ?>
<?= $this->render('_navigation', ['themeAsset' => $themeAsset]); ?>
<!-- Main Wrapper -->
<div id="wrapper">
    <?= $this->render('_content', ['content' => $content, 'themeAsset' => $themeAsset]); ?>
    <?= $this->render('_footer', ['themeAsset' => $themeAsset]); ?>
</div>
<?php $this->endContent(); ?>
