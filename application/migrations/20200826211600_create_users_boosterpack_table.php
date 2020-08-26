<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_users_boosterpack_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'boosterpack_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'added_likes' => array(
                'type' => 'INT',
                'constraint' => 11, //А кто его знает?...
                'unsigned' => TRUE,
            )
        ));
        $this->dbforge->add_field('`time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('users_boosterpack');
    }

    public function down()
    {
        $this->dbforge->drop_table('users_boosterpack');
    }
}
