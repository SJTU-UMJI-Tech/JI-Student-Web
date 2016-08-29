<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Privilege_model
 *
 * @category   ji-life
 * @package    ji-life
 * @author     tc-imba
 * @copyright  2016 umji-sjtu
 */
class Privilege_model extends CI_Model
{
	const TABLE_GROUP = 'usergroup';
	const TABLE_USER  = 'user_detail';
	//const LIBRARY = 'Scholarships_obj';
	
	const PRIVILEGE_READ        = 0x01;
	const PRIVILEGE_WRITE       = 0x02;
	const PRIVILEGE_ADMIN_READ  = 0x04;
	const PRIVILEGE_ADMIN_WRITE = 0x08;
	const PRIVILEGE_MANAGE      = 0x10;
	
	/**
	 * Privilege_model constructor.
	 */
	function __construct()
	{
		parent::__construct();
		
	}
	
	/**
	 * @info 1:visitor
	 * @info 2:default
	 * @param int          $user
	 * @param string|array $type
	 * @return array
	 */
	public function get_privilege($user, $type = '*')
	{
		if (!$user)
		{
			$usergroup = '1';
		}
		else
		{
			$query = $this->db->select('usergroup')->from($this::TABLE_USER)
			                  ->where(array('USER_ID' => $user))->get();
			if ($query->num_rows() > 0)
			{
				$result = $query->row_array(0);
				$usergroup = '1,2,' . $result['usergroup'];
			}
			else
			{
				$query = $this->db->select('USER_ID')->from('jbxx')
				                  ->where(array('USER_ID' => $user))->get();
				$usergroup = $query->num_rows() > 0 ? '1,2' : '1';
			}
		}
		$usergroup = explode(',', $usergroup);
		if (is_array($type))
		{
			$type = implode(',', $type);
		}
		$query = $this->db->select($type)->from($this::TABLE_GROUP)
		                  ->where_in('id', $usergroup)->get();
		$remove = array_flip(array('id', 'name', 'CREATE_TIMESTAMP', 'UPDATE_TIMESTAMP'));
		$result = array();
		foreach ($query->result_array() as $row)
		{
			foreach ($row as $key => $value)
			{
				if (!array_key_exists($key, $remove))
				{
					if ($value == -1 || !isset($result[$key]))
					{
						$result[$key] = $value;
					}
					else
					{
						$result[$key] |= $value;
					}
				}
			}
		}
		//print_r($result);
		return $result;
	}
	
	public function has_privilege($user, $privilege)
	{
		$type = array();
		foreach ($privilege as $key => $value)
		{
			$type[] = $key;
		}
		$result = $this->get_privilege($user, $type);
		foreach ($privilege as $key => $value)
		{
			if (!isset($result[$key]))
			{
				return false;
			}
			if (is_string($value))
			{
				$value = $this->str2privilege($value);
			}
			if ($result[$key] > 0 && ($result[$key] & $value) != $value)
			{
				return false;
			}
		}
		return true;
	}
	
	protected function str2privilege($str)
	{
		$privileges = explode(',', $str);
		$data = 0;
		foreach ($privileges as $privilege)
		{
			switch ($privilege)
			{
			case 'read':
				$data |= $this::PRIVILEGE_READ;
				break;
			case 'write':
				$data |= $this::PRIVILEGE_WRITE;
				break;
			case 'admin_read':
				$data |= $this::PRIVILEGE_ADMIN_READ;
				break;
			case 'admin_write':
				$data |= $this::PRIVILEGE_ADMIN_WRITE;
				break;
			default:
				$data |= 0x1000;
				break;
			}
		}
		return $data;
	}
	
	
}

