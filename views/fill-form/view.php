<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\tool\models\FillFormTool */

$this->title = '查看:' . $model->id;
?>
<div class="fill-form-tool-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'key',
            'value',
            'unique_hash',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>



