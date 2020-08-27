<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_users_auth_table extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'ip' => array(
                'type' => 'VARCHAR',
                'constraint' => '63',
                'null' => true
            ),
            'user_agent' => array(
                'type' => 'TEXT',
                'null' => true
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => true
            ),
            'state' => array(
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'default' => 0
            ),
            'extra' => array(
                'type' => 'TEXT',
                'null' => true
            ),
        ));
        $this->dbforge->add_field('`time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('users_auth');
    }

    public function down()
    {
        $this->dbforge->drop_table('users_auth');
    }
}
