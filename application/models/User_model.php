<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class User_model
 *
 * @category   ji-life
 * @package    ji-life
 * @author     tc-imba
 * @copyleft   2016-2017 umji-sjtu
 */
class User_model extends CI_Model
{
    
    /**
     * User_model constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->load->library('User_obj');
    }
    
    /**
     * @param $USER_ID int
     * @return User_obj
     */
    public function get_user($USER_ID)
    {
        $query = $this->db->get_where('user', array('USER_ID' => $USER_ID));
        if ($query->num_rows() == 0) return new User_obj();
        return $query->row(0, 'User_obj');
    }
    
    
    /**
     * @param $user_info object
     * @return User_obj
     */
    public function update_user($user_info)
    {
        $data = array(
            'USER_ID'      => $user_info->code,
            'user_name'    => $user_info->name,
            'user_type'    => $user_info->userType,
            'account'      => $user_info->account,
            'birth'        => $user_info->birthday->birthYear . '.' .
                              $user_info->birthday->birthMonth . '.' .
                              $user_info->birthday->birthDay,
            'gender'       => $user_info->gender,
            'email'        => $user_info->email,
            'mobile'       => $user_info->mobile,
            'institute_id' => $user_info->organize->id,
            'card'         => $user_info->cardNo,
            'card_type'    => $user_info->cardType,
        );
        $user = $this->get_user($data['USER_ID']);
        //print_r($data);
        if ($user->is_error())
        {
            $this->db->insert('user', $data);
        }
        else
        {
            $this->db->update('user', $data, array('USER_ID' => $data['USER_ID']));
        }
        return new User_obj($data);
    }
    
}

