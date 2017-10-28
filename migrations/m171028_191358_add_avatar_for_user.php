<?php

use yii\db\Migration;

class m171028_191358_add_avatar_for_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'avatar', $this->string(60)->after('fullname'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'avatar');
    }
}
