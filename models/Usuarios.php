<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

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
class Usuarios extends \yii\db\ActiveRecord implements IdentityInterface
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

    public static function findByUsername($username)
    {
        return static::findOne(['nombre' => $username]);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
    }

    public function validateAuthKey($authKey)
    {
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword(
            $password,
            $this->password
        );
    }

    public static function cantidadEnCarrito()
    {
        if (Yii::$app->user->isGuest) {
            return 'Sin carrito';
        } else {
            $cantidad = static::findOne(Yii::$app->user->id)
                ->getCarritos()->sum('cantidad');
            return "Ver carrito ($cantidad)";
        }
    }
}
