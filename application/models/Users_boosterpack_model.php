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
}