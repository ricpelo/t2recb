<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $nombre
 * @property string $password
 *
 * @property Carritos[] $carritos
 * @property Facturas[] $facturas
 */
class Usuarios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'password'], 'required'],
            [['nombre'], 'string', 'max' => 255],
            [['nombre'], 'unique'],
            [['password'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'password' => 'Password',
        ];
    }

    /**
     * Gets query for [[Carritos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarritos()
    {
        return $this->hasMany(Carritos::class, ['usuario_id' => 'id'])
            ->inverseOf('usuario');
    }

    /**
     * Gets query for [[Facturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFacturas()
    {
        return $this->hasMany(Facturas::class, ['usuario_id' => 'id'])
            ->inverseOf('usuario');
    }

    public function getLineas()
    {
        return $this->hasMany(Lineas::class, ['factura_id' => 'id'])
            ->via('facturas');
    }

    public function getZapatosComprados()
    {
        return $this->hasMany(Zapatos::class, ['id' => 'zapato_id'])
            ->via('lineas');
    }

    public function getZapatosEnCarrito()
    {
        return $this->hasMany(Zapatos::class, ['id' => 'zapato_id'])
            ->via('carritos');
    }
}
