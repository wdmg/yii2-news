<?php

use wdmg\helpers\StringHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wdmg\news\models\News */

$this->title = Yii::t('app/modules/news', 'Updating news: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/news', 'All news'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => StringHelper::stringShorter($model->name, 64), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app/modules/news', 'Edit');


?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
</div>
<div class="news-update">
    <?= $this->render('_form', [
        'module' => $module,
        'model' => $model,
        'statusModes' => $model->getStatusesList(),
    ]); ?>
</div>