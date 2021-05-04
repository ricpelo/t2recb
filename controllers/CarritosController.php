<?php

namespace app\controllers;

use app\models\Carritos;
use app\models\Zapatos;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\db\QueryBuilder;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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

        return $this->render('ver', [
            'dataProvider' => $dataProvider,
            'total' => $total,
        ]);
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
        (new Query())->createCommand()
            ->delete('carritos', [
                'usuario_id' => Yii::$app->user->id
            ])->execute();

        return $this->redirect(['carritos/ver']);
    }

    public function actionMas($id)
    {
        $carrito = $this->findModel($id);
        $carrito->cantidad++;
        $carrito->save();
        return $this->redirect(['carritos/ver']);
    }

    public function actionMenos($id)
    {
        $carrito = $this->findModel($id);

        if ($carrito->cantidad <= 1) {
            $carrito->delete();
        } else {
            $carrito->cantidad--;
            $carrito->save();
        }

        return $this->redirect(['carritos/ver']);
    }

    public function actionMasAjax()
    {
        $id = Yii::$app->request->post('id');
        $carrito = $this->findModel($id);
        $carrito->cantidad++;
        $carrito->save();
        $usuario_id = Yii::$app->user->id;
        $total = Carritos::total($usuario_id);
        $importe = $carrito->cantidad * $carrito->zapato->precio;
        return $this->asJson([
            'cantidad' => $carrito->cantidad,
            'importe' => Yii::$app->formatter->asCurrency($importe),
            'total' => Yii::$app->formatter->asCurrency($total),
        ]);
    }

    public function actionMenosAjax()
    {
        $id = Yii::$app->request->post('id');
        $carrito = $this->findModel($id);
        $usuario_id = Yii::$app->user->id;
        $total = Carritos::total($usuario_id);

        if ($carrito->cantidad <= 1) {
            $carrito->delete();
            $cantidad = 0;
            $importe = 0;
        } else {
            $carrito->cantidad--;
            $carrito->save();
            $cantidad = $carrito->cantidad;
            $importe = $cantidad * $carrito->zapato->precio;
        }

        return $this->asJson([
            'cantidad' => $cantidad,
            'importe' => Yii::$app->formatter->asCurrency($importe),
            'total' => Yii::$app->formatter->asCurrency($total),
        ]);
    }

    protected static function findModel($id)
    {
        if (($model = Carritos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
