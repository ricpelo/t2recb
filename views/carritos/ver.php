<?php

$this->title = 'Carrito';
$this->params['breadcrumbs'][] = $this->title;
?>

<h2>Tu carrito:</h2>

<div id="carrito">
    <?= $this->render('_carrito', [
        'dataProvider' => $dataProvider,
        'total' => $total,
    ]) ?>
</div>
