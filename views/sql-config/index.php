<?php

use yii\helpers\Html;
use yii\helpers\Url;
use dsj\components\grid\ResponsiveGridView;
use yii\widgets\ActiveForm;
use backend\modules\tool\models\SqlConfig;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model backend\modules\tool\models\SqlConfig */
\backend\modules\tool\Assets\SeachBundle::register($this);
\backend\modules\tool\Assets\LineBundle::register($this);
$this->title = '数据源';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="SqlConfig-index">
    <?= \backend\modules\tool\helpers\widgets\ExcelButton::widget([])?>
<?php $form = ActiveForm::begin(['method'=>'get']); ?>
 <?= $form->field($model, 'source_name')->dropDownList(SqlConfig::GetTypeSelect('source_name')) ?> 

<?= Html::submitButton('搜索', ['class' => 'btn btn-primary btn-search']) ?>
    <button type="button" class="btn btn-default reload">重置</button>
<?php ActiveForm::end(); ?>
    <?= ResponsiveGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'Driver',
            'host',
            'port',
            'data_base',
            'user',
            'password',
            'source_name',
            ['class' => 'dsj\components\grid\ResponsiveActionColumn'],
        ],
    ]); ?>
</div>
