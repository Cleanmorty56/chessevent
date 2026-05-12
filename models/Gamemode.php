<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gamemode".
 *
 * @property int $id
 * @property string $name
 * @property string $control_time
 *
 * @property Tournament[] $tournaments
 */
class Gamemode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gamemode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'control_time'], 'required'],
            [['name'], 'string', 'max' => 80],
            [['control_time'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'control_time' => 'Control Time',
        ];
    }

    /**
     * Gets query for [[Tournaments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTournaments()
    {
        return $this->hasMany(Tournament::class, ['gamemode_id' => 'id']);
    }
}
