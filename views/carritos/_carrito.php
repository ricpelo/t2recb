<?php

use yii\bootstrap4\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Url;

$urlMas = Url::to(['carritos/mas-ajax']);
$urlMenos = Url::to(['carritos/menos-ajax']);
$js = <<<EOT
    $('.boton-mas').click(function (ev) {
        var padre = $(this).closest('tr');
        var id = padre.data('key');
        ev.preventDefault();
        $.ajax({
            url: '$urlMas',
            type: 'post',
            data: {
                id: id
            }
        })
        .done(function (data) {
            $('#carrito').html(data.carrito);
            $('#ver-carrito').html(data.cantidad);
        });
        return false;
    });

    $('.boton-menos').click(function (ev) {
        var padre = $(this).closest('tr');
        var id = padre.data('key');
        ev.preventDefault();
        $.ajax({
            url: '$urlMenos',
            type: 'post',
            data: {
                id: id
            }
        })
        .done(function (data) {
            $('#carrito').html(data.carrito);
            $('#ver-carrito').html(data.cantidad);
        });
        return false;
    });
EOT;
$this->registerJs($js);
?>

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
                        'class' => 'btn-sm btn-info boton-mas',
                        'data-method' => 'post',
                    ]);
                },
                'menos' => function ($url, $model, $key) {
                    return Html::a('-', [
                        'carritos/menos',
                        'id' => $key,
                    ], [
                        'class' => 'btn-sm btn-warning boton-menos',
                        'data-method' => 'post',
                    ]);
                },
            ],
        ],
    ],
]) ?>
