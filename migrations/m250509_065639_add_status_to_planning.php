<?php

use yii\db\Migration;

/**
 * Class m250509_065639_add_status_to_planning
 */
class m250509_065639_add_status_to_planning extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250509_065639_add_status_to_planning cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250509_065639_add_status_to_planning cannot be reverted.\n";

        return false;
    }
    */
}
