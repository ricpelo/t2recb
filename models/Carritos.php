<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "carritos".
 *
 * @property int $id
 * @property int $usuario_id
 * @property int $zapato_id
 * @property int $cantidad
 *
 * @property Usuarios $usuario
 * @property Zapatos $zapato
 */
class Carritos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carritos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'zapato_id', 'cantidad'], 'required'],
            [['usuario_id', 'zapato_id', 'cantidad'], 'default', 'value' => null],
            [['usuario_id', 'zapato_id'], 'integer'],
            [['cantidad'], 'integer', 'min' => 0],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::class, 'targetAttribute' => ['usuario_id' => 'id']],
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
            'usuario_id' => 'Usuario ID',
            'zapato_id' => 'Zapato ID',
            'cantidad' => 'Cantidad',
        ];
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::class, ['id' => 'usuario_id'])
            ->inverseOf('carritos');
    }

    /**
     * Gets query for [[Zapato]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZapato()
    {
        return $this->hasOne(Zapatos::class, ['id' => 'zapato_id'])
            ->inverseOf('carritos');
    }
}
