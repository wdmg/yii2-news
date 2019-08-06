<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wdmg\news\models\News */

$this->title = Yii::t('app/modules/news', 'New post');
$this->params['breadcrumbs'][] = ['label' => $this->context->module->name, 'url' => ['news/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
</div>
<div class="news-create">
    <?= $this->render('_form', [
        'module' => $module,
        'model' => $model,
        'statusModes' => $model->getStatusesList(),
    ]); ?>
</div>