<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
                array('validate_user', function ($id) {
                    App::get_ci()->load->model('User_model');
                    return User_model::exist($id);
                })
            ),
            'errors' => array(
                'validate_user' => 'User not exist.',
            ),
        ),
        array(
            'field' => 'amount',
            'label' => 'Amount',
            'rules' => 'required|numeric'
        ),
    )
);