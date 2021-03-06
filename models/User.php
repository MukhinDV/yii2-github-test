<?php

namespace app\models;

use Yii;
use aracoool\uuid\{Uuid, UuidBehavior, UuidValidator};
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $login Логин
 * @property bool $need_this_user Поиск будет по этому пользователю
 * @property int $created_at Дата создания
 * @property int|null $updated_at Дата обновления
 *
 * @property Repository[] $repositories
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class
            ],
            [
                'class' => UuidBehavior::class,
                'version' => Uuid::V4
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], UuidValidator::class],
            [['login',], 'required'],
            [['id'], 'string'],
            [['need_this_user'], 'boolean'],
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['created_at', 'updated_at'], 'integer'],
            [['login'], 'string', 'max' => 255],
            [['login'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'need_this_user' => 'Поиск будет по этому пользователю',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[Repositories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRepositories()
    {
        return $this->hasMany(Repository::class, ['user_id' => 'id']);
    }
}
