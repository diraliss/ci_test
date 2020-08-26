<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'main_page/login' => array(
        array(
            'field' => 'login',
            'label' => 'Login',
            'rules' => 'required|valid_email'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required'
        ),
    ),
    'main_page/add_money' => array(
        array(
            'field' => 'user_id',
            'label' => 'User',
            'rules' => array(
                'required',
                'integer',
                function($id) {
                    App::get_ci()->load->model('User_model');
                    return User_model::exist($id);
                }
            )
        ),
        array(
            'field' => 'amount',
            'label' => 'Amount',
            'rules' => 'required|decimal'
        ),
    )
);