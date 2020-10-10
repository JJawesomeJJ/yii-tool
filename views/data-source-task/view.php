<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\tool\models\DataSourceTask */

$this->title = '查看:' . $model->id;
?>
<div class="log-operate-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'class_path',
            'pid',
            'task_id',
            'run_time',
            'intervals',
            'status',
            'description',
        ],
    ]) ?>

</div>



