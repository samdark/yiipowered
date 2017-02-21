<?php

use yii\db\Migration;

class m170221_212440_adjust_schema_for_tags extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tag}}', 'frequency', $this->integer()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%tag}}', 'frequency');
    }
}
