<?php


class Like_model extends CI_Emerald_Model
{
    const CLASS_TABLE = 'entity_like';
    
    const ENTITY_TYPES = [
        'post',
        'comment'
    ];

    /** @var int */
    protected $entity_id;
    /** @var string */
    protected $entity_type;
    /** @var int */
    protected $user_id;
    /** @var string */
    protected $time_created;

    /**
     * @var User_model
     */
    protected $user;

    /**
     * @param string $type
     * @param int $id
     * @return self[]
     */
    public static function get_all_by_entity_id(string $type, int $id)
    {
        $data = App::get_ci()->s->from(self::CLASS_TABLE)->where(['entity_type' => $type, 'entity_id' => $id])->orderBy('time_created','ASC')->many();
        $ret = [];
        foreach ($data as $i)
        {
            $ret[] = (new self())->set($i);
        }
        return $ret;
    }

    /**
     * @param string $type
     * @param int $id
     * @return int
     */
    public static function get_count_by_entity_id(string $type, int $id)
    {
        return App::get_ci()->s->from(self::CLASS_TABLE)->where(['entity_type' => $type, 'entity_id' => $id])->orderBy('time_created','ASC')->count();
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
     * @return int
     */
    public function get_entity_id(): int
    {
        return $this->entity_id;
    }

    /**
     * @param int $entity_id
     *
     * @return bool
     */
    public function set_entity_id(int $entity_id)
    {
        $this->entity_id = $entity_id;
        return $this->save('entity_id', $entity_id);
    }

    /**
     * @return string
     */
    public function get_entity_type(): string
    {
        return $this->entity_type;
    }

    /**
     * @param string $entity_type
     * @return bool
     * @throws Exception
     */
    public function set_entity_type(string $entity_type)
    {
        if (!in_array($entity_type, self::ENTITY_TYPES)) {
            throw new \Exception('Wrong entity type');
        }
        $this->entity_type = $entity_type;
        return $this->save('entity_type', $entity_type);
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

    function __construct($id = NULL)
    {
        parent::__construct();

        App::get_ci()->load->model('User_model');


        $this->set_id($id);
    }

    public static function create(array $data)
    {
        App::get_ci()->s->from(self::CLASS_TABLE)->insert($data)->execute();
        return new static(App::get_ci()->s->get_insert_id());
    }

    public function delete()
    {
        App::get_ci()->s->from(self::CLASS_TABLE)->where(['id' => $this->get_id()])->delete()->execute();
        return (App::get_ci()->s->get_affected_rows() > 0);
    }
}