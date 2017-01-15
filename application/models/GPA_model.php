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
	
	public function get_course_data()
	{
		$filename = './config/course.json';
		$file = fopen($filename, 'r');
		if (!$file)
		{
			return NULL;
		}
		$json_data = fread($file, filesize($filename));
		fclose($file);
		//$json_info = json_decode($json_data, true);
		return $json_data;
	}
	
	
	public function update_scoreboard($USER_ID)
	{
		$courses = json_decode($this->get_course_data(), true);
		$data = array(
			'core_grade'   => 0,
			'total_grade'  => 0,
			'core_credit'  => 0,
			'total_credit' => 0,
			'core_gpa'     => 0,
			'total_gpa'    => 0
		);
		$query = $this->db->select('*')->from('gpa_list')->where(array('USER_ID' => $USER_ID))
		                  ->order_by('course_id', 'ASC')->get();
		$result = $query->result();
		
		//print_r($result);
		
		foreach ($result as $key => $item)
		{
			$course_id = $item->course_id;
			
			if (isset($courses['course'][$course_id]))
			{
				$course = &$courses['course'][$course_id];
				
				$data['total_grade'] += min(40, $item->grade) * $course['credit'];
				$data['total_credit'] += $course['credit'];
				
				if ($course['category'] != 'SJTU' || (isset($course['core']) && $course['core']))
				{
					$data['core_grade'] += min(40, $item->grade) * $course['credit'];
					$data['core_credit'] += $course['credit'];
				}
				
			}
			
			/*$query = $this->db->get_where('course', array('courseid' => $item->courseid));
			if ($query->num_rows() > 0)
			{
				$row = $query->row(0);
				
				$result[$key]->credit = $row->credit;
				$result[$key]->core = $row->core;
				$result[$key]->letter = $letter_list[$item->grade];
				
				$data['total_grade'] += min(40, $item->grade) * $row->credit;
				$data['total_credit'] += $row->credit;
				
				if ($row->core == '1')
				{
					$data['core_grade'] += min(40, $item->grade) * $row->credit;
					$data['core_credit'] += $row->credit;
				}
			}*/
		}
		
		if ($data['core_credit'] > 0)
		{
			$data['core_gpa'] = $data['core_grade'] / 10 / $data['core_credit'];
		}
		
		if ($data['total_credit'] > 0)
		{
			$data['total_gpa'] = $data['total_grade'] / 10 / $data['total_credit'];
		}
		
		unset($data['core_grade']);
		unset($data['total_grade']);
		
		$query = $this->db->select('USER_ID')->from('gpa_scoreboard')->where(array('USER_ID' => $USER_ID))->get();
		
		
		if ($query->num_rows() > 0)
		{
			$this->db->update('gpa_scoreboard', $data, array('USER_ID' => $USER_ID));
		}
		else
		{
			$data['USER_ID'] = $USER_ID;
			$this->db->insert('gpa_scoreboard', $data);
		}
		
		//return array('result' => $result, 'data' => $data);
		
	}
	
	public function update_scoreboard_all()
	{
		$query = $this->db->select('USER_ID')->from('gpa_list')->distinct()->get();
		foreach ($query->result() as $item)
		{
			$this->update_scoreboard($item->USER_ID);
		}
	}
	
	public function get_scoreboard()
	{
		$query = $this->db->select(array('USER_ID', 'core_gpa', 'core_credit', 'total_gpa', 'total_credit'))
		                  ->from('gpa_scoreboard')->order_by('core_gpa', 'DESC')->get();
		$result = $query->result();
		foreach ($result as $key => &$value)
		{
			$value->No = $key + 1;
			$query2 = $this->db->select('USER_NAME')->from('jbxx')->where(array('USER_ID' => $value->USER_ID))->get();
			if ($query->num_rows() > 0)
			{
				$value->name = $query2->row()->USER_NAME;
			}
			else
			{
				$value->name = $value->USER_ID;
			}
			$value->core_gpa = sprintf("%.3f", $value->core_gpa);
			$value->total_gpa = sprintf("%.3f", $value->total_gpa);
		}
		return $query->result();
	}
}

