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
    
    /**
     * @return bool
     */
    function is_time_valid()
    {
        $deadline = strtotime($this->deadline);
        $now = time();
        return $now <= $deadline;
    }
    
    /**
     * @param int $id
     * @return My_obj
     */
    function get_group_by_id($id)
    {
        $group = $this->Site_model->get_object($this::TABLE_GROUP, 'My_obj', array(
            'id'     => $id,
            'state'  => 0,
            'season' => $this->season
        ));
        return $group;
    }
    
    /**
     * @param int $USER_ID
     * @return int
     */
    function get_user_group_by_id($USER_ID)
    {
        $query = $this->db->get_where($this::TABLE_USER, array('USER_ID' => $USER_ID, 'season' => $this->season));
        if ($query->num_rows() > 0) return $query->row(0)->group_id;
        return -1;
    }
    
    /**
     * @param array $members
     * @param int   $group_id
     * @return array
     */
    function get_member_info($members, $group_id)
    {
        $query = $this->db->select('user.USER_ID, user.user_name, (group_id=' . $group_id . ') AS verified')
                          ->from($this::TABLE_USER)->where_in('user.USER_ID', $members)
                          ->join('user', $this::TABLE_USER . '.USER_ID=user.USER_ID', 'right')->get();
        return $query->result();
    }
    
    /**
     * @param int $member_id
     * @param int $group_id
     */
    function reset_member_group($member_id, $group_id)
    {
        if ($group_id <= 0) return;
        if ($this->get_user_group_by_id($member_id) == $group_id)
        {
            $this->db->update($this::TABLE_USER, array('group_id' => 0),
                              array('USER_ID' => $member_id, 'season' => $this->season));
        }
    }
    
    /**
     * @param int $USER_ID
     * @param int $new_group_id
     * @return bool
     */
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
    
    /**
     * @param int    $USER_ID
     * @param int    $class_id
     * @param string $member_list
     * @return string
     */
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
    
    /**
     * @param int $USER_ID
     * @return bool
     */
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
    
    function transfer($leader_id, $USER_ID)
    {
        $group_id = $this->get_user_group_by_id($leader_id);
        if ($group_id <= 0) return '你没有队伍';
        $group = $this->get_group_by_id($group_id);
        if ($group->is_error() || $group->leader_id != $leader_id) return '你不是队长';
        $member_arr = $group->member ? explode(',', $group->member) : array();
        if (!in_array($USER_ID, $member_arr)) return '队伍中没有目标队员';
        $member_group_id = $this->get_user_group_by_id($USER_ID);
        if ($member_group_id != $group_id) return '目标队员未认证';
        foreach ($member_arr as $index => $item)
            if ($item == $USER_ID) array_splice($member_arr, $index, 1);
        array_unshift($member_arr, $leader_id);
        $this->db->update($this::TABLE_GROUP,
                          array(
                              'leader_id' => $USER_ID,
                              'member'    => implode(',', $member_arr)
                          ),
                          array('id' => $group_id));
        return 'ok';
    }
    
    /**
     * @param string $season
     * @return string
     */
    function export_result($season)
    {
        $this->load->model('User_model');
        
        $workbook = new PHPExcel();
        $worksheet = $workbook->setActiveSheetIndex(0);
        $worksheet->setCellValue('A1', '编号')
                  ->setCellValue('B1', '班级')
                  ->setCellValue('C1', '姓名')
                  ->setCellValue('D1', '学号')
                  ->setCellValue('E1', '手机');
        
        // 设置首行居中
        foreach (array('A1', 'B1', 'C1', 'D1', 'E1') as $cell)
            $worksheet->getStyle($cell)->getAlignment()
                      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        // 设置列宽度
        $worksheet->getColumnDimension('A')->setWidth(5);
        $worksheet->getColumnDimension('B')->setWidth(15);
        $worksheet->getColumnDimension('C')->setWidth(15);
        $worksheet->getColumnDimension('D')->setWidth(20);
        $worksheet->getColumnDimension('E')->setWidth(20);
        
        $current_row = 2;
        $query = $this->db->get_where($this::TABLE_GROUP, array('season' => $season, 'state' => 0));
        foreach ($query->result() as $index => $group)
        {
            // 设置队长
            $leader = $this->User_model->get_user($group->leader_id);
            $data = array(
                'A' => $index + 1,
                'B' => $group->class_id,
                'C' => $leader->user_name . ' (队长)',
                'D' => $leader->USER_ID,
                'E' => $leader->mobile
            );
            foreach ($data as $column => $value)
            {
                $style = $worksheet->getCell($column . $current_row)
                                   ->setValue($value)->getStyle();
                $style->getAlignment()
                      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                      ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $style->getNumberFormat()->setFormatCode('0');
            }
            
            $group_first_row = $current_row++;
            
            // 设置队员
            if ($group->member)
            {
                $member_arr = explode(',', $group->member);
                $member_info = $this->get_member_info($member_arr, $group->id);
                foreach ($member_info as $user)
                {
                    if ($user->verified)
                    {
                        $style = $worksheet->getCell('C' . $current_row)
                                           ->setValue($user->user_name)->getStyle();
                        $style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $style->getNumberFormat()->setFormatCode('0');
                        $style = $worksheet->getCell('D' . $current_row)
                                           ->setValue($user->USER_ID)->getStyle();
                        $style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $style->getNumberFormat()->setFormatCode('0');
                        $current_row++;
                    }
                }
            }
            
            $worksheet->mergeCells('A' . $group_first_row . ':A' . ($current_row - 1));
            $worksheet->mergeCells('B' . $group_first_row . ':B' . ($current_row - 1));
        }
        
        $filename = 'files/enrollment/machine/' . $season . '/result.xlsx';
        
        $writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
        $writer->save($filename);
        
        return $filename;
    }
}

