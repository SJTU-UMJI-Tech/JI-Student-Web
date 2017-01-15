<?php defined('BASEPATH') OR exit('No direct script access allowed');

class GPA extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('GPA_model');
    }
    
    protected function redirect()
    {
        $this->__redirect('gpa');
    }
    
    public function index()
    {
        //print_r($this->GPA_model->get_course_data());
        
        $scoreboard = $this->GPA_model->get_scoreboard();
        
        /*$scoreboard = array(
            array('No' => 1, 'name' => 'JCC'),
            array('No' => 2, 'name' => 'Luke Xuan'),
            array('No' => 3, 'name' => 'Jason QSY'),
            array('No' => 4, 'name' => 'WGZ'),
        );*/
        $this->data['scoreboard'] = json_encode($scoreboard);
        $this->form_navbar();
        $this->load->view('gpa/home', $this->data);
        
    }
    
    public function graph()
    {
        $course_id = $this->input->get('id');
        $this->data['score_list'] = json_encode($this->GPA_model->get_course_score($course_id));
        $this->data['course_id'] = $course_id;
        $this->form_navbar();
        $this->load->view('gpa/graph', $this->data);
    }
    
    
    public function update_all()
    {
        $this->GPA_model->update_scoreboard_all();
    }
}
