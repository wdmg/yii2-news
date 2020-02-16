<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wdmg\news\models\News */

$this->title = Yii::t('app/modules/news', 'View news item');
$this->params['breadcrumbs'][] = ['label' => $this->context->module->name, 'url' => ['news/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
</div>
<div class="news-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model) {
                    $output = Html::tag('strong', $model->name);
                    if (($postURL = $model->getPostUrl(true, true)) && $model->id) {
                        $output .= '<br/>' . Html::a($model->getPostUrl(true, false), $postURL, [
                            'target' => '_blank',
                            'data-pjax' => 0
                        ]);
                    }
                    return $output;
                }
            ],
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function($model) {
                    if ($model->image) {
                        return '<div style="width:50%;">' . Html::img($model->getImagePath(true) . '/' . $model->image, ['class' => 'img-responsive']) . '</div>';
                    } else {
                        return null;
                    }
                }
            ],
            'title:ntext',
            [
                'attribute' => 'content',
                'format' => 'html',
            ],
            'description:ntext',
            'keywords:ntext',
            [
                'attribute' => 'in_sitemap',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->in_sitemap)
                        return '<span class="fa fa-check text-success"></span>';
                    else
                        return '<span class="fa fa-remove text-danger"></span>';
                }
            ],
            [
                'attribute' => 'in_rss',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->in_rss)
                        return '<span class="fa fa-check text-success"></span>';
                    else
                        return '<span class="fa fa-remove text-danger"></span>';
                }
            ],
            [
                'attribute' => 'in_turbo',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->in_turbo)
                        return '<span class="fa fa-check text-success"></span>';
                    else
                        return '<span class="fa fa-remove text-danger"></span>';
                }
            ],
            [
                'attribute' => 'in_amp',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->in_amp)
                        return '<span class="fa fa-check text-success"></span>';
                    else
                        return '<span class="fa fa-remove text-danger"></span>';
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->status == $data::POST_STATUS_PUBLISHED)
                        return '<span class="label label-success">'.Yii::t('app/modules/news','Published').'</span>';
                    elseif ($data->status == $data::POST_STATUS_DRAFT)
                        return '<span class="label label-default">'.Yii::t('app/modules/news','Draft').'</span>';
                    else
                        return $data->status;
                }
            ],
            /*[
                'attribute' => 'route',
                'format' => 'html',
                'value' => function($data) {

                    if (isset($data->route))
                        return Html::tag('strong', $data->route);
                    elseif (isset($this->context->module->newsRoute))
                        return ((is_array($this->context->module->newsRoute)) ? array_shift($this->context->module->newsRoute) : $this->context->module->newsRoute) .'&nbsp;'. Html::tag('span', Yii::t('app/modules/news','by default'), ['class' => 'label label-default']);
                    else
                        return null;
                }
            ],
            [
                'attribute' => 'layout',
                'format' => 'html',
                'value' => function($data) {
                    if (isset($data->layout))
                        return Html::tag('strong', $data->layout);
                    elseif (isset($this->context->module->newsLayout))
                        return $this->context->module->newsLayout .'&nbsp;'. Html::tag('span', Yii::t('app/modules/news','by default'), ['class' => 'label label-default']);
                    else
                        return null;
                }
            ],*/
            'created_at:datetime',
            'updated_at:datetime'
        ],
    ]); ?>

</div>