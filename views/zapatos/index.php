<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ZapatosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Zapatos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zapatos-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Zapatos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'codigo',
            'denominacion',
            'precio:currency',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{anyadir}',
                'buttons' => [
                    'anyadir' => function ($url, $model, $key) {
                        return Html::a('Añadir al carrito', [
                            'carritos/meter',
                            'id' => $key,
                        ], ['class' => 'btn-sm btn-info']);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
