<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_transaction_table extends CI_Migration
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
            'type' => array(
                'type' => 'VARCHAR',
                'constraint' => '7',
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
            'amount' => array(
                'type' => 'VARCHAR', //чтобы записалось ЛЮБОЕ значение
                'constraint' => '255',
                'null' => true
            ),
        ));
        $this->dbforge->add_field('`time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('transaction');
    }

    public function down()
    {
        $this->dbforge->drop_table('transaction');
    }
}
