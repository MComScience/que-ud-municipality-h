<?php

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@homer/assets');
?>
<?php $this->beginContent('@homer/views/layouts/_base.php',['class' => '']); ?>
    <div class="color-line">
    </div>
    <?= $content; ?>
<?php $this->endContent(); ?>