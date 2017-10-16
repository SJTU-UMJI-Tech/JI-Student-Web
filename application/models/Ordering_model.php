<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Ordering_model
 *
 * @category   ji-life
 * @package    ji-life
 * @author     tc-imba
 * @copyright  2016-2017 umji-sjtu
 */
class Ordering_model extends CI_Model
{
    const ADDRESS_TABLE = 'ordering_address';
    
    /**
     * Career_model constructor.
     */
    function __construct()
    {
        parent::__construct();
    }
    
    
    function get_address($USER_ID)
    {
        $query = $this->db->select('*')
                          ->from($this::ADDRESS_TABLE)
                          ->where(array('USER_ID' => $USER_ID))->get();
        $result = $query->result_array();
        if (count($result) > 0) return ($query->result_array())[0];
        return null;
    }
    
    function set_address($USER_ID, $building, $floor, $room)
    {
        $address = $this->get_address($USER_ID);
        $data = array(
            'building' => $building,
            'floor'    => $floor,
            'room'     => $room
        );
        if ($address)
        {
            $this->db->update($this::ADDRESS_TABLE, $data, array('USER_ID' => $USER_ID));
        }
        else
        {
            $data['USER_ID'] = $USER_ID;
            $this->db->insert($this::ADDRESS_TABLE, $data);
        }
        return $data;
    }
}

