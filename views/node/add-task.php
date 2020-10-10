<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\tool\models\DataSourceTask */
/* @var $form yii\widgets\ActiveForm */
$js=<<<js
Date.prototype.Format = function (fmt) { // author: meizz
    var o = {
        "M+": this.getMonth() + 1, // 月份
        "d+": this.getDate(), // 日
        "h+": this.getHours(), // 小时
        "m+": this.getMinutes(), // 分
        "s+": this.getSeconds(), // 秒
        "q+": Math.floor((this.getMonth() + 3) / 3), // 季度
        "S": this.getMilliseconds() // 毫秒
    };
    if (/(y+)/.test(fmt))
        fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
            return fmt;
}
$("#run_now").click(function() {
  $("#datasourcetask-run_time").val((new Date().Format("yyyy-MM-dd hh:mm:ss")));
})
js;
$this->registerJs($js);
?>

<div class="DataSourceTask-form">

    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'task_id')->textInput(['maxlength' => true]) ?>
    <div style="display: flex">
        <?= $form->field($model, 'run_time')->widget(\kartik\datetime\DateTimePicker::classname(), [
                            'options' => ['placeholder' => ''],
                    //        'type' => DateTimePicker :: TYPE_COMPONENT_APPEND,
                            'removeButton' => false,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd hh:ii:ss',
                            ]
                        ]);?>
        <button id="run_now" class="btn-sm btn-primary btn" style="height: 40px;margin-top: 20px;">立即运行</button>
    </div>
            <?= $form->field($model, 'intervals')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>
    <?php if(\backend\modules\tool\models\DataSourceTask::find()->andWhere(["task_id"=>$model->id])->asArray()->one()):?>
    <div class="form-group">
        <?php if(\backend\modules\tool\models\DataSourceTask::find()->andWhere(["task_id"=>$model->id])->asArray()->one()["status"]==1):?>
        <?= Html::a('禁止运行',\dsj\components\helpers\Url::to(["switch"])."&id=".$model->id,['color'=>"red"]) ?>
        <?php else:?>
            <?= Html::a('开启运行',\dsj\components\helpers\Url::to(["switch"])."&id=".$model->id) ?>
        <?php endif;?>
    </div>
    <?php endif;?>
    <?php ActiveForm::end(); ?>

</div>
