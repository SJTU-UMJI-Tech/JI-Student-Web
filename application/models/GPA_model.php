<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class GPA_model
 *
 * @category   ji-life
 * @package    ji-life
 * @author     tc-imba
 * @copyleft   2016-2017 umji-sjtu
 */
class GPA_model extends CI_Model
{
    /**
     * GPA_model constructor.
     */
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Update the scoreboard and return whether this is a new user
     * @param int        $USER_ID
     * @param null|array $courses
     * @return bool
     */
    public function update_scoreboard($USER_ID, &$courses = NULL)
    {
        /** If $courses is NULL, read it from config */
        if (!$courses)
        {
            $this->load->model('Site_model');
            $courses = $this->Site_model->read_config('course.json');
            $courses = json_decode($courses, true);
        }
        
        /** Used to process gpa data */
        $data = array(
            'core_grade'   => 0,
            'total_grade'  => 0,
            'core_credit'  => 0,
            'total_credit' => 0,
            'core_gpa'     => 0,
            'total_gpa'    => 0
        );
        
        /** Get score from the database and calculate the gpa and credits */
        $query = $this->db->select(array('course_id', 'grade'))->from('gpa_list')->where(array('USER_ID' => $USER_ID))
                          ->order_by('course_id', 'ASC')->get();
        $result = $query->result();
        
        foreach ($result as $key => $item)
        {
            $course_id = $item->course_id;
            if (isset($courses['course'][$course_id]))
            {
                if ($item->grade < 0) continue;
                $course = &$courses['course'][$course_id];
                if ($item->grade == 0) continue;
                $data['total_grade'] += min(40, $item->grade) * $course['credit'];
                $data['total_credit'] += $course['credit'];
                
                if ($course['category'] != 'SJTU' || (isset($course['core']) && $course['core']))
                {
                    $data['core_grade'] += min(40, $item->grade) * $course['credit'];
                    $data['core_credit'] += $course['credit'];
                }
            }
            else echo $course_id . '<br>';
        }
        if ($data['core_credit'] > 0)
        {
            $data['core_gpa'] = $data['core_grade'] / 10 / $data['core_credit'];
        }
        if ($data['total_credit'] > 0)
        {
            $data['total_gpa'] = $data['total_grade'] / 10 / $data['total_credit'];
        }
        
        /** Unset extra attributes before update the database */
        unset($data['core_grade']);
        unset($data['total_grade']);
        
        /** Insert or Update the database */
        $query = $this->db->select('USER_ID')->from('gpa_scoreboard')->where(array('USER_ID' => $USER_ID))->get();
        if ($query->num_rows() > 0)
        {
            $this->db->update('gpa_scoreboard', $data, array('USER_ID' => $USER_ID));
            return false;
        }
        else
        {
            $data['USER_ID'] = $USER_ID;
            $this->db->insert('gpa_scoreboard', $data);
            return true;
        }
    }
    
    /**
     * Update all data in the scoreboard
     * Use it when course.json is changed
     * @TODO Can be only called by manager of this module
     */
    public function update_scoreboard_all()
    {
        $this->load->model('Site_model');
        $courses = $this->Site_model->read_config('course.json');
        if (!$courses)
        {
            echo 'Reading course.json failed';
            exit();
        }
        $courses = json_decode($courses, true);
        $query = $this->db->select('USER_ID')->from('gpa_list')->distinct()->get();
        foreach ($query->result() as $item)
        {
            $this->update_scoreboard($item->USER_ID, $courses);
            echo $item->USER_ID . ' success!<br>';
        }
    }
    
    /**
     * Get the scoreboard on the home page
     * @return array
     */
    public function get_scoreboard()
    {
        $query =
            $this->db->select(array(
                                  'core_gpa', 'core_credit', 'total_gpa',
                                  'total_credit', 'jbxx.USER_ID', 'jbxx.USER_NAME'
                              ))
                     ->from('gpa_scoreboard')->order_by('core_gpa', 'DESC')
                     ->where('total_credit>=16')
                     ->join('jbxx', 'gpa_scoreboard.USER_ID=jbxx.USER_ID')
                     ->get();
        $result = $query->result();
        foreach ($result as $key => &$value)
        {
            $value->No = $key + 1;
            if (!$value->USER_NAME)
            {
                $value->USER_NAME = '';
            }
            $value->core_gpa = sprintf("%.3f", $value->core_gpa);
            $value->total_gpa = sprintf("%.3f", $value->total_gpa);
        }
        return $query->result();
    }
    
    /**
     * Get the scores of all users of the course
     * @param string $course_id
     * @return array
     */
    public function get_course_score($course_id)
    {
        $query = $this->db->select(array('grade', 'count(grade)'))->from('gpa_list')->order_by('grade', 'DESC')
                          ->where(array('course_id' => $course_id))->group_by('grade')->get();
        return $query->result();
    }
    
    /**
     * Get the gpa_scoreboard user
     * @param int $USER_ID
     * @return mixed|null
     */
    public function get_user($USER_ID)
    {
        $query = $this->db->get_where('gpa_scoreboard', array('USER_ID' => $USER_ID));
        if ($query->num_rows() > 0) return $query->row();
        return NULL;
    }
    
    /**
     * Set the state of this module
     * @TODO move this part to the privilege Model
     * @param int $USER_ID
     * @param int $state
     */
    public function set_user_state($USER_ID, $state)
    {
        $this->db->update('gpa_scoreboard', array('state' => $state), array('USER_ID' => $USER_ID));
    }
    
    /**
     * Get the score of all courses of a user
     * @param int $USER_ID
     * @return array
     */
    public function get_user_score($USER_ID)
    {
        $query = $this->db->select(array('id', 'course_id', 'grade'))->from('gpa_list')
                          ->order_by('course_id', 'ASC')
                          ->where(array('USER_ID' => $USER_ID))->get();
        return $query->result();
    }
}

