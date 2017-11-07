<?php

use app\components\migration\BaseMigration;

class m171104_210242_create_comment_table extends BaseMigration
{
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'object_type' => $this->string(255)->notNull(),
            'object_id' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ], $this->tableOptions);

        $this->addForeignKey('fk-comment-created_by-user-id', '{{%comment}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->createIndex('idx-comment-object_type-object_id', '{{%comment}}', ['object_type', 'object_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%comment}}');
    }
}
