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
        App::get_ci()->load->model('Like_model');
        App::get_ci()->load->model('Boosterpack_model');

        if (is_prod()) {
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
        if (!User_model::is_logged()) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }
        $posts = Post_model::preparation(Post_model::get_all(), 'main_page');
        return $this->response_success(['posts' => $posts]);
    }

    public function get_all_boosterpacks()
    {
        if (!User_model::is_logged()) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }
        $boosterpacks = Boosterpack_model::preparation(Boosterpack_model::get_all(), 'main_page');
        return $this->response_success(['boosterpacks' => $boosterpacks]);
    }

    public function get_post($post_id)
    { // or can be $this->input->post('news_id') , but better for GET REQUEST USE THIS

        $post_id = intval($post_id);

        if (empty($post_id)) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try {
            $post = new Post_model($post_id);
        } catch (EmeraldModelNoDataException $ex) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }


        $posts = Post_model::preparation($post, 'full_info');
        return $this->response_success(['post' => $posts]);
    }

    public function get_current_user_info()
    {
        if (!User_model::is_logged()) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH, [], 403);
        }
        $user = User_model::get_user();
        return $this->response_success(['user' => User_model::preparation($user, 'default')]);
    }


    public function comment($post_id = null, $type = 'post', $parent_id = null)
    {
        if (!User_model::is_logged()) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH, [], 403);
        }
        $data = json_decode(App::get_ci()->security->xss_clean(App::get_ci()->input->raw_input_stream), true);

        $data['post_id'] = $post_id;
        $data['type'] = $type;
        $data['parent_id'] = $parent_id;

        App::get_ci()->load->library('form_validation');
        App::get_ci()->form_validation->set_data($data);
        if (App::get_ci()->form_validation->run() && ((($type == 'comment') && !empty($parent_id)) || ($type != 'comment'))) {
            $comment = Comment_model::create([
                'user_id' => User_model::get_session_id(),
                'assign_id' => $post_id,
                'parent_id' => ($type == 'comment') ? $parent_id : null,
                'text' => $data['message']
            ]);
            $comment = Comment_model::preparation([$comment], 'full_info');

            return $this->response_success(['comment' => $comment]);
        } else {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS, ['errors' => App::get_ci()->form_validation->error_array()]);
        }
    }


    public function login()
    {
        App::get_ci()->load->model('Users_auth_model');
        if (Users_auth_model::is_blocked()) {
            Users_auth_model::fail_login(['errors' => ['Authorization attempts ended, try later'], 'blocked_until' => Users_auth_model::get_blocked_until()]);

            return $this->response_error('Authorization attempts ended, try later', ['blocked_until' => Users_auth_model::get_blocked_until()], 403);
        }
        App::get_ci()->load->library('form_validation');

        $data = json_decode(App::get_ci()->security->xss_clean(App::get_ci()->input->raw_input_stream), true);

        App::get_ci()->form_validation->set_data($data);
        if (App::get_ci()->form_validation->run()) {
            $user_id = User_model::get_user_id_by_credentials($data['login'], $data['password']);
            if (!is_null($user_id)) {
                Login_model::start_session($user_id);
                Users_auth_model::success_login();

                return $this->response_success();
            } else {
                Users_auth_model::increase_login_attempts(['errors' => ['Invalid credentials']]);
                return $this->response_error('Invalid credentials', [], 400);
            }
        } else {
            Users_auth_model::increase_login_attempts(['errors' => App::get_ci()->form_validation->error_array()]);
            return $this->response_error('Empty credentials', [], 400);
        }
    }

    public function logout()
    {
        Login_model::logout();
        redirect(site_url('/'));
    }

    public function add_money()
    {
        App::get_ci()->load->model('Transaction_model');
        App::get_ci()->load->library('form_validation');

        $data = json_decode(App::get_ci()->security->xss_clean(App::get_ci()->input->raw_input_stream), true);
        $data['user_id'] = !isset($data['user_id']) ? User_model::get_session_id() : intval($data['user_id']);
        App::get_ci()->form_validation->set_data($data);

        if (App::get_ci()->form_validation->run()) {
            $user = new User_model($data['user_id']);
            $result = $user->add_money($data['amount'], $data);
            if ($result) {
                $user->reload();

                return $this->response_success(['user' => User_model::preparation($user, 'default')]); // Обновляется поле balance
            } else {
                return $this->response_error(CI_Core::RESPONSE_GENERIC_TRY_LATER);
            }
        } else {
            //Была некорректная попытка добавления денег
            Transaction_model::add_wrong_input_transaction($data['user_id'], $data['amount'], ['input' => $data, 'form_errors' => App::get_ci()->form_validation->error_array()]);
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS, App::get_ci()->form_validation->error_array());
        }
    }

    public function buy_boosterpack($id = null)
    {
        if (!User_model::is_logged()) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH, [], 403);
        }
        $id = intval($id);
        if (empty($id) || !Boosterpack_model::exist($id)) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }
        $user = User_model::get_user();
        $boosterpack = new Boosterpack_model($id);

        if ($user->get_wallet_balance() < $boosterpack->get_price()) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_UNAVAILABLE);
        }

        $result = $user->buy_boosterpack($boosterpack);
        if ($result) {
            $user->reload();

            return $this->response_success(['user' => User_model::preparation($user, 'default')]); // Обновляется поле balance и available_likes
        } else {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_TRY_LATER);
        }
    }

    public function like($type = 'post', $id = null)
    {
        if (!User_model::is_logged()) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH, [], 403);
        }
        if (empty($type) || is_null($id) || !intval($id)) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }
        $user = User_model::get_user();
        if ($user->get_available_likes() <= 0) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_UNAVAILABLE);
        }

        $object = null;
        switch ($type) {
            case 'post':
                if (!Post_model::exist($id)) {
                    return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
                } else {
                    $object = new Post_model($id);
                }
                break;
            case 'comment':
                if (!Comment_model::exist($id)) {
                    return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
                } else {
                    $object = new Comment_model($id);
                }
                break;
            default:
                return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }
        Like_model::create([
            'user_id' => User_model::get_session_id(),
            'entity_type' => $type,
            'entity_id' => $id
        ]);
        $object->reload();

        return $this->response_success(['likes' => $object->get_likes_count()]); // Кол-во лайков под постом \ комментарием чтобы обновить
    }

    //GET

    public function user_transactions()
    {
        if (!User_model::is_logged()) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH, [], 403);
        }

        App::get_ci()->load->model('Transaction_model');
        $filter = App::get_ci()->input->get('filter', true);
        $transactions = Transaction_model::get_user_transactions(User_model::get_session_id(), (isset($filter['state']) ? intval($filter['state']) : null));

        return $this->response_success(['transactions' => Transaction_model::preparation($transactions)]);
    }

    public function user_boosterpacks()
    {
        if (!User_model::is_logged()) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH, [], 403);
        }

        App::get_ci()->load->model('Users_boosterpack_model');
        $boosterpacks = Users_boosterpack_model::get_user_boosterpacks(User_model::get_session_id());

        return $this->response_success(['boosterpacks' => Users_boosterpack_model::preparation($boosterpacks)]);
    }
}
