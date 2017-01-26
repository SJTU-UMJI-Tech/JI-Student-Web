<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Scholarship_model
 *
 * @category   ji-life
 * @package    ji-life
 * @author     tc-imba
 * @copyright  2016 umji-sjtu
 */
class Scholarships_model extends CI_Model
{
	const TABLE   = 'scholarships';
	const LIBRARY = 'Scholarships_obj';
	
	/**
	 * Scholarship_model constructor.
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->library($this::LIBRARY);
	}
	
	/**
	 * @param $id
	 * @return Scholarships_obj
	 */
	public function get_by_id($id)
	{
		return $this->Site_model->get_object($this::TABLE, $this::LIBRARY, array('id' => $id));
	}
	
	public function edit_by_id($id, $title, $abstract, $content)
	{
		$data = array(
			'title'    => $this->Site_model->html_purify($title),
			'abstract' => $this->Site_model->html_purify($abstract),
			'content'  => $this->Site_model->html_purify($content)
		);
		if ($id > 0)
		{
			$this->db->update($this::TABLE, $data, array('id' => $id));
			return $id;
		}
		else
		{
			$this->db->insert($this::TABLE, $data);
			return $this->db->insert_id();
		}
	}
	
	/**
	 * @param string|array $keywords
	 * @param array        $where
	 * @param int          $limit
	 * @param int          $offset
	 * @param string       $order
	 * @return string
	 */
	public function search($keywords, $where, $limit, $offset, $order)
	{
		$fields = array('title', 'abstract');
		$orders = array('CREATE_TIMESTAMP', $order != 'Oldest' ? 'DESC' : 'ASC');
		$result = $this->Site_model->search_object($this::TABLE, $this::LIBRARY, $fields, $keywords,
		                                           $where, $orders, $limit, $offset);
		//print_r($result);
		return $result;
	}
	
}

