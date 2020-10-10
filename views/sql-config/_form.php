<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
\backend\Assets\VueBundle::register($this);
/* @var $this yii\web\View */
/* @var $model backend\modules\tool\models\SqlConfig */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="SqlConfig-form" id="app">

    <?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
             <?= $form->field($model, 'Driver')->dropDownList(['mysql'=>'mysql','PostgreSQL'=>'PostgreSQL','oracle'=>'oracle','sqlserver'=>'sqlserver']) ?> 
            <?= $form->field($model, 'host')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'port')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'data_base')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'user')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'source_name')->textInput(['maxlength' => true]) ?>
            
    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
<!--        --><?//= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
