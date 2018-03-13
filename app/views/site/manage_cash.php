<?php

/* @var $this yii\web\View */

use yii\grid\GridView;

$this->title = 'Case Game Example';
?>
<div class="site-index">

    <div class="jumbotron">
        <h3>Manage cash prizes</h3>

    </div>
    <?php $dataProvider = $model->cashToTransferSearch() ?>
    <?= GridView::widget(array(
        'id' => 'category-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => new \app\models\Prize(),
        'columns' => array(
            'id',
            'amount',
            'user.username',
            array(
                'label' => 'action',
                'content' => function ($model, $key, $index, $column) {
                    return \yii\helpers\Html::a('send', ['site/send_prize', 'prize_id' => $model->id], ['class' => 'btn btn-success']);
                }

            )
        ),
    ));

    ?>

</div>
