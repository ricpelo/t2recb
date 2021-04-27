<?php

namespace app\controllers;

use app\models\Carritos;
use app\models\Zapatos;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class CarritosController extends Controller {
    public function behaviors()
    {
        return [
            'access' => [
                '__class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionVer()
    {

    }

    public function actionMeter($id)
    {
        $zapato = Zapatos::findModel($id);
        $carrito = Carritos::findOne([
            'usuario_id' => Yii::$app->user->id,
            'zapato_id' => $zapato->id,
        ]);

        if ($carrito !== null) {
            $carrito->cantidad++;
        } else {
            $carrito = new Carritos([
                'usuario_id' => Yii::$app->user->id,
                'zapato_id' => $zapato->id,
                'cantidad' => 1,
            ]);
        }

        $carrito->save();
        return $this->redirect(['zapatos/index']);
    }

    public function actionVaciar()
    {

    }
}
