<?php


class Users_boosterpack_model extends CI_Emerald_Model
{
    const CLASS_TABLE = 'users_boosterpack';

    /** @var int */
    protected $added_likes;
    /** @var int */
    protected $boosterpack_id;
    /** @var int */
    protected $user_id;
    /** @var string */
    protected $time_created;

    protected $user;
    protected $boosterpack;

    /**
     * @return int
     */
    public function get_added_likes(): int
    {
        return $this->added_likes;
    }

    /**
     * @param int $added_likes
     *
     * @return bool
     */
    public function set_added_likes(int $added_likes)
    {
        $this->added_likes = $added_likes;
        return $this->save('added_likes', $added_likes);
    }

    /**
     * @return int
     */
    public function get_user_id(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     *
     * @return bool
     */
    public function set_user_id(int $user_id)
    {
        $this->user_id = $user_id;
        return $this->save('user_id', $user_id);
    }

    /**
     * @return User_model
     */
    public function get_user(): User_model
    {
        if (empty($this->user)) {
            try {
                $this->user = new User_model($this->get_user_id());
            } catch (Exception $exception) {
                $this->user = new User_model();
            }
        }
        return $this->user;
    }


    /**
     * @return int
     */
    public function get_boosterpack_id(): int
    {
        return $this->boosterpack_id;
    }

    /**
     * @param int $boosterpack_id
     *
     * @return bool
     */
    public function set_boosterpack_id(int $boosterpack_id)
    {
        $this->boosterpack_id = $boosterpack_id;
        return $this->save('boosterpack_id', $boosterpack_id);
    }

    /**
     * @return Boosterpack_model
     */
    public function get_boosterpack(): Boosterpack_model
    {
        if (empty($this->boosterpack)) {
            try {
                $this->boosterpack = new Boosterpack_model($this->get_boosterpack_id());
            } catch (Exception $exception) {
                $this->boosterpack = new Boosterpack_model();
            }
        }
        return $this->boosterpack;
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

    public static function get_user_boosterpacks($user_id)
    {
        $query = ['user_id' => $user_id];
        $data = App::get_ci()->s->from(self::CLASS_TABLE)->where($query)->orderBy('time_created', 'ASC')->many();
        $ret = [];
        foreach ($data as $i) {
            $ret[] = (new self())->set($i);
        }
        return $ret;
    }

    /**
     * @param Users_boosterpack_model|Users_boosterpack_model[] $data
     * @param string $preparation
     * @return stdClass|stdClass[]
     * @throws Exception
     */
    public static function preparation($data, $preparation = 'default')
    {
        switch ($preparation) {
            case 'default':
                return self::_preparation_default($data);
            default:
                throw new Exception('undefined preparation type');
        }
    }


    /**
     * @param Users_boosterpack_model[] $data
     * @return stdClass[]
     */
    private static function _preparation_default($data)
    {
        $ret = [];

        foreach ($data as $d){
            $o = new stdClass();

            $o->id = $d->get_id();

            $o->boosterpack = Boosterpack_model::preparation($d->get_boosterpack());
            $o->added_likes = $d->get_added_likes();

            $ret[] = $o;
        }

        return $ret;
    }
}