<?php defined('BASEPATH') OR exit('No direct script access allowed');

class GPA extends Front_Controller
{
    protected function redirect()
    {
        $this->__redirect('gpa');
    }
    
    public function index()
    {
        $scoreboard = array(
            array('No' => 1, 'name' => 'JCC'),
            array('No' => 2, 'name' => 'Luke Xuan'),
            array('No' => 3, 'name' => 'Jason QSY'),
            array('No' => 4, 'name' => 'WGZ'),
        );
        $this->data['scoreboard'] = json_encode($scoreboard);
        $this->form_navbar();
        $this->load->view('gpa/home', $this->data);
        
    }
}
