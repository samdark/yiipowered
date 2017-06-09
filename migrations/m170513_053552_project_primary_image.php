<?php

use app\components\Migration;

class m170513_053552_project_primary_image extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'primary_image_id', $this->integer()->null()->comment('Primary image'));
        $this->addForeignKey('fk-project-primary_image_id', '{{%project}}', 'primary_image_id', '{{%image}}', 'id', 'SET NULL');
        
        $query = "UPDATE project p SET p.primary_image_id = (SELECT i.id FROM image i WHERE i.project_id = p.id ORDER BY i.id LIMIT 1)";
        $this->execute($query);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-project-primary_image_id', '{{%project}}');
        $this->dropColumn('{{%project}}', 'primary_image_id');
    }
}