<?php

use yii\helpers\Html;
use yii\helpers\Url;
use dsj\components\grid\ResponsiveGridView;
use yii\widgets\ActiveForm;
use backend\modules\tool\models\DataSourceTask;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model backend\modules\tool\models\DataSourceTask */
\backend\modules\tool\Assets\SeachBundle::register($this);
$this->title = '运行控制';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="DataSourceTask-index">
    <?= \backend\modules\tool\helpers\widgets\ExcelButton::widget([])?>
<?php $form = ActiveForm::begin(['method'=>'get']); ?>
 <?= $form->field($model, 'class_path')->dropDownList(DataSourceTask::GetTypeSelect('class_path')) ?> 

<?= Html::submitButton('搜索', ['class' => 'btn btn-primary btn-search']) ?>
    <button type="button" class="btn btn-default reload">重置</button>
<?php ActiveForm::end(); ?>
    <?= ResponsiveGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'class_path',
            'pid',
            'task_id',
            'run_time',
            'intervals',
            'status',
            'description',
            ['class' => 'dsj\components\grid\ResponsiveActionColumn'],
        ],
    ]); ?>
</div>
