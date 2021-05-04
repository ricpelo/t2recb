<?php

namespace app\controllers;

use app\models\Facturas;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class FacturasController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'crear' => ['POST'],
                ],
            ],
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

    public function actionCrear()
    {
        $usuario_id = Yii::$app->user->id;
        $factura = new Facturas([
            'usuario_id' => $usuario_id,
        ]);
        $factura->save();
        $sql = 'INSERT INTO lineas (factura_id, zapato_id, cantidad)
                SELECT :factura_id, zapato_id, cantidad
                  FROM carritos
                 WHERE usuario_id = :usuario_id';
        Yii::$app->db->createCommand($sql, [
            ':factura_id' => $factura->id,
            ':usuario_id' => $usuario_id,
        ])->execute();
        CarritosController::borrarCarritoUsuario();
        return $this->redirect(['zapatos/index']);
    }
}
