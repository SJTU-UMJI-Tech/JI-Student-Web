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
    
    public function update_avatar($avatar_file)
    {
        $response = array(
            'state'   => 200,
            'message' => 'unknown error',
            'result'  => false
        );
        if (!$this->Site_model->is_login())
        {
            echo $response['message'] = 'Not login';
            return $response;
        }
        
        $USER_ID = $_SESSION['user_id'];
        
        $avatar_file = str_replace('data:image/png;base64,', '', $avatar_file);
        $avatar_img = base64_decode($avatar_file);
        $avatar_md5_new = md5(time());
        $avatar_temp_name = $avatar_md5_new . '.png';
        $avatar_size = file_put_contents('./uploads/temp/' . $avatar_temp_name, $avatar_img);
        
        $config['source_image'] = './uploads/temp/' . $avatar_temp_name;
        
        if ($avatar_size <= 0)
        {
            $response['message'] = 'Upload failed, please try again';
        }
        else if ($avatar_size >= 200000)
        {
            $response['message'] =
                'The size of file is too large! (It won\'t happen if you are using our cropper plugin)';
        }
        else
        {
            $file_prefix = './uploads/avatar/' . $USER_ID . '.' . $avatar_md5_new;
            
            $config['image_library'] = 'gd2';
            $config['new_image'] = $file_prefix . '.big.png';
            $config['width'] = 150;
            $config['height'] = 150;
            
            $this->load->library('image_lib', $config);
            
            $this->image_lib->resize();
            
            $config['new_image'] = $file_prefix . '.png';
            $config['width'] = 48;
            $config['height'] = 48;
            
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            
            $response['result'] = base_url('uploads/avatar/' . $USER_ID . '.' . $avatar_md5_new . '.big.png');
            
            $user = $this->get_user($USER_ID);
            $avatar_md5 = $user->avatar_md5;
            $this->db->update('user', array('avatar_md5' => $avatar_md5_new), array('USER_ID' => $USER_ID));
            $_SESSION['avatar_md5'] = $avatar_md5_new;
            
            $file_prefix = './uploads/avatar/' . $USER_ID . '.' . $avatar_md5;
            if (file_exists($file_prefix . '.png'))
                unlink($file_prefix . '.png');
            if (file_exists($file_prefix . '.big.png'))
                unlink($file_prefix . '.big.png');
            
        }
        
        unlink($config['source_image']);
        
        
        return json_encode($response);
    }
    
    
}

