<?php

use app\models\Carritos;
use yii\bootstrap4\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Carrito';
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['carritos/mas-ajax']);
$js = <<<EOT
    $('.boton-mas').click(function (ev) {
        var padre = $(this).closest('tr');
        var id = padre.data('key');
        ev.preventDefault();
        $.ajax({
            url: '$url',
            type: 'post',
            data: {
                id: id
            }
        })
        .done(function (data) {
            $(padre).children('td').eq(3).text(data.cantidad);
        });
        return false;
    });
EOT;
$this->registerJs($js);
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
    'data-method' => 'post',
]);
