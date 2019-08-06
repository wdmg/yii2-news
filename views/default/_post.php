<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<div class="post">
    <h2><?= Html::encode($model->title); ?></h2>

    <?php
    if ($model->image) {
        echo '<div class="col-xs-12 col-sm-12">' . Html::img($model->getImagePath(true) . '/' . $model->image, ['class' => 'img-responsive']) . '</div>';
    }
    ?>

    <?= HtmlPurifier::process($model->content); ?>

</div>