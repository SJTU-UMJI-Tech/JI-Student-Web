<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends Front_Controller
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
    
    }
    
    public function page_missing()
    {
        $this->load->view('errors/404.html');
    }
    
    
}
