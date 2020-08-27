<?php


class Transaction_model extends CI_Emerald_Model
{
    const CLASS_TABLE = 'transaction';

    const TRANSACTION_TYPES = [
        'in',
        'out'
    ];

    /** @var int */
    protected $user_id;
    /** @var int */
    protected $state;
    /** @var string */
    protected $type;
    /** @var string */
    protected $amount;
    /** @var string */
    protected $extra;
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
     * @return string
     */
    public function get_type(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function get_amount(): string
    {
        return $this->amount;
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

        App::get_ci()->load->model('User_model');


        $this->set_id($id);
    }

    public static function create(array $data)
    {
        if (!in_array($data['type'], self::TRANSACTION_TYPES)) {
            throw new \Exception('Wrong transaction type');
        }
        App::get_ci()->s->from(self::CLASS_TABLE)->insert($data)->execute();
        return new static(App::get_ci()->s->get_insert_id());
    }

    public static function add_wrong_input_transaction($user_id, $amount, $extra = [])
    {
        self::create([
            'user_id' => $user_id,
            'amount' => $amount,
            'state' => 0,
            'type' => 'in',
            'extra' => json_encode($extra)
        ]);
    }

    public static function add_success_input_transaction($user_id, $amount, $extra = [])
    {
        self::create([
            'user_id' => $user_id,
            'amount' => $amount,
            'state' => 1,
            'type' => 'in',
            'extra' => json_encode($extra)
        ]);
    }

    public static function add_wrong_output_transaction($user_id, $amount, $extra = [])
    {
        self::create([
            'user_id' => $user_id,
            'amount' => $amount,
            'state' => 0,
            'type' => 'out',
            'extra' => json_encode($extra)
        ]);
    }

    public static function add_success_output_transaction($user_id, $amount, $extra = [])
    {
        self::create([
            'user_id' => $user_id,
            'amount' => $amount,
            'state' => 1,
            'type' => 'out',
            'extra' => json_encode($extra)
        ]);
    }

    public static function get_user_transactions($user_id, $state = null)
    {
        $query = ['user_id' => $user_id];
        if (!is_null($state)) {
            $query['state'] = intval($state);
        }
        $data = App::get_ci()->s->from(self::CLASS_TABLE)->where($query)->orderBy('time_created', 'ASC')->many();
        $ret = [];
        foreach ($data as $i) {
            $ret[] = (new self())->set($i);
        }
        return $ret;
    }

    /**
     * @param Transaction_model|Transaction_model[] $data
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
     * @param Transaction_model[] $data
     * @return stdClass[]
     */
    private static function _preparation_default($data)
    {
        $ret = [];

        foreach ($data as $d){
            $o = new stdClass();

            $o->id = $d->get_id();

            $o->amount = $d->get_amount();
            $o->extra = $d->get_extra();
            $o->state = $d->get_state();
            $o->type = $d->get_type();

            $o->time_created = $d->get_time_created();

            $ret[] = $o;
        }

        return $ret;
    }
}