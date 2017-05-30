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

    public function search($keywords, $where, $limit, $offset)
    {
        $fields = array('title', 'abstract');
        $orders = array('start_date' => $order != 'Oldest' ? 'DESC' : 'ASC');
        $result = $this->Site_model->search_object($this::TABLE, $this::LIBRARY, $fields, $keywords,
            $where, $orders, $limit, $offset);
        //print_r($result);
        return $result;
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
}

