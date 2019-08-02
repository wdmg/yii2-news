<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use wdmg\widgets\Editor;
use wdmg\widgets\SelectInput;

/* @var $this yii\web\View */
/* @var $model wdmg\news\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="news-form">
        <?php $form = ActiveForm::begin([
            'id' => "addNewsForm",
            'enableAjaxValidation' => true
        ]); ?>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?php
        if (($pageURL = $model->getPostUrl()) && $model->id)
            echo  Html::tag('label', Yii::t('app/modules/news', 'News URL')) . Html::tag('fieldset', Html::a($pageURL, $pageURL, [
                    'target' => '_blank',
                    'data-pjax' => 0
                ])) . '<br/>';
        ?>
        <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'excerpt')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'content')->widget(Editor::className(), [
            'options' => [],
            'pluginOptions' => []
        ]) ?>
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'keywords')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'status')->widget(SelectInput::className(), [
            'items' => $statusModes,
            'options' => [
                'class' => 'form-control'
            ]
        ]); ?>
        <hr/>
        <div class="form-group">
            <?= Html::a(Yii::t('app/modules/news', '&larr; Back to list'), ['news/index'], ['class' => 'btn btn-default pull-left']) ?>&nbsp;
            <?= Html::submitButton(Yii::t('app/modules/news', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
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
                }
            ).done(function(data) {
                if (data.alias && form.find('#news-alias').val().length == 0) {
                    form.find('#news-alias').val(data.alias);
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