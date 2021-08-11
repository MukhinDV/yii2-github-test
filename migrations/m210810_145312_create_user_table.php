<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m210810_145312_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => 'Uuid primary key',
            'login' => $this->string()->notNull()->unique()->comment('Логин'),
            'need_this_user' => $this->boolean()->notNull()->defaultValue(false)->comment('Поиск будет по этому пользователю'),

            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->comment('Дата обновления'),
        ]);

        $this->createIndex('idx-user-login', 'user', 'login');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
