<?php


class Users_auth_model extends CI_Emerald_Model
{
    const CLASS_TABLE = 'users_auth';

    /** @var string */
    protected $ip;
    /** @var string */
    protected $user_agent;
    /** @var int */
    protected $state;
    /** @var string */
    protected $extra;
    /** @var int|null */
    protected $user_id;
    /** @var string */
    protected $time_created;

    /**
     * @return int|null
     */
    public function get_user_id(): ?int
    {
        return $this->user_id;
    }

    /**
     * @return int
     */
    public function get_state(): int
    {
        return $this->state;
    }

    /**
     * @return string|null
     */
    public function get_ip(): ?string
    {
        return $this->ip;
    }

    /**
     * @return string|null
     */
    public function get_user_agent(): ?string
    {
        return $this->user_agent;
    }

    /**
     * @return array|null
     */
    public function get_extra(): ?array
    {
        $extra = $this->extra;
        return json_decode($extra, true);
    }

    /**
     * @return string
     */
    public function get_time_created(): string
    {
        return $this->time_created;
    }


    function __construct($id = NULL)
    {
        parent::__construct();
        $this->set_id($id);
    }

    public static function create(array $data)
    {
        App::get_ci()->s->from(self::CLASS_TABLE)->insert($data)->execute();
        return new static(App::get_ci()->s->get_insert_id());
    }

    public function delete()
    {
        $this->is_loaded(TRUE);
        App::get_ci()->s->from(self::CLASS_TABLE)->where(['id' => $this->get_id()])->delete()->execute();
        return (App::get_ci()->s->get_affected_rows() > 0);
    }

    public static function is_blocked()
    {
        $block = App::get_ci()->session->userdata('blocked_until');
        if (is_null($block)) return false;
        if (empty($block) || !intval($block) || $block < time()) {
            App::get_ci()->session->unset_userdata('blocked_until');
            App::get_ci()->session->set_userdata('login_attempts', 0);
            return false;
        }
        return true;
    }

    public static function get_blocked_until()
    {
        return App::get_ci()->session->userdata('blocked_until');
    }

    public static function get_login_attempts()
    {
        $attempts = App::get_ci()->session->userdata('login_attempts');
        if (is_null($attempts)) {
            App::get_ci()->session->set_userdata('login_attempts', 0);
            return 0;
        }
        return $attempts;
    }

    public static function increase_login_attempts($extra = [])
    {
        $attempts = Users_auth_model::get_login_attempts() + 1;
        App::get_ci()->session->set_userdata('login_attempts', $attempts);

        self::fail_login($extra);

        if ($attempts >= 5) {
            App::get_ci()->session->set_userdata('blocked_until', time() + 60 * 10);
        }
    }

    public static function success_login()
    {
        self::create([
            'user_id' => App::get_ci()->session->userdata('id'),
            'state' => 1,
            'ip' => App::get_ci()->input->ip_address(),
            'user_agent' => App::get_ci()->input->user_agent(true),
            'extra' => json_encode([
                'attempt' => self::get_login_attempts()
            ])
        ]);
    }

    public static function fail_login($extra = []) {
        $extra = array_merge([
            'attempt' => self::get_login_attempts()
        ], $extra);

        self::create([
            'user_id' => null,
            'state' => 0,
            'ip' => App::get_ci()->input->ip_address(),
            'user_agent' => App::get_ci()->input->user_agent(true),
            'extra' => json_encode($extra)
        ]);
    }
}