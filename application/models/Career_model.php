<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Career_model
 *
 * @category   ji-life
 * @package    ji-life
 * @author     tc-imba
 * @copyright  2016-2017 umji-sjtu
 */
class Career_model extends CI_Model
{
    const TABLE   = 'career';
    //const LIBRARY = 'Scholarships_obj';
    
    /**
     * Career_model constructor.
     */
    function __construct()
    {
        parent::__construct();
        //$this->load->library($this::LIBRARY);
    }

    function get_category()
    {
        $query = $this->db->select('*')->from('career_wechat_category')->get();
        $result = $query->result_array();
        return $result;
    }

    function get_data()
    {
        $query = $this->db->select('*')->from('career_wechat_data')->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_by_id($id)
    {
        return $this->Site_model->get_object($this::TABLE, $this::LIBRARY, array('id' => $id));
    }

    public function search($keywords, $where, $limit, $offset, $order)
    {
        $fields = array('title', 'abstract');
        $orders = array('start_date' => $order != 'Oldest' ? 'DESC' : 'ASC');
        $result = $this->Site_model->search_object($this::TABLE, $this::LIBRARY, $fields, $keywords,
            $where, $orders, $limit, $offset);
        //print_r($result);
        return $result;
    }
    
}

