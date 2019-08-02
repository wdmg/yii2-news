<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<div class="post">
    <h3><?= Html::encode($model->title); ?></h3>
    <?= HtmlPurifier::process($model->excerpt); ?>
    <?php
    if (($pageURL = $model->getPostUrl()) && $model->id)
        echo Html::a('Read more', $pageURL);
    ?>
</div>