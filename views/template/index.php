<?php

use yii\helpers\Html;
use yii\helpers\Url;
use dsj\components\grid\ResponsiveGridView;
use backend\modules\tool\models\FillFormModel;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '填报工具模版页';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fill-for-template-index">
    
        <p>
                 <?= Html::button('Create Fill For Template', ['class' => 'btn btn-success data-create','url' => Url::to(['create'])]) ?>
        </p>
    <p>
        <?= Html::a('导向构建', ['template/easy'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= ResponsiveGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

                'id',
            'key',
        [
            'attribute' => 'num',
            'label' => '该模板录入数量',
            'value' => function($model){
                FillFormModel::$unique_template_key=$model->key;
                return FillFormModel::find()->count();
            }
        ],
            ['class' => 'dsj\components\grid\ResponsiveActionColumn',
                'buttons' => [
                    'view' => function($url, $model, $key) {
                         return Html::button('生成CRUD', ['class' => 'btn btn-success data-create','url' => Url::to(['template',"id"=>$model->id])]);
                    },
//                    'update' => function($url, $model, $key) {
//                        return '';
//                    },
//                    'delete' => function($url, $model, $key) {
//                        return '';
//                    }
                ]],
    ],
    ]); ?>
</div>
