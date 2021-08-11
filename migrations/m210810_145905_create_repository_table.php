<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%repository}}`.
 */
class m210810_145905_create_repository_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%repository}}', [
            'id' => 'uuid primary key',
            'name' => $this->string()->notNull()->comment('Имя'),
            'user_id' => 'uuid not null',
            'repository_updated_at' => $this->dateTime()->notNull()->comment('Дата обновления репозитория'),

            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->comment('Дата обновления'),
        ]);

        $this->addForeignKey(
            'fk-repository-user_id', 'repository', 'user_id',
            'user', 'id');

        $this->createIndex('idx-repository-name', 'repository', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%repository}}');
    }
}
