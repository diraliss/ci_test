<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 27.01.2020
 * Time: 10:10
 */
class User_model extends CI_Emerald_Model {
    const CLASS_TABLE = 'user';


    /** @var string */
    protected $email;
    /** @var string */
    protected $password;
    /** @var string */
    protected $personaname;
    /** @var string */
    protected $profileurl;
    /** @var string */
    protected $avatarfull;
    /** @var int */
    protected $rights;
    /** @var float */
    protected $wallet_balance;
    /** @var float */
    protected $wallet_total_refilled;
    /** @var float */
    protected $wallet_total_withdrawn;
    /** @var int */
    protected $available_likes;
    /** @var string */
    protected $time_created;
    /** @var string */
    protected $time_updated;

    /** @var Transaction_model[] */
    protected $transactions;

    private static $_current_user;

    /**
     * @return string
     */
    public function get_email(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function set_email(string $email)
    {
        $this->email = $email;
        return $this->save('email', $email);
    }

    /**
     * @return string|null
     */
    public function get_password(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    public function set_password(string $password)
    {
        $this->password = $password;
        return $this->save('password', $password);
    }

    /**
     * @return string
     */
    public function get_personaname(): string
    {
        return $this->personaname;
    }

    /**
     * @param string $personaname
     *
     * @return bool
     */
    public function set_personaname(string $personaname)
    {
        $this->personaname = $personaname;
        return $this->save('personaname', $personaname);
    }

    /**
     * @return string
     */
    public function get_avatarfull(): string
    {
        return $this->avatarfull;
    }

    /**
     * @param string $avatarfull
     *
     * @return bool
     */
    public function set_avatarfull(string $avatarfull)
    {
        $this->avatarfull = $avatarfull;
        return $this->save('avatarfull', $avatarfull);
    }

    /**
     * @return int
     */
    public function get_rights(): int
    {
        return $this->rights;
    }

    /**
     * @param int $rights
     *
     * @return bool
     */
    public function set_rights(int $rights)
    {
        $this->rights = $rights;
        return $this->save('rights', $rights);
    }

    /**
     * @return int
     */
    public function get_available_likes(): int
    {
        return $this->available_likes;
    }

    /**
     * @param int $available_likes
     *
     * @return bool
     */
    public function set_available_likes(int $available_likes)
    {
        $this->available_likes = $available_likes;
        return $this->save('available_likes', $available_likes);
    }
    
    /**
     * @param int $likes
     *
     * @return bool
     */
    public function add_to_available_likes(int $likes)
    {
        $this->available_likes += $likes;
        return $this->save('available_likes', $this->available_likes);
    }

    public function use_like()
    {
        //обновление не выполняется! использовать только внутри конструкции с trans_begin!
        App::get_ci()->db->where('id', $this->get_id());
        App::get_ci()->db->update('user', [
            'available_likes' => $this->get_available_likes() - 1,
        ]);
    }

    /**
     * @return float
     */
    public function get_wallet_balance(): float
    {
        return $this->wallet_balance;
    }

    /**
     * @param float $wallet_balance
     *
     * @return bool
     */
    public function set_wallet_balance(float $wallet_balance)
    {
        $this->wallet_balance = $wallet_balance;
        return $this->save('wallet_balance', $wallet_balance);
    }

    /**
     * @param float $sum
     *
     * @return bool
     */
    public function add_to_wallet_balance(float $sum)
    {
        $this->wallet_balance += $sum;
        return $this->save('wallet_balance', $this->wallet_balance);
    }

    /**
     * @return float
     */
    public function get_wallet_total_refilled(): float
    {
        return $this->wallet_total_refilled;
    }

    /**
     * @param float $wallet_total_refilled
     *
     * @return bool
     */
    public function set_wallet_total_refilled(float $wallet_total_refilled)
    {
        $this->wallet_total_refilled = $wallet_total_refilled;
        return $this->save('wallet_total_refilled', $wallet_total_refilled);
    }

    /**
     * @param float $sum
     *
     * @return bool
     */
    public function add_to_wallet_total_refilled(float $sum)
    {
        $this->wallet_total_refilled += $sum;
        return $this->save('wallet_total_refilled', $this->wallet_total_refilled);
    }

    /**
     * @return float
     */
    public function get_wallet_total_withdrawn(): float
    {
        return $this->wallet_total_withdrawn;
    }

    /**
     * @param float $wallet_total_withdrawn
     *
     * @return bool
     */
    public function set_wallet_total_withdrawn(float $wallet_total_withdrawn)
    {
        $this->wallet_total_withdrawn = $wallet_total_withdrawn;
        return $this->save('wallet_total_withdrawn', $wallet_total_withdrawn);
    }

    /**
     * @return string
     */
    public function get_time_created(): string
    {
        return $this->time_created;
    }

    /**
     * @param string $time_created
     *
     * @return bool
     */
    public function set_time_created(string $time_created)
    {
        $this->time_created = $time_created;
        return $this->save('time_created', $time_created);
    }

    /**
     * @return string
     */
    public function get_time_updated(): string
    {
        return $this->time_updated;
    }

    /**
     * @param string $time_updated
     *
     * @return bool
     */
    public function set_time_updated(string $time_updated)
    {
        $this->time_updated = $time_updated;
        return $this->save('time_updated', $time_updated);
    }

    public function get_transactions()
    {
        if (empty($this->transactions)) {
            $this->transactions = Transaction_model::get_user_transactions($this->get_id());
        }
        return $this->transactions;
    }

    function __construct($id = NULL)
    {
        parent::__construct();
        $this->set_id($id);
    }

    public function reload(bool $for_update = FALSE)
    {
        parent::reload($for_update);

        return $this;
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

    public function add_money($amount, $extra = []) {
        $amount = round(floatval($amount), 2);

        App::get_ci()->load->database();
        App::get_ci()->db->trans_begin();

        try {
            App::get_ci()->db->where('id', $this->get_id());
            App::get_ci()->db->update(self::CLASS_TABLE, [
                'wallet_balance' => $this->get_wallet_balance() + $amount,
                'wallet_total_refilled' => $this->get_wallet_total_refilled() + $amount
            ]);

            if (App::get_ci()->db->trans_status() === FALSE) {
                Transaction_model::add_wrong_input_transaction($this->get_id(), $amount, ['input'=> $extra, 'db_errors' => App::get_ci()->db->error()]);
                App::get_ci()->db->trans_rollback();
                return false;
            }
        } catch (\Exception $e) {
            Transaction_model::add_wrong_input_transaction($this->get_id(), $amount, ['input'=> $extra, 'error_message' => $e->getMessage()]);
            App::get_ci()->db->trans_rollback();
            return false;
        }
        App::get_ci()->db->trans_commit();
        Transaction_model::add_success_input_transaction($this->get_id(), $amount);
        return true;
    }

    /**
     * @param Boosterpack_model $boosterpack
     * @return false
     */
    public function buy_boosterpack($boosterpack)
    {
        App::get_ci()->load->database();
        App::get_ci()->load->model('Transaction_model');
        App::get_ci()->db->trans_begin();

        try {
            $price = $boosterpack->get_price();
            $likes = $boosterpack->get_bought_likes();

            App::get_ci()->db->where('id', $this->get_id());
            App::get_ci()->db->update(self::CLASS_TABLE, [
                'available_likes' => $this->get_available_likes() + $likes,
                'wallet_balance' => $this->get_wallet_balance() - $price,
                'wallet_total_withdrawn' => $this->get_wallet_total_withdrawn() + $price
            ]);

            App::get_ci()->db->insert('users_boosterpack', [
                'user_id' => $this->get_id(),
                'boosterpack_id' => $boosterpack->get_id(),
                'added_likes' => $likes
            ]);

            if (App::get_ci()->db->trans_status() === FALSE) {
                Transaction_model::add_wrong_output_transaction($this->get_id(), $price, ['boosterpack_id' => $boosterpack->get_id(), 'db_errors' => App::get_ci()->db->error()]);
                App::get_ci()->db->trans_rollback();
                return false;
            }
        } catch (\Exception $e) {
            App::get_ci()->db->trans_rollback();
            Transaction_model::add_wrong_output_transaction($this->get_id(), $boosterpack->get_price(), ['boosterpack_id' => $boosterpack->get_id(), 'error_message' => $e->getMessage()]);
            return false;
        }
        App::get_ci()->db->trans_commit();
        Transaction_model::add_success_output_transaction($this->get_id(), $boosterpack->get_price());
        return true;
    }

    /**
     * @return self[]
     * @throws Exception
     */
    public static function get_all()
    {

        $data = App::get_ci()->s->from(self::CLASS_TABLE)->many();
        $ret = [];
        foreach ($data as $i)
        {
            $ret[] = (new self())->set($i);
        }
        return $ret;
    }


    /**
     * @param int $id
     * @return boolean
     */
    public static function exist($id) {
        $count = App::get_ci()->s->from(self::CLASS_TABLE)->where('id', intval($id))->count();
        return ($count > 0);
    }

    /**
     * @param User_model|User_model[] $data
     * @param string $preparation
     * @return stdClass|stdClass[]
     * @throws Exception
     */
    public static function preparation($data, $preparation = 'default')
    {
        switch ($preparation)
        {
            case 'main_page':
                return self::_preparation_main_page($data);
            case 'default':
                return self::_preparation_default($data);
            default:
                throw new Exception('undefined preparation type');
        }
    }

    /**
     * @param User_model $data
     * @return stdClass
     */
    private static function _preparation_main_page($data)
    {
        $o = new stdClass();

        $o->id = $data->get_id();

        $o->personaname = $data->get_personaname();
        $o->avatarfull = $data->get_avatarfull();

        $o->time_created = $data->get_time_created();
        $o->time_updated = $data->get_time_updated();


        return $o;
    }


    /**
     * @param User_model $data
     * @return stdClass
     */
    private static function _preparation_default($data)
    {
        $o = new stdClass();

        if (!$data->is_loaded())
        {
            $o->id = NULL;
        } else {
            $o->id = $data->get_id();

            $o->personaname = $data->get_personaname();
            $o->avatarfull = $data->get_avatarfull();

            $o->time_created = $data->get_time_created();
            $o->time_updated = $data->get_time_updated();

            $o->balance = $data->get_wallet_balance();
            $o->available_likes = $data->get_available_likes();
        }

        return $o;
    }


    /**
     * Getting id from session
     * @return integer|null
     */
    public static function get_session_id(): ?int
    {
        return App::get_ci()->session->userdata('id');
    }

    /**
     * @return bool
     */
    public static function is_logged()
    {
        $steam_id = intval(self::get_session_id());
        return $steam_id > 0;
    }



    /**
     * Returns current user or empty model
     * @return User_model
     */
    public static function get_user()
    {
        if (! is_null(self::$_current_user)) {
            return self::$_current_user;
        }
        if ( ! is_null(self::get_session_id()))
        {
            self::$_current_user = new self(self::get_session_id());
            return self::$_current_user;
        } else
        {
            return new self();
        }
    }

    /**
     * Returns user id found by login (email) and password
     * @param string $login
     * @param string $password
     * @return int|null
     */
    public static function get_user_id_by_credentials(string $login, string $password): ?int
    {
        $user_id = App::get_ci()->s->from(self::CLASS_TABLE)->where(['email' => $login, 'password' => $password])->value('id');
        return ($user_id ? intval($user_id) : null);
    }

}
