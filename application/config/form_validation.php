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
);