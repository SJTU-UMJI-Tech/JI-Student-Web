<?php defined('BASEPATH') OR exit('No direct script access allowed');

class GPA extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('GPA_model');
        $this->add_nav('GPA');
        $this->name = 'gpa';
    }
    
    protected function redirect()
    {
        $this->__redirect('GPA/terms');
        //$this->redirect_login();
        //$user = $this->GPA_model->get_user($_SESSION['user_id']);
        //if (!$user || $user->state <= 0) $this->__redirect('GPA/terms');
    }
    
    public function terms()
    {
        $this->redirect_acl('read','site');
        
        if ($this->input->get('confirm') == 1)
        {
            $this->GPA_model->update_scoreboard($_SESSION['user_id']);
            $this->GPA_model->set_user_state($_SESSION['user_id'], 1);
            $this->__redirect('GPA/degree');
        }
        $terms_body = $this->Site_model->read_config('gpa/terms.json');
        $this->form_navbar();
        
        $data = array(
            'terms_body'  => json_decode($terms_body, true),
            'confirm_url' => base_url('GPA/terms?confirm=1')
        );
        $this->data['article'] = true;
        
        $this->__view('ji/gpa/terms', $data);
    }
    
    public function index()
    {
        $this->redirect_acl('read');
        
        $scoreboard = $this->GPA_model->get_scoreboard();
        $this->add_nav('board')->form_navbar();
        
        $data = array(
            'scoreboard' => &$scoreboard
        );
        
        $this->__view('ji/gpa/scoreboard', $data);
    }
    
    public function graph_score()
    {
        if (!$this->validate_acl('read'))
        {
            echo json_encode('');
            exit();
        }
        $course_id = $this->input->get('id');
        echo json_encode($this->GPA_model->get_course_score($course_id));
        exit();
    }
    
    public function graph()
    {
        $this->redirect_acl('read');
        $this->add_nav('graph');
        
        $score = $this->GPA_model->get_user_score($_SESSION['user_id']);
        $courses = $this->Site_model->read_config('course.json');
        
        $data = array(
            'score'   => &$score,
            'courses' => json_decode($courses, true)
        );
        
        $this->__view('ji/gpa/graph', $data);
    }
    
    public function degree()
    {
        $this->redirect_acl('read');
        $this->add_nav('degree');
        
        $score = $this->GPA_model->get_user_score($_SESSION['user_id']);
        $courses = $this->Site_model->read_config('course.json');
        
        $data = array(
            'score'   => &$score,
            'courses' => json_decode($courses, true)
        );
        
        $this->__view('ji/gpa/degree', $data);
    }
    
    public function update()
    {
        if (!$this->validate_acl('read'))
        {
            echo 'Access Permission Error';
            exit();
        }
        $user = $this->GPA_model->get_user($_SESSION['user_id']);
        if (!$user)
        {
            echo 'User not found';
            exit();
        }
        if ($user->state < 1)
        {
            echo 'Permission denied';
            exit();
        }
        $courses = json_decode($this->Site_model->read_config('course.json'), true);
        if (!$courses)
        {
            echo 'Server error';
            exit();
        }
        
        $data = $this->input->post('data');
        $current = $this->GPA_model->get_user_score($_SESSION['user_id']);
        $current_map = array();
        foreach ($current as $item)
        {
            $current_map[$item->course_id] = $item->id;
        }
        $delete_queue = array();
        $insert_queue = array();
        $update_queue = array();
        foreach ($data as $item)
        {
            if ($item['method'] == 'delete' && isset($current_map[$item['course_id']]) &&
                $current_map[$item['course_id']] >= 0
            )
            {
                $delete_queue[] = $item['course_id'];
                $current_map[$item['course_id']] = -1;
            }
            else if ($item['method'] == 'edit' && isset($courses['course'][$item['course_id']])) ;
            {
                if (isset($item['grade']) && isset($courses['grade'][$item['grade']]))
                {
                    $grade = $courses['grade'][$item['grade']];
                }
                else
                {
                    $grade = -1;
                }
                if (!isset($current_map[$item['course_id']]))
                {
                    // Judge whether there is a equivalent course with the new course
                    if (isset($courses['course'][$item['course_id']]['equivalent']))
                    {
                        foreach ($courses['course'][$item['course_id']]['equivalent'] as $course_id)
                        {
                            if (isset($current_map[$course_id])) continue;
                        }
                    }
                    $insert_queue[] = array(
                        'USER_ID'   => $_SESSION['user_id'],
                        'course_id' => $item['course_id'],
                        'grade'     => $grade
                    );
                    $current_map[$item['course_id']] = -1;
                }
                else if ($current_map[$item['course_id']] >= 0)
                {
                    $update_queue[] = array(
                        'id'        => $item->id,
                        'course_id' => $item['course_id'],
                        'grade'     => $grade
                    );
                    $current_map[$item['course_id']] = -1;
                }
            }
        }
        print_r($data);
        print_r($insert_queue);
        print_r($update_queue);
        print_r($delete_queue);
        if (sizeof($insert_queue) > 0) $this->db->insert_batch('gpa_list', $insert_queue);
        if (sizeof($update_queue) > 0) $this->db->update_batch('gpa_list', $update_queue);
        if (sizeof($delete_queue) > 0) $this->db->where_in('course_id', $delete_queue)->delete('gpa_list');
        $this->GPA_model->update_scoreboard($_SESSION['user_id'], $courses);
        echo 'success';
        exit();
    }
    
    
    public function update_all()
    {
        $this->redirect_acl('admin');
        $this->GPA_model->update_scoreboard_all();
    }
}
