<?php

use app\components\Migration;

class m170407_231101_bookmark extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%bookmark}}', [
            'project_id' => $this->integer()->notNull()->comment('Project'),
            'user_id' => $this->integer()->notNull()->comment('User'),
            'created_at' => $this->integer()->notNull(),
            'PRIMARY KEY(project_id, user_id)'
        ]);
    }
    
    public function safeDown()
    {
        $this->dropTable('{{%bookmark}}');
    }
}
