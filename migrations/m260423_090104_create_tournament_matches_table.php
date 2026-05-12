<?php

use yii\db\Migration;

/**
 * Создание таблицы партий турнира
 */
class m260423_090104_create_tournament_matches_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('tournament_matches', [
            'id' => $this->primaryKey(),
            'tournament_id' => $this->integer()->notNull(),
            'round' => $this->integer()->notNull(),
            'white_player_id' => $this->integer()->notNull(),
            'black_player_id' => $this->integer()->notNull(),
            'result' => "enum('pending', 'white_win', 'black_win', 'draw') DEFAULT 'pending'",
            'winner_id' => $this->integer()->null(),
            'status' => "enum('pending', 'played') DEFAULT 'pending'",
            'played_at' => $this->dateTime()->null(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);


        $this->createIndex('idx-tm-tournament', 'tournament_matches', 'tournament_id');
        $this->createIndex('idx-tm-round', 'tournament_matches', 'round');

        // Внешние ключи
        $this->addForeignKey('fk-tm-tournament', 'tournament_matches', 'tournament_id', 'tournament', 'id', 'CASCADE');
        $this->addForeignKey('fk-tm-white', 'tournament_matches', 'white_player_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-tm-black', 'tournament_matches', 'black_player_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-tm-winner', 'tournament_matches', 'winner_id', 'user', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-tm-winner', 'tournament_matches');
        $this->dropForeignKey('fk-tm-black', 'tournament_matches');
        $this->dropForeignKey('fk-tm-white', 'tournament_matches');
        $this->dropForeignKey('fk-tm-tournament', 'tournament_matches');
        $this->dropTable('tournament_matches');
    }
}