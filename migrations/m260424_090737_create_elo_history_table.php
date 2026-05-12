<?php

use yii\db\Migration;

class m260424_090737_create_elo_history_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('elo_history', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'game_id' => $this->integer()->null(),
            'elo_before' => $this->integer()->notNull(),
            'elo_after' => $this->integer()->notNull(),
            'change' => $this->integer()->notNull(),
            'reason' => $this->string()->notNull()->defaultValue('game'),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('elo_history');
    }
}