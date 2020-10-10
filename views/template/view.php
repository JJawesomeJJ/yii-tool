<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\tool\models\FillForTemplate */

$this->title = '查看:' . $model->id;
?>
<div class="fill-for-template-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'template:ntext',
        ],
    ]) ?>

</div>



