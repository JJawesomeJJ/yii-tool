<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\tool\models\SqlConfig */

$this->title = '查看:' . $model->id;
?>
<div class="log-operate-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'Driver',
            'host',
            'port',
            'data_base',
            'user',
            'password',
            'source_name',
        ],
    ]) ?>

</div>



