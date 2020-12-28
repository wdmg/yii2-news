<?php

use wdmg\widgets\AliasInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use wdmg\widgets\Editor;
use wdmg\widgets\SelectInput;
use wdmg\widgets\LangSwitcher;

/* @var $this yii\web\View */
/* @var $model wdmg\news\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">
    <?php
        echo LangSwitcher::widget([
            'label' => Yii::t('app/modules/news', 'Language version'),
            'model' => $model,
            'renderWidget' => 'button-group',
            'createRoute' => 'news/create',
            'updateRoute' => 'news/update',
            'supportLocales' => $this->context->module->supportLocales,
            //'currentLocale' => $this->context->getLocale(),
            'versions' => (isset($model->source_id)) ? $model->getAllVersions($model->source_id, true) : $model->getAllVersions($model->id, true),
            'options' => [
                'id' => 'locale-switcher',
                'class' => 'pull-right'
            ]
        ]);
    ?>

    <?php $form = ActiveForm::begin([
        'id' => "addNewsForm",
        'enableAjaxValidation' => true,
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->widget(AliasInput::class, [
        'labels' => [
            'edit' => Yii::t('app/modules/news', 'Edit'),
            'save' => Yii::t('app/modules/news', 'Save')
        ],
        'options' => [
            'baseUrl' => ($model->id) ? $model->url : Url::to($model->getRoute(), true)
        ]
    ])->label(Yii::t('app/modules/news', 'News URL')); ?>

    <?php
        if (isset(Yii::$app->redirects) && $model->url && ($model->status == $model::STATUS_PUBLISHED)) {
            if ($url = Yii::$app->redirects->check($model->url, false)) {
                echo Html::tag('div', Yii::t('app/modules/redirects', 'For this URL is active redirect to {url}', [
                    'url' => $url
                ]), [
                    'class' => "alert alert-warning"
                ]);
            }
        }
    ?>

    <?= $form->field($model, 'excerpt')->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'content')->widget(Editor::class, [
        'options' => [],
        'pluginOptions' => []
    ]) ?>

    <?php
        if ($model->image) {
            echo '<div class="row">';
            echo '<div class="col-xs-12 col-sm-3 col-md-2">' . Html::img($model->getImagePath(true) . '/' . $model->image, ['class' => 'img-responsive']) . '</div>';
            echo '<div class="col-xs-12 col-sm-9 col-md-10">' . $form->field($model, 'file')->fileInput() . '</div>';
            echo '</div><br/>';
        } else {
            echo $form->field($model, 'file')->fileInput();
        }
    ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">
                <a data-toggle="collapse" href="#newsMetaTags">
                    <?= Yii::t('app/modules/news', "SEO") ?>
                </a>
            </h6>
        </div>
        <div id="newsMetaTags" class="panel-collapse collapse">
            <div class="panel-body">
                <?= $form->field($model, 'title')->textInput() ?>
                <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
                <?= $form->field($model, 'keywords')->textarea(['rows' => 3]) ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">
                <a data-toggle="collapse" href="#newsOptions">
                    <?= Yii::t('app/modules/news', "Other options") ?>
                </a>
            </h6>
        </div>
        <div id="newsOptions" class="panel-collapse collapse">
            <div class="panel-body">
                <?= $form->field($model, 'in_sitemap', [
                        'template' => "{label}\n<br/>{input}\n{hint}\n{error}"
                    ])
                    ->checkbox(['label' => Yii::t('app/modules/news', '- display in the sitemap')])
                    ->label(Yii::t('app/modules/news', 'Sitemap'))
                ?>
                <?= $form->field($model, 'in_rss', [
                    'template' => "{label}\n<br/>{input}\n{hint}\n{error}"
                ])->checkbox(['label' => Yii::t('app/modules/news', '- display in the rss-feed')])->label(Yii::t('app/modules/news', 'RSS-feed'))
                ?>
                <?= $form->field($model, 'in_turbo', [
                    'template' => "{label}\n<br/>{input}\n{hint}\n{error}"
                ])->checkbox(['label' => Yii::t('app/modules/news', '- display in the turbo-pages')])->label(Yii::t('app/modules/news', 'Yandex turbo'))
                ?>
                <?= $form->field($model, 'in_amp', [
                    'template' => "{label}\n<br/>{input}\n{hint}\n{error}"
                ])->checkbox(['label' => Yii::t('app/modules/news', '- display in the AMP pages')])->label(Yii::t('app/modules/news', 'Google AMP'))
                ?>
            </div>
        </div>
    </div>

    <?= $form->field($model, 'locale')->widget(SelectInput::class, [
        'items' => $languagesList,
        'options' => [
            'id' => 'news-form-locale',
            'class' => 'form-control'
        ]
    ])->label(Yii::t('app/modules/news', 'Language')); ?>

    <?= $form->field($model, 'status')->widget(SelectInput::class, [
        'items' => $statusModes,
        'options' => [
            'class' => 'form-control'
        ]
    ]); ?>
    <hr/>
    <div class="form-group">
        <?= Html::a(Yii::t('app/modules/news', '&larr; Back to list'), ['news/index'], ['class' => 'btn btn-default pull-left']) ?>
        <?php if ((Yii::$app->authManager && $this->context->module->moduleExist('rbac') && Yii::$app->user->can('updatePosts', [
            'created_by' => $model->created_by,
            'updated_by' => $model->updated_by
        ])) || !$model->id) : ?>&nbsp;
            <?= Html::submitButton(Yii::t('app/modules/news', 'Save'), ['class' => 'btn btn-save btn-success pull-right']) ?>
        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php $this->registerJs(<<< JS
    $(document).ready(function() {
        function afterValidateAttribute(event, attribute, messages)
        {
            if (attribute.name && !attribute.alias && messages.length == 0) {
                var form = $(event.target);
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serializeArray(),
                }).done(function(data) {
                    if (data.alias && form.find('#news-alias').val().length == 0) {
                        form.find('#news-alias').val(data.alias);
                        form.find('#news-alias').change();
                        form.yiiActiveForm('validateAttribute', 'news-alias');
                    }
                }).fail(function () {
                    /*form.find('#options-type').val("");
                    form.find('#options-type').trigger('change');*/
                });
                return false; // prevent default form submission
            }
        }
        $("#addNewsForm").on("afterValidateAttribute", afterValidateAttribute);
    });
JS
); ?>