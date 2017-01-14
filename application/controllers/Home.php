<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->add_nav('org')->add_nav('stu-un');
    }
    
    protected function redirect()
    {
        $this->__redirect();
    }
    
    public function index()
    {
        $this->data['page_name'] = 'UM-SJTU JI LIFE';
        $this->add_nav('tech')->form_navbar();
        $obj = $this->Site_model->get_object('jbxx', 'User_obj', array('USER_ID' => '515370910207'));
        $this->load->view('home', $this->data);
    }
    
    
}
