<?php

use app\migrations\BaseMigration;

class m170402_173905_star extends BaseMigration
{
    public function up()
    {
        $this->createTable('{{%star}}', [
            'project_id' => $this->integer()->notNull()->comment('Project'),
            'user_id' => $this->integer()->notNull()->comment('User'),
            'star' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'PRIMARY KEY(project_id, user_id)'
        ], $this->tableOptions);
    }
    
    public function down()
    {
        $this->dropTable('{{%star}}');
    }
}
