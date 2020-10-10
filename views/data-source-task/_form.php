<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\tool\models\DataSourceTask */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="DataSourceTask-form">

    <?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'class_path')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'pid')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'task_id')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'run_time')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'intervals')->textInput(['maxlength' => true]) ?>
             <?= $form->field($model, 'status')->dropDownList(['是'=>'是','否'=>'否']) ?> 
            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
            
    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
