<?php

use app\components\migration\BaseMigration;

class m220209_211801_add_verification_and_checks_to_project extends BaseMigration
{
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'verified', $this->boolean()->defaultValue(false)->notNull());
        $this->addColumn('{{%project}}', 'check_result', $this->string());
        $this->addColumn('{{%project}}', 'check_log', $this->text());

        $this->createIndex('idx-project-verified', '{{%project}}', 'verified');
        $this->createIndex('idx-project-check_result', '{{%project}}', 'check_result');
    }

    public function safeDown()
    {
        $this->dropIndex('idx-project-verified', '{{%project}}');
        $this->dropIndex('idx-project-check_result', '{{%project}}');

        $this->dropColumn('{{%project}}', 'verified');
        $this->dropColumn('{{%project}}', 'check_result');
        $this->dropColumn('{{%project}}', 'check_log');
    }
}
