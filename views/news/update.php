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
$this->params['breadcrumbs'][] = Yii::t('app/modules/news', 'Updating');

?>
<?php if (Yii::$app->authManager && $this->context->module->moduleExist('rbac') && Yii::$app->user->can('updatePosts', [
    'created_by' => $model->created_by,
    'updated_by' => $model->updated_by
])) : ?>
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
    </div>
    <div class="news-update">
        <?= $this->render('_form', [
            'module' => $module,
            'model' => $model,
            'statusModes' => $model->getStatusesList(),
            'languagesList' => $model->getLanguagesList(false),
        ]); ?>
    </div>
<?php else: ?>
    <div class="page-header">
        <h1 class="text-danger"><?= Yii::t('app/modules/news', 'Error {code}. Access Denied', [
                'code' => 403
            ]) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
    </div>
    <div class="news-update-error">
        <blockquote>
            <?= Yii::t('app/modules/news', 'You are not allowed to view this page.'); ?>
        </blockquote>
    </div>
<?php endif; ?>