<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class My_obj
 *
 * The basic class of objects
 *
 * @category   ji
 * @package    ji
 * @author     tc-imba
 * @copyright  2016 umji-sjtu
 */
class My_obj
{
	/** @var bool */
	protected $error_flag = false;
	/** @var CI_Model */
	protected $CI;
	
	/**
	 * My_obj constructor.
	 * @param array        $data
	 * @param string|array $main
	 */
	public function __construct($data = array(), $main = array())
	{
		if (isset($data))
		{
			foreach ($data as $key => $value)
			{
				$this->$key = $value;
			}
		}
		if (!is_array($main))
		{
			$main = array($main => $main);
		}
		else
		{
			$main = array_flip($main);
		}
		foreach ($main as $key => $value)
		{
			if (!isset($this->$key))
			{
				$this->$key = 0;
				$this->error_flag = true;
			}
		}
		if (!$this->is_error())
		{
			$this->CI =& get_instance();
		}
	}
	
	
	/**
	 * Return whether the object is error
	 * @return bool
	 */
	public function is_error()
	{
		return $this->error_flag;
	}
	
	/**
	 * Magic method __get()
	 * @param $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return isset($this->$key) && !$this->is_error() ? $this->$key : NULL;
	}
	
	public function add_array(&$array)
	{
		if (!$this->is_error())
		{
			$array[] = &$this;
		}
	}
	
}