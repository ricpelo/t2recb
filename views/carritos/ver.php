<?php

use app\models\Carritos;
use yii\bootstrap4\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;

$this->title = 'Carrito';
$this->params['breadcrumbs'][] = $this->title;
?>

<h2>Tu carrito:</h2>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'showFooter' => true,
    'columns' => [
        'zapato.codigo',
        'zapato.denominacion',
        'zapato.precio:currency',
        [
            'attribute' => 'cantidad',
            'footer' => 'TOTAL:',
        ],
        [
            'label' => 'Importe',
            'value' => function ($model, $key, $index, $column) {
                return $model->cantidad * $model->zapato->precio;
            },
            'format' => 'currency',
            'footer' => Yii::$app->formatter->asCurrency($total),
        ],
        [
            'class' => ActionColumn::class,
            'template' => '{mas} {menos}',
            'buttons' => [
                'mas' => function ($url, $model, $key) {
                    return Html::a('+', [
                        'carritos/mas',
                        'id' => $key,
                    ], [
                        'class' => 'btn-sm btn-info',
                        'data-method' => 'post',
                    ]);
                },
                'menos' => function ($url, $model, $key) {
                    return Html::a('-', [
                        'carritos/menos',
                        'id' => $key,
                    ], [
                        'class' => 'btn-sm btn-warning',
                        'data-method' => 'post',
                    ]);
                },
            ],
        ],
    ],
]) ?>

<?= Html::a('Vaciar carrito', ['carritos/vaciar'], [
    'class' => 'btn btn-danger',
    'data-method' => 'post',
]);
