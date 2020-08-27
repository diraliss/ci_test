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
    'main_page/comment' => array(
        array(
            'field' => 'post_id',
            'label' => 'Post',
            'rules' => array(
                'required',
                'integer',
                array('validate_post', function ($id) {
                    App::get_ci()->load->model('Post_model');
                    return Post_model::exist($id);
                })
            ),
            'errors' => array(
                'validate_post' => 'Post not exist.',
            ),
        ),
        array(
            'field' => 'type',
            'label' => 'Type',
            'rules' => 'in_list[post,comment]'
        ),
        array(
            'field' => 'post_id',
            'label' => 'Post',
            'rules' => array(
                'integer',
                array('validate_comment_parent', function ($id) {
                    App::get_ci()->load->model('Comment_model');
                    return Comment_model::exist($id);
                })
            ),
            'errors' => array(
                'validate_comment_parent' => 'Comment not exist.',
            ),
        ),
        array(
            'field' => 'message',
            'label' => 'Message',
            'rules' => 'required|regex_match[/[а-яА-Яa-zA-Z\d\s\-_@]*/]'
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