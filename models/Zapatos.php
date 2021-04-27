<?php

namespace app\models;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "zapatos".
 *
 * @property int $id
 * @property float $codigo
 * @property string $denominacion
 * @property float|null $precio
 *
 * @property Carritos[] $carritos
 * @property Lineas[] $lineas
 */
class Zapatos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zapatos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'denominacion'], 'required'],
            [['denominacion'], 'string', 'max' => 255],
            [['codigo'], 'integer'],
            [['codigo'], function ($attribute, $params) {
                if (mb_strlen($this->$attribute) != 13) {
                    $this->addError($attribute, 'El código debe tener 13 dígitos exactamente.');
                }
            }],
            [['codigo'], 'unique'],
            [['precio'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'denominacion' => 'Denominacion',
            'precio' => 'Precio',
        ];
    }

    /**
     * Gets query for [[Carritos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarritos()
    {
        return $this->hasMany(Carritos::class, ['zapato_id' => 'id'])
            ->inverseOf('zapato');
    }

    /**
     * Gets query for [[Lineas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLineas()
    {
        return $this->hasMany(Lineas::class, ['zapato_id' => 'id'])
            ->inverseOf('zapato');
    }

    public static function findModel($id)
    {
        if (($model = static::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
