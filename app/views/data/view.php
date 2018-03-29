<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Data */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            [
            	'attribute' => 'image',
                'format' => ['image', ['width' => '150', 'height' => '150']],
                'value' => function ($data) {
                    return $data->imageUrl;
                }
            ],
        ],
    ]) ?>

</div>
