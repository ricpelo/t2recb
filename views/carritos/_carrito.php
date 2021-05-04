<?php

use yii\bootstrap4\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Url;

$urlMas = Url::to(['carritos/mas-ajax']);
$urlMenos = Url::to(['carritos/menos-ajax']);
$urlVaciar = Url::to(['carritos/vaciar-ajax']);
$js = <<<EOT
    function manejador(urlManejador) {
        return function (ev) {
            var padre = $(this).closest('tr');
            var id = padre.data('key');
            ev.preventDefault();
            $.ajax({
                url: urlManejador,
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
        }
    }

    $('.boton-mas').click(manejador('$urlMas'));
    $('.boton-menos').click(manejador('$urlMenos'));
    $('#boton-vaciar').click(manejador('$urlVaciar'));
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

<?= Html::a('Vaciar carrito', ['carritos/vaciar'], [
    'class' => 'btn btn-danger',
    'id' => 'boton-vaciar',
    'data-method' => 'post',
]);
