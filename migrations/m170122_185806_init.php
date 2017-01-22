<?php

use yii\db\Migration;

/**
 * Initial database structure
 */
class m170122_185806_init extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'fullname' => $this->string(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->notNull(),
            'email' => $this->string(),
            'github' => $this->string(),
            'twitter' => $this->string(),
            'facebook' => $this->string(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);


        $this->createTable('{{%auth}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk-auth-user_id-user-id', '{{%auth}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

        $this->createTable('{{%project}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'url' => $this->string(),
            'is_opensource' => $this->boolean()->notNull()->defaultValue(false),
            'source_url' => $this->string(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'is_featured' => $this->boolean()->notNull()->defaultValue(false),
            'yii_version' => $this->string()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk-project-created_by-user-id', '{{%project}}', 'created_by', '{{%user}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-project-updated_by-user-id', '{{%project}}', 'updated_by', '{{%user}}', 'id', 'SET NULL');

        $this->createTable('{{%project_description}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer()->notNull(),
            'language' => $this->string()->notNull()->defaultValue('en-US'),
            'content' => $this->text()->notNull(),
        ], $tableOptions);
        
        $this->createTable('{{%project_user}}', [
            'project_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->addPrimaryKey('pk-project_user', '{{%project_user}}', ['project_id', 'user_id']);
        $this->addForeignKey('fk-project_user-project_id', '{{%project_user}}', 'project_id', '{{%project}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-project_user-user_id', '{{%project_user}}', 'user_id', '{{%user}}', 'id', 'CASCADE');


        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);


        $this->addForeignKey('fk-image-project_id', '{{%image}}', 'project_id', '{{%project}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-image-created_by-user-id', '{{%image}}', 'created_by', '{{%user}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-image-updated_by-user-id', '{{%image}}', 'updated_by', '{{%user}}', 'id', 'SET NULL');

        $this->createTable('{{%vote}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'project_id' => $this->integer()->notNull(),
            'value' => $this->smallInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->createIndex('idx-vote-unique', '{{%vote}}', ['user_id', 'project_id'], true);
        $this->addForeignKey('fk-vote-user_id', '{{%vote}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-vote-project_id', '{{%vote}}', 'project_id', '{{%project}}', 'id', 'CASCADE');

        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'icon' => $this->string(),
            'description' => $this->text(),
            'type' => $this->smallInteger()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%project_tag}}', [
            'project_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-project_tag', '{{%project_tag}}', ['project_id', 'tag_id']);
        $this->addForeignKey('fk-project_tag-project_id', '{{%project_tag}}', 'project_id', '{{%project}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-project_tag-tag_id', '{{%project_tag}}', 'tag_id', '{{%tag}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%project_tag}}');
        $this->dropTable('{{%tag}}');
        $this->dropTable('{{%vote}}');
        $this->dropTable('{{%image}}');
        $this->dropTable('{{%project_user}}');
        $this->dropTable('{{%project_description}}');
        $this->dropTable('{{%project}}');
        $this->dropTable('{{%auth}}');
        $this->dropTable('{{%user}}');
    }
}
