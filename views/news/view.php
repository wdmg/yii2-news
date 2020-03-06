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
                        return Html::img($model->getImagePath(true) . '/' . $model->image, [
                            'class' => 'img-thumbnail',
                            'style' => 'max-height: 160px'
                        ]);
                    } else {
                        return null;
                    }
                }
            ],
            'title:ntext',
            [
                'attribute' => 'content',
                'format' => 'html',
                'contentOptions' => [
                    'style' => 'display:inline-block;max-height:360px;overflow-x:auto;'
                ]
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
                        return '<span class="fa fa-times text-danger"></span>';
                }
            ],
            [
                'attribute' => 'in_rss',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->in_rss)
                        return '<span class="fa fa-check text-success"></span>';
                    else
                        return '<span class="fa fa-times text-danger"></span>';
                }
            ],
            [
                'attribute' => 'in_turbo',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->in_turbo)
                        return '<span class="fa fa-check text-success"></span>';
                    else
                        return '<span class="fa fa-times text-danger"></span>';
                }
            ],
            [
                'attribute' => 'in_amp',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->in_amp)
                        return '<span class="fa fa-check text-success"></span>';
                    else
                        return '<span class="fa fa-times text-danger"></span>';
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
            [
                'attribute' => 'created',
                'label' => Yii::t('app/modules/news','Created'),
                'format' => 'html',
                'value' => function($data) {

                    $output = "";
                    if ($user = $data->createdBy) {
                        $output = Html::a($user->username, ['../admin/users/view/?id='.$user->id], [
                            'target' => '_blank',
                            'data-pjax' => 0
                        ]);
                    } else if ($data->created_by) {
                        $output = $data->created_by;
                    }

                    if (!empty($output))
                        $output .= ", ";

                    $output .= Yii::$app->formatter->format($data->updated_at, 'datetime');
                    return $output;
                }
            ],
            [
                'attribute' => 'updated',
                'label' => Yii::t('app/modules/news','Updated'),
                'format' => 'html',
                'value' => function($data) {

                    $output = "";
                    if ($user = $data->updatedBy) {
                        $output = Html::a($user->username, ['../admin/users/view/?id='.$user->id], [
                            'target' => '_blank',
                            'data-pjax' => 0
                        ]);
                    } else if ($data->updated_by) {
                        $output = $data->updated_by;
                    }

                    if (!empty($output))
                        $output .= ", ";

                    $output .= Yii::$app->formatter->format($data->updated_at, 'datetime');
                    return $output;
                }
            ],
        ],
    ]); ?>
    <hr/>
    <div class="form-group">
        <?= Html::a(Yii::t('app/modules/news', '&larr; Back to list'), ['news/index'], ['class' => 'btn btn-default pull-left']) ?>&nbsp;
        <?= Html::a(Yii::t('app/modules/news', 'Update'), ['news/update', 'id' => $model->id], ['class' => 'btn btn-primary pull-right']) ?>
    </div>
</div>