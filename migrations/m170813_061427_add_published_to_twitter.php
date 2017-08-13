<?php

use yii\db\Migration;

class m170813_061427_add_published_to_twitter extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'published_to_twitter', $this->boolean()->defaultValue(false)->notNull());
        $this->createIndex('idx-project-status-published_to_twitter', '{{%project}}', ['status', 'published_to_twitter']);
    }

    public function safeDown()
    {
        $this->dropIndex('idx-project-status-published_to_twitter', '{{%project}}');
        $this->dropColumn('{{%project}}', 'published_to_twitter');
    }   
}
