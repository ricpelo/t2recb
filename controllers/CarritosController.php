<?php

namespace app\controllers;

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

    }

    public function actionVaciar()
    {

    }
}
