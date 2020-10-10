<?php

use yii\helpers\Html;
use yii\helpers\Url;
use dsj\components\grid\ResponsiveGridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '填报工具模板';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fill-form-tool-index">
    
        <p>
                 <?= Html::button('添加', ['class' => 'btn btn-success data-create','url' => Url::to(['create'])]) ?>
        </p>

    <?= ResponsiveGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'key',
            'value',
            'unique_hash',
            'created_at',
            //'updated_at',

            ['class' => 'dsj\components\grid\ResponsiveActionColumn'],
    ],
    ]); ?>
</div>
