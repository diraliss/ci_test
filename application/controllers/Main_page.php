<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 21:36
 */
class Main_page extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        App::get_ci()->load->model('User_model');
        App::get_ci()->load->model('Login_model');
        App::get_ci()->load->model('Post_model');
        App::get_ci()->load->model('Comment_model');

        if (is_prod())
        {
            die('In production it will be hard to debug! Run as development environment!');
        }
    }

    public function index()
    {
        $user = User_model::get_user();



        App::get_ci()->load->view('main_page', ['user' => User_model::preparation($user, 'default')]);
    }

    public function get_all_posts()
    {
        $posts =  Post_model::preparation(Post_model::get_all(), 'main_page');
        return $this->response_success(['posts' => $posts]);
    }

    public function get_post($post_id){ // or can be $this->input->post('news_id') , but better for GET REQUEST USE THIS

        $post_id = intval($post_id);

        if (empty($post_id)){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try
        {
            $post = new Post_model($post_id);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }


        $posts =  Post_model::preparation($post, 'full_info');
        return $this->response_success(['post' => $posts]);
    }


    public function comment($post_id, $type = 'post', $parent_id = null)
    {
        if (!User_model::is_logged()) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        $post_id = intval($post_id);
        $data = json_decode(App::get_ci()->security->xss_clean(App::get_ci()->input->raw_input_stream), true);

        if (empty($post_id) || !isset($data['message']) || empty($data['message'])) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try {
            $post = new Post_model($post_id);
        } catch (EmeraldModelNoDataException $ex) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        if ($type == 'comment') {
            try {
                $parent = new Comment_model($parent_id);
            } catch (EmeraldModelNoDataException $ex) {
                return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
            }
        }

        $comment = Comment_model::create([
            'user_id' => User_model::get_session_id(),
            'assign_id' => $post_id,
            'parent_id' => ($type == 'comment') ? $parent_id : null,
            'text' => $data['message']
        ]);
        $comment =  Comment_model::preparation([$comment], 'full_info');

        return $this->response_success(['comment' => $comment]);
    }


    public function login()
    {
        App::get_ci()->load->library('form_validation');

        $data = json_decode(App::get_ci()->security->xss_clean(App::get_ci()->input->raw_input_stream), true);

        App::get_ci()->form_validation->set_data($data);
        if (App::get_ci()->form_validation->run()) {
            $user_id = User_model::get_user_id_by_credentials($data['login'], $data['password']);
            if (!is_null($user_id)) {
                Login_model::start_session($user_id);

                return $this->response_success();
            } else {
                return $this->response_error('Invalid credentials', [], 400);
            }
        } else {
            return $this->response_error('Empty credentials', [], 400);
        }
    }


    public function logout()
    {
        Login_model::logout();
        redirect(site_url('/'));
    }

    public function add_money(){
        // todo: add money to user logic
        return $this->response_success(['amount' => rand(1,55)]);
    }

    public function buy_boosterpack(){
        // todo: add money to user logic
        return $this->response_success(['amount' => rand(1,55)]);
    }


    public function like(){
        // todo: add like post\comment logic
        return $this->response_success(['likes' => rand(1,55)]); // Колво лайков под постом \ комментарием чтобы обновить
    }

}
