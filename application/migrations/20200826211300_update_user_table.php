<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_user_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_column('user', [
            'available_likes' => array(
                'type' => 'INT',
                'unsigned' => true,
                'default' => 20 //Ну не пустыми же их создавать?..
            ),
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('user', 'available_likes');
    }
}
