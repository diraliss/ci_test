<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_transaction_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'type' => array(
                'type' => 'VARCHAR',
                'constraint' => '8',
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'state' => array(
                'type' => 'TINYINT',
                'unsigned' => TRUE,
            ),
            'extra' => array(
                'type' => 'TEXT'
            )
        ));
        $this->dbforge->add_field('`amount` DECIMAL(16,2) NOT NULL');
        $this->dbforge->add_field('`time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('transaction');
    }

    public function down()
    {
        $this->dbforge->drop_table('transaction');
    }
}
