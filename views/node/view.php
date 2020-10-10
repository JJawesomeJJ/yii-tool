<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\tool\models\Node */

$this->title = '查看:' . $model->id;
?>
<div class="log-operate-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sql_string',
            'source_config',
            'desc_config',
            'fileds_mapping',
            'before_sql',
            'node_name',
            'node_desc',
        ],
    ]) ?>

</div>



