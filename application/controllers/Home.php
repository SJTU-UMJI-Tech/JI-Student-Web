<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Front_Controller
{
    protected function redirect()
    {
        $this->__redirect();
    }
    
    public function index()
    {
        $data['page_name'] = 'UM-SJTU JI LIFE';
        
        $obj = $this->Site_model->get_object('jbxx', 'User_obj', array('USER_ID' => '515370910207'));
        $this->load->view('home', $data);
        $_SESSION['user_id'] = '515370910207';
        $_SESSION['user_name'] = 'Liu Yihao';
        $_SESSION['user_type'] = 'Manager';
    }
    
    
}
