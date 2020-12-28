<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wdmg\news\models\News */

$this->title = Yii::t('app/modules/news', 'View news item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/news', 'All news'), 'url' => ['news/index']];
$this->params['breadcrumbs'][] = $this->title;

$bundle = false;
if ($model->locale && isset(Yii::$app->translations) && class_exists('\wdmg\translations\FlagsAsset')) {
    $bundle = \wdmg\translations\FlagsAsset::register(Yii::$app->view);
}

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
                    if (($postURL = $model->getModelUrl(true, true)) && $model->id) {
                        $output .= '<br/>' . Html::a($model->getUrl(true, false), $postURL, [
                            'target' => '_blank',
                            'data-pjax' => 0
                        ]);
                    }

                    if (isset(Yii::$app->redirects) && $model->url && ($model->status == $model::STATUS_PUBLISHED)) {
                        if ($url = Yii::$app->redirects->check($model->url, false)) {
                            $output .= '&nbsp' . Html::tag('span', '', [
                                'class' => "text-danger fa fa-exclamation-circle",
                                'data' => [
                                    'toggle' => "tooltip",
                                    'placement' => "top"
                                ],
                                'title' => Yii::t('app/modules/redirects', 'For this URL is active redirect to {url}', [
                                    'url' => $url
                                ])
                            ]);
                        }
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
                'attribute' => 'common',
                'label' => Yii::t('app/modules/news','Common'),
                'format' => 'html',
                'value' => function($data) {
                    $output = '';
                    if ($data->in_sitemap)
                        $output .= '<span class="fa fa-fw fa-sitemap text-success" title="' . Yii::t('app/modules/news','Present in sitemap') . '"></span>';
                    else
                        $output .= '<span class="fa fa-fw fa-sitemap text-danger" title="' . Yii::t('app/modules/news','Not present in sitemap') . '"></span>';

                    $output .= "&nbsp;";

                    if ($data->in_rss)
                        $output .= '<span class="fa fa-fw fa-rss text-success" title="' . Yii::t('app/modules/news','Present in RSS-feed') . '"></span>';
                    else
                        $output .= '<span class="fa fa-fw fa-rss text-danger" title="' . Yii::t('app/modules/news','Not present in RSS-feed') . '"></span>';

                    $output .= "&nbsp;";

                    if ($data->in_turbo)
                        $output .= '<span class="fa fa-fw fa-rocket text-success" title="' . Yii::t('app/modules/news','Present in Yandex.Turbo') . '"></span>';
                    else
                        $output .= '<span class="fa fa-fw fa-rocket text-danger" title="' . Yii::t('app/modules/news','Not present in Yandex.Turbo') . '"></span>';

                    $output .= "&nbsp;";

                    if ($data->in_amp)
                        $output .= '<span class="fa fa-fw fa-bolt text-success" title="' . Yii::t('app/modules/news','Present in Google AMP') . '"></span>';
                    else
                        $output .= '<span class="fa fa-fw fa-bolt text-danger" title="' . Yii::t('app/modules/news','Not present in Google AMP') . '"></span>';

                    return $output;
                }
            ],
            [
                'attribute' => 'locale',
                'label' => Yii::t('app/modules/news','Language'),
                'format' => 'raw',
                'value' => function($data) use ($bundle) {
                    if ($data->locale) {
                        if ($bundle) {
                            $locale = Yii::$app->translations->parseLocale($data->locale, Yii::$app->language);
                            if ($data->locale === $locale['locale']) { // Fixing default locale from PECL intl
                                if (!($country = $locale['domain']))
                                    $country = '_unknown';

                                $flag = \yii\helpers\Html::img($bundle->baseUrl . '/flags-iso/flat/24/' . $country . '.png', [
                                    'title' => $locale['name']
                                ]);
                                return $flag . " " . $locale['name'];
                            }
                        } else {
                            if (extension_loaded('intl'))
                                $language = mb_convert_case(trim(\Locale::getDisplayLanguage($data->locale, Yii::$app->language)), MB_CASE_TITLE, "UTF-8");
                            else
                                $language = $data->locale;

                            return $language;
                        }
                    }
                    return null;
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->status == $data::STATUS_PUBLISHED)
                        return '<span class="label label-success">'.Yii::t('app/modules/news','Published').'</span>';
                    elseif ($data->status == $data::STATUS_DRAFT)
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
        <?php if (Yii::$app->authManager && $this->context->module->moduleExist('rbac') && Yii::$app->user->can('updatePosts', [
                'created_by' => $model->created_by,
                'updated_by' => $model->updated_by
            ])) : ?>
            <div class="form-group pull-right">
                <?= Html::a(Yii::t('app/modules/news', 'Delete'), ['news/delete', 'id' => $model->id], [
                    'class' => 'btn btn-delete btn-danger',
                    'data-confirm' => Yii::t('app/modules/news', 'Are you sure you want to delete this post?'),
                    'data-method' => 'post',
                ]) ?>
                <?= Html::a(Yii::t('app/modules/news', 'Update'), ['news/update', 'id' => $model->id], ['class' => 'btn btn-edit btn-primary']) ?>
            </div>
        <?php endif; ?>
    </div>
</div>