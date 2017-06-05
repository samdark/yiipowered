<?php

use app\components\Migration;

class m170513_053552_project_primary_image extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'primary_image_id', $this->integer()->null()->comment('Primary image'));
        
        $query = "UPDATE project p SET primary_image_id = (SELECT id FROM image i WHERE i.project_id = p.id ORDER BY id LIMIT 1)";
        $this->execute($query);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%project}}', 'primary_image_id');
    }
}