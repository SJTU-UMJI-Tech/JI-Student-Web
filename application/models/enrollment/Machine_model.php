<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Editor_model
 *
 * @category   ji-life
 * @package    ji-life
 * @author     tc-imba
 * @copyright  2016-2017 umji-sjtu
 */
class Machine_model extends CI_Model
{
    const TABLE_GROUP = 'enrollment_machine_group';
    const TABLE_USER  = 'enrollment_machine_user';
    
    public $season;
    public $season_name;
    public $member_max;
    public $deadline;
    
    /**
     * Machine_model constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->Site_model->load_site_config('machine');
        $this->season = $this->Site_model->site_config['enrollment_machine_season'];
        $this->season_name = $this->Site_model->site_config['enrollment_machine_season_name'];
        $this->member_max = $this->Site_model->site_config['enrollment_machine_member_max'];
        $this->deadline = $this->Site_model->site_config['enrollment_machine_deadline'];
    }
    
    function is_time_valid()
    {
        $deadline = strtotime($this->deadline);
        $now = time();
        return $now <= $deadline;
    }
    
    function get_group_by_id($id)
    {
        $group = $this->Site_model->get_object($this::TABLE_GROUP, 'My_obj', array(
            'id'     => $id,
            'state'  => 0,
            'season' => $this->season
        ));
        return $group;
    }
    
    function get_user_group_by_id($USER_ID)
    {
        $query = $this->db->get_where($this::TABLE_USER, array('USER_ID' => $USER_ID, 'season' => $this->season));
        if ($query->num_rows() > 0) return $query->row(0)->group_id;
        return -1;
    }
    
    function get_member_info($members, $group_id)
    {
        $query = $this->db->select('user.USER_ID, user.user_name, (group_id=' . $group_id . ') AS verified')
                          ->from($this::TABLE_USER)->where_in('user.USER_ID', $members)
                          ->join('user', $this::TABLE_USER . '.USER_ID=user.USER_ID', 'right')->get();
        return $query->result();
    }
    
    function reset_member_group($member_id, $group_id)
    {
        if ($group_id <= 0) return;
        if ($this->get_user_group_by_id($member_id) == $group_id)
        {
            $this->db->update($this::TABLE_USER, array('group_id' => 0),
                              array('USER_ID' => $member_id, 'season' => $this->season));
        }
    }
    
    function verify($USER_ID, $new_group_id)
    {
        if (!$this->is_time_valid()) return false;
        $group = $this->get_group_by_id($new_group_id);
        if ($group->id <= 0) return false;
        $member_list = explode(',', $group->member);
        if (!in_array($USER_ID, $member_list)) return false;
        $group_id = $this->get_user_group_by_id($USER_ID);
        if ($group_id < 0)
        {
            $this->db->insert($this::TABLE_USER, array(
                'USER_ID'  => $USER_ID,
                'group_id' => $new_group_id,
                'season'   => $this->season
            ));
            return true;
        }
        else if ($group_id == 0)
        {
            $this->db->update($this::TABLE_USER, array('group_id' => $new_group_id),
                              array('USER_ID' => $USER_ID, 'season' => $this->season));
            return true;
        }
        return false;
    }
    
    function submit($USER_ID, $class_id, $member_list)
    {
        if (!$this->is_time_valid()) return '报名已结束';
        $group_id = $this->get_user_group_by_id($USER_ID);
        $member_arr = $member_list ? explode(',', $member_list) : array();
        if (count($member_arr) > $this->member_max) return '队员人数超过上限';
        foreach ($member_arr as $member_id)
        {
            if (!preg_match('/^(\d{12}|\d{10})$/', $member_id)) return '队员学号格式错误';
            if ($member_id == $USER_ID) return '队员学号与队长相同';
        }
        $data = array(
            'class_id' => $class_id,
            'member'   => $member_list,
            'state'    => 0,
        );
        if ($group_id > 0)
        {
            // 有组
            $group = $this->get_group_by_id($group_id);
            if ($group_id != $group->id) return '小组编号发生未知错误，请联系我们';
            if ($USER_ID != $group->leader_id) return '你不是组长，不能提交';
            $old_member_list = explode(',', $group->member);
            foreach ($old_member_list as $member_id)
            {
                if (!in_array($member_id, $member_arr))
                {
                    $this->reset_member_group($member_id, $group_id);
                }
            }
            $this->db->update($this::TABLE_GROUP, $data, array('id' => $group_id));
        }
        else
        {
            // 没有组
            $data['leader_id'] = $USER_ID;
            $data['season'] = $this->season;
            $this->db->insert($this::TABLE_GROUP, $data);
            $new_group_id = $this->db->insert_id();
            if (($group_id == 0))
            {
                // 已注册
                $this->db->update($this::TABLE_USER,
                                  array('group_id' => $new_group_id),
                                  array('USER_ID' => $USER_ID, 'season' => $this->season));
            }
            else
            {
                // 未注册
                $this->db->insert($this::TABLE_USER, array(
                    'USER_ID'  => $USER_ID,
                    'group_id' => $new_group_id,
                    'season'   => $this->season
                ));
            }
        }
        return 'ok';
    }
    
    function cancel($USER_ID)
    {
        if (!$this->is_time_valid()) return false;
        $group_id = $this->get_user_group_by_id($USER_ID);
        if ($group_id <= 0) return false;
        $group = $this->get_group_by_id($group_id);
        if ($group->leader_id == $USER_ID)
        {
            // 组长取消
            $member_list = explode(',', $group->member);
            foreach ($member_list as $member_id)
            {
                $this->reset_member_group($member_id, $group_id);
            }
            $this->reset_member_group($USER_ID, $group_id);
            $this->db->update($this::TABLE_GROUP, array('state' => 1), array('id' => $group_id));
        }
        else
        {
            // 组员取消
            $this->reset_member_group($USER_ID, $group_id);
        }
        return true;
    }
}

