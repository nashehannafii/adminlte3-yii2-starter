<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property string $id
 * @property string|null $level_id
 * @property string|null $rack_id
 * @property string|null $nama_item
 * @property string|null $deskripsi_item
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'level_id', 'rack_id'], 'string', 'max' => 36],
            [['nama_item'], 'string', 'max' => 200],
            [['deskripsi_item'], 'string', 'max' => 1000],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'level_id' => Yii::t('app', 'Level ID'),
            'rack_id' => Yii::t('app', 'Rack ID'),
            'nama_item' => Yii::t('app', 'Nama Item'),
            'deskripsi_item' => Yii::t('app', 'Deskripsi Item'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
