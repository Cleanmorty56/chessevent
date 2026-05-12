<?php

use yii\db\Migration;

/**
 * Создание таблицы пропусков (bye)
 */
class m260423_090437_create_tournament_byes_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('tournament_byes', [
            'id' => $this->primaryKey(),
            'tournament_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'round' => $this->integer()->notNull(),
            'points' => $this->decimal(3, 1)->defaultValue(1.0),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-tb-tournament', 'tournament_byes', 'tournament_id');
        $this->addForeignKey('fk-tb-tournament', 'tournament_byes', 'tournament_id', 'tournament', 'id', 'CASCADE');
        $this->addForeignKey('fk-tb-user', 'tournament_byes', 'user_id', 'user', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-tb-user', 'tournament_byes');
        $this->dropForeignKey('fk-tb-tournament', 'tournament_byes');
        $this->dropTable('tournament_byes');
    }
}