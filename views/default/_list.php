<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<div class="post">
    <h3><?= Html::encode($model->title); ?></h3>
    <?php
        if ($model->image) {
            echo '<div class="col-xs-12 col-sm-4">' . Html::img($model->getImagePath(true) . '/' . $model->image, ['class' => 'img-responsive']) . '</div>';
        }
    ?>
    <?= HtmlPurifier::process($model->excerpt); ?>
    <?php
    if (($postURL = $model->getUrl()) && $model->id)
        echo Html::a('Read more', $postURL);
    ?>
</div>