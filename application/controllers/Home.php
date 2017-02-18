<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    protected function redirect()
    {
        $this->__redirect();
    }
    
    public function index()
    {
        $this->data['page_name'] = 'UM-SJTU JI LIFE';
        $this->add_nav('HOME')->form_navbar();
        $obj = $this->Site_model->get_object('jbxx', 'User_obj', array('USER_ID' => '515370910207'));
        
        $this->load->model('ACL_model');
        $this->ACL_model->generate_config();
        var_dump($this->ACL_model->isAllowed('gpa', 'r'));
        
        $this->load->view('home', $this->data);
    }
    
    
}
