<?php defined('BASEPATH') OR exit('No direct script access allowed');

class GPA extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('GPA_model');
        $this->add_nav('GPA');
    }
    
    protected function redirect()
    {
        $this->redirect_login();
        $user = $this->GPA_model->get_user($_SESSION['user_id']);
        if (!$user || $user->state <= 0) $this->__redirect('GPA/terms');
    }
    
    public function terms()
    {
        $this->redirect_login();
        if ($this->input->get('confirm') == 1)
        {
            $this->GPA_model->set_user_state($_SESSION['user_id'], 1);
            $this->__redirect('GPA');
        }
        $this->data['terms_body'] = $this->Site_model->read_config('gpa/terms.json');
        $this->form_navbar();
        $this->load->view('gpa/terms', $this->data);
    }
    
    public function index()
    {
        $this->redirect();
        $scoreboard = $this->GPA_model->get_scoreboard();
        $this->data['scoreboard'] = json_encode($scoreboard);
        $this->add_nav('board')->form_navbar();
        $this->load->view('gpa/home', $this->data);
    }
    
    public function graph()
    {
        $this->redirect();
        $course_id = $this->input->get('id');
        $this->data['score_list'] = json_encode($this->GPA_model->get_course_score($course_id));
        $this->data['course_id'] = $course_id;
        $this->add_nav('graph')->form_navbar();
        $this->load->view('gpa/graph', $this->data);
    }
    
    
    public function update_all()
    {
        $this->redirect();
        $this->GPA_model->update_scoreboard_all();
    }
}
