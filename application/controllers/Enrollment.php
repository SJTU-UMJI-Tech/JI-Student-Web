<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Enrollment extends Front_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->add_nav('ENROLL');
    }
    
    protected function redirect()
    {
        $this->__redirect('enrollment');
    }
    
    public function index()
    {
        $this->load->view('common/page');
    }
    
    public function machine()
    {
        $this->add_nav('machine')->form_navbar();
        
        
        $this->load->view('common/page', $this->data);
    }
}
