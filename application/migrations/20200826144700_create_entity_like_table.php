<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_entity_like_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'entity_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'entity_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            )
        ));
        $this->dbforge->add_field('`time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('entity_like');
    }

    public function down()
    {
        $this->dbforge->drop_table('entity_like');
    }
}
