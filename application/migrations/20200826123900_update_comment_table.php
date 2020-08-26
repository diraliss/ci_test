<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_comment_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_column('comment', [
            'parent_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ),
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('comment', 'parent_id');
    }
}
