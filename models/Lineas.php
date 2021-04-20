<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lineas".
 *
 * @property int $id
 * @property int $factura_id
 * @property int $zapato_id
 * @property int $cantidad
 *
 * @property Facturas $factura
 * @property Zapatos $zapato
 */
class Lineas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lineas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['factura_id', 'zapato_id', 'cantidad'], 'required'],
            [['factura_id', 'zapato_id', 'cantidad'], 'default', 'value' => null],
            [['factura_id', 'zapato_id'], 'integer'],
            [['cantidad'], 'integer', 'min' => 0],
            [['factura_id'], 'exist', 'skipOnError' => true, 'targetClass' => Facturas::class, 'targetAttribute' => ['factura_id' => 'id']],
            [['zapato_id'], 'exist', 'skipOnError' => true, 'targetClass' => Zapatos::class, 'targetAttribute' => ['zapato_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'factura_id' => 'Factura ID',
            'zapato_id' => 'Zapato ID',
            'cantidad' => 'Cantidad',
        ];
    }

    /**
     * Gets query for [[Factura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFactura()
    {
        return $this->hasOne(Facturas::class, ['id' => 'factura_id'])
            ->inverseOf('lineas');
    }

    /**
     * Gets query for [[Zapato]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZapato()
    {
        return $this->hasOne(Zapatos::class, ['id' => 'zapato_id'])
            ->inverseOf('lineas');
    }
}
