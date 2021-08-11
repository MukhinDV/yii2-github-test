<?php

namespace app\models;

use Yii;
use aracoool\uuid\{Uuid, UuidBehavior, UuidValidator};
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "repository".
 *
 * @property string $id
 * @property string $name Имя
 * @property string $user_id
 * @property int $repository_updated_at Дата обновления репозитория
 * @property int $created_at Дата создания
 * @property int|null $updated_at Дата обновления
 *
 * @property User $user
 */
class Repository extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'repository';
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
            [['name', 'user_id', 'repository_updated_at'], 'required'],
            [['id', 'user_id'], 'string'],
            [['repository_updated_at', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'user_id' => 'User ID',
            'repository_updated_at' => 'Дата обновления репозитория',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
