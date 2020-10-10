<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div style="display: block;position: relative;display: flex">
    <?= Html::button('新增', ['class' => 'btn btn-success data-create','url' => Url::to(['create'])]) ?>
    <div style="display: flex;position: absolute;right: 20px;">
        <button style="height: 34px;width:110px;display: flex;align-items: center;justify-content: center;" type="button" class="btn btn-primary"><a style="line-height:20px;height:34px;color: white;text-decoration: none;display: block;width: 110px;" href="<?=Url::to(['export-template'])?>" target="_blank">下载模版文件</a></button>
        <p>
            <?= Html::button('导入', ['class' => 'btn btn-success data-create','url' => Url::to(['import'])]) ?>
        </p>
    </div>
</div>
