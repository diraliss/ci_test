<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 27.01.2020
 * Time: 10:10
 */
class Comment_model extends CI_Emerald_Model
{
    const CLASS_TABLE = 'comment';


    /** @var int */
    protected $user_id;
    /** @var int */
    protected $assing_id;
    /** @var int|null */
    protected $parent_id;
    /** @var string */
    protected $text;

    /** @var string */
    protected $time_created;
    /** @var string */
    protected $time_updated;

    // generated
    /** @var Comment_model[] */
    protected $comments;
    /** @var Like_model[] */
    protected $likes;
    /** @var int */
    protected $likes_count;
    /** @var User_model */
    protected $user;
    /** @var Comment_model */
    protected $parent;


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
    public function get_assing_id(): int
    {
        return $this->assing_id;
    }

    /**
     * @param int $assing_id
     *
     * @return bool
     */
    public function set_assing_id(int $assing_id)
    {
        $this->assing_id = $assing_id;
        return $this->save('assing_id', $assing_id);
    }

    /**
     * @return int|null
     */
    public function get_parent_id(): ?int
    {
        return $this->parent_id;
    }

    /**
     * @param int|null $parent_id
     *
     * @return bool
     */
    public function set_parent_id(?int $parent_id)
    {
        $this->parent_id = $parent_id;
        return $this->save('parent_id', $parent_id);
    }


    /**
     * @return string
     */
    public function get_text(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    public function set_text(string $text)
    {
        $this->text = $text;
        return $this->save('text', $text);
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
    public function set_time_updated(int $time_updated)
    {
        $this->time_updated = $time_updated;
        return $this->save('time_updated', $time_updated);
    }

    // generated

    /**
     * @return Like_model[]
     */
    public function get_likes()
    {
        if (empty($this->likes)) {
            try {
                $this->likes = Like_model::get_all_by_entity_id('comment', $this->id);
            } catch (\Exception $e) {
                $this->likes = [];
            }
        }
        return $this->likes;
    }

    /**
     * @return int
     */
    public function get_likes_count()
    {
        if (empty($this->likes_count)) {
            $this->likes_count = Like_model::get_count_by_entity_id('comment', $this->id);
        }
        return $this->likes_count;
    }

    /**
     * @return self[]
     */
    public function get_comments()
    {
        if (empty($this->comments)) {
            try {
                $this->comments = self::get_all_by_parent_id($this->id);
            } catch (\Exception $e) {
                $this->comments = [];
            }
        }
        return $this->comments;
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
     * @return Comment_model
     */
    public function get_parent(): Comment_model
    {
        if (empty($this->parent) && !is_null($this->get_parent_id())) {
            try {
                $this->parent = new Comment_model($this->get_parent_id());
            } catch (Exception $exception) {
                $this->parent = new Comment_model();
            }
        }
        return $this->parent;
    }

    function __construct($id = NULL)
    {
        parent::__construct();

        App::get_ci()->load->model('User_model');


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

    /**
     * @param int $assign_id
     * @param bool $without_parent
     * @return self[]
     */
    public static function get_all_by_assign_id(int $assign_id, bool $without_parent = false)
    {
        $query = ['assign_id' => $assign_id];
        if ($without_parent) {
            $query['parent_id'] = null;
        }
        $data = App::get_ci()->s->from(self::CLASS_TABLE)->where($query)->orderBy('time_created', 'ASC')->many();
        $ret = [];
        foreach ($data as $i) {
            $ret[] = (new self())->set($i);
        }
        return $ret;
    }

    /**
     * @param int $parent_id
     * @return self[]
     * @throws Exception
     */
    public static function get_all_by_parent_id(int $parent_id)
    {
        $data = App::get_ci()->s->from(self::CLASS_TABLE)->where(['parent_id' => $parent_id])->orderBy('time_created', 'ASC')->many();
        $ret = [];
        foreach ($data as $i) {
            $ret[] = (new self())->set($i);
        }
        return $ret;
    }

    /**
     * @param int $id
     * @return boolean
     */
    public static function exist($id)
    {
        $count = App::get_ci()->s->from(self::CLASS_TABLE)->where('id', intval($id))->count();
        return ($count > 0);
    }

    /**
     * @param self|self[] $data
     * @param string $preparation
     * @return stdClass|stdClass[]
     * @throws Exception
     */
    public static function preparation($data, $preparation = 'default')
    {
        switch ($preparation) {
            case 'full_info':
                return self::_preparation_full_info($data);
            default:
                throw new Exception('undefined preparation type');
        }
    }


    /**
     * @param self[] $data
     * @return stdClass[]
     */
    private static function _preparation_full_info($data)
    {
        $ret = [];

        foreach ($data as $d) {
            $o = new stdClass();

            $o->id = $d->get_id();
            $o->text = $d->get_text();

            $o->user = User_model::preparation($d->get_user(), 'main_page');
            $o->parent_id = $d->get_parent_id();
            $o->childs = Comment_model::preparation($d->get_comments(), 'full_info');

            $o->likes = $d->get_likes_count();

            $o->time_created = $d->get_time_created();
            $o->time_updated = $d->get_time_updated();

            $ret[] = $o;
        }


        return $ret;
    }


}
