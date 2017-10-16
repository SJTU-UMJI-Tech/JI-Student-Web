<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ordering extends Front_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->add_nav('ORDER');
        $this->name = 'ordering';
        $this->load->model('Ordering_model');
    }
    
    protected function redirect()
    {
        $this->__redirect();
    }
    
    public function index()
    {
        $this->redirect_acl('read', 'site');
        
        if ($this->input->post())
        {
            $building = $this->input->post('building');
            $floor = $this->input->post('floor');
            $room = $this->input->post('room');
            $profile = $this->Ordering_model->set_address($_SESSION['user_id'], $building, $floor, $room);
        }
        else
        {
            $profile = $this->Ordering_model->get_address($_SESSION['user_id']);
        }
        
        $this->form_navbar();
        $data = array(
            'profile' => $profile ? $profile : array(),
            'url'     => base_url('ordering')
        );
        $this->__view('ji/ordering/index', $data);
    }
    
    public function water()
    {
        $this->redirect_acl('read', 'site');
        
        $this->add_nav('water')->form_navbar();
        $data = array();
        $this->__view('ji/ordering/water', $data);
    }
    
}
