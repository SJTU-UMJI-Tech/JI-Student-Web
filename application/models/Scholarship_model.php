<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Scholarship_model
 *
 * @category   ji-life
 * @package    ji-life
 * @author     tc-imba
 * @copyright  2016 umji-sjtu
 */
class Scholarship_model extends CI_Model
{
	const TABLE   = 'scholarship';
	const LIBRARY = 'Scholarship_obj';
	
	/**
	 * Scholarship_model constructor.
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->library($this::LIBRARY);
	}
	
	/**
	 * @param string|array $keywords
	 * @param int          $limit
	 * @param int          $offset
	 * @param string       $order
	 * @return string
	 */
	public function search($keywords, $limit, $offset, $order)
	{
		$fields = array('title', 'abstract');
		$orders = array('CREATE_TIMESTAMP', $order != 'Oldest' ? 'DESC' : 'ASC');
		$result = $this->Site_model->search_object($this::TABLE, $this::LIBRARY, $fields, $keywords,
		                                           $orders, $limit, $offset);
		return $result;
	}
	
}

