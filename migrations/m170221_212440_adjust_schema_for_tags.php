<?php

use yii\db\Migration;

class m170221_212440_adjust_schema_for_tags extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%tag}}', 'frequency', $this->integer()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%tag}}', 'frequency');
    }
}
