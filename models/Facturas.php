<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "facturas".
 *
 * @property int $id
 * @property int $usuario_id
 * @property string $created_at
 *
 * @property Usuarios $usuario
 * @property Lineas[] $lineas
 */
class Facturas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'facturas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id'], 'required'],
            [['usuario_id'], 'default', 'value' => null],
            [['usuario_id'], 'integer'],
            // [['created_at'], 'safe'],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::class, 'targetAttribute' => ['usuario_id' => 'id']],
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
            'created_at' => 'Created At',
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
            ->inverseOf('facturas');
    }

    /**
     * Gets query for [[Lineas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLineas()
    {
        return $this->hasMany(Lineas::class, ['factura_id' => 'id'])
            ->inverseOf('factura');
    }
}
