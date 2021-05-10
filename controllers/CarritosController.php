<?php

namespace app\controllers;

use app\models\Carritos;
use app\models\Facturas;
use app\models\Usuarios;
use app\models\Zapatos;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CarritosController extends Controller {
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'meter' => ['POST'],
                    'vaciar' => ['POST'],
                    'mas' => ['POST'],
                    'menos' => ['POST'],
                    'crearFactura' => ['POST'],
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

    public function actionVer()
    {
        return $this->render('ver', $this->datosVista());
    }

    public function actionMeter($id)
    {
        Yii::$app->user->setReturnUrl(['carritos/meter', 'id' => $id]);

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

    public function actionMeterAjax()
    {
        $id = Yii::$app->request->post('id');
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
        return $this->asJson([
            'cantidad' => Usuarios::cantidadEnCarrito(),
        ]);
    }

    public function actionVaciar()
    {
        $this->borrarCarritoUsuario();
        return $this->redirect(['carritos/ver']);
    }

    public function actionVaciarAjax()
    {
        $this->borrarCarritoUsuario();
        return $this->devolverDatosVista();
    }

    public function actionMas($id)
    {
        $this->masZapato($id);
        return $this->redirect(['carritos/ver']);
    }

    public function actionMasAjax()
    {
        $id = Yii::$app->request->post('id');
        $this->masZapato($id);
        return $this->devolverDatosVista();
    }

    public function actionMenos($id)
    {
        $this->menosZapato($id);
        return $this->redirect(['carritos/ver']);
    }

    public function actionMenosAjax()
    {
        $id = Yii::$app->request->post('id');
        $this->menosZapato($id);
        return $this->devolverDatosVista();
    }

    public function actionCrearFactura()
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
        $this->borrarCarritoUsuario();
        return $this->redirect(['zapatos/index']);
    }

    protected static function findModel($id)
    {
        if (($model = Carritos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function datosVista()
    {
        $usuario_id = Yii::$app->user->id;
        $total = Carritos::total($usuario_id);
        $query = Carritos::find()
            ->where([
                'usuario_id' => $usuario_id,
            ])
            ->orderBy('id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return [
            'dataProvider' => $dataProvider,
            'total' => $total,
        ];
    }

    private function masZapato($id) {
        $carrito = $this->findModel($id);
        $carrito->cantidad++;
        $carrito->save();
    }

    private function menosZapato($id) {
        $carrito = $this->findModel($id);

        if ($carrito->cantidad <= 1) {
            $carrito->delete();
        } else {
            $carrito->cantidad--;
            $carrito->save();
        }
    }

    private function devolverDatosVista()
    {
        return $this->asJson([
            'carrito' => $this->renderAjax('_carrito',
                $this->datosVista()
            ),
            'cantidad' => Usuarios::cantidadEnCarrito(),
        ]);
    }

    public function borrarCarritoUsuario()
    {
        (new Query())->createCommand()
            ->delete('carritos', [
                'usuario_id' => Yii::$app->user->id
            ])->execute();
    }
}
