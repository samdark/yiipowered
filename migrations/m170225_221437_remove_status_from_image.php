<?php

use yii\db\Migration;

class m170225_221437_remove_status_from_image extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('{{%image}}', 'status');
    }

    public function safeDown()
    {
        $this->addColumn('{{%image}}', 'status', $this->smallInteger()->notNull()->defaultValue(10));
    }
}
