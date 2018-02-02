<?php

use app\components\migration\BaseMigration;

class m180202_175611_add_comment_notification extends BaseMigration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'notify_about_comment_on_email', $this->boolean()->defaultValue(false)->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'notify_about_comment_on_email');
    }
}
