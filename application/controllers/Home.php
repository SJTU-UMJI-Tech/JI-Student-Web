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
        $this->load->model('Navbar_model');
        $nav = $this->Navbar_model->get_navbar_data();
        $str = $this->Navbar_model->generate_navbar($nav);
        //print_r($nav);
        //echo $str;
        $data['nav'] = $str;
        $this->load->view('home', $data);
    }
    
    
}
