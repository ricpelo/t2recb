<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Zapatos */

$this->title = 'Create Zapatos';
$this->params['breadcrumbs'][] = ['label' => 'Zapatos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zapatos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
