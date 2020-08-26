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
    /** @var double */
    protected $amount;
    /** @var string */
    protected $extra;
    /** @var string */
    protected $time_created;

    /**
     * @return int
     */
    public function get_user_id(): int
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
     * @return int
     */
    public function get_type(): int
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function get_amount(): float
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
        $data['amount'] = floatval($data['amount']);
        if ($data['amount'] <= 0) {
            throw new \Exception('Wrong amount value');
        }
        App::get_ci()->s->from(self::CLASS_TABLE)->insert($data)->execute();
        return new static(App::get_ci()->s->get_insert_id());
    }
}