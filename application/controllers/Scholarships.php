<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Scholarships extends Front_Controller
{
    public $editor_create;
    
    function __construct()
    {
        parent::__construct();
        $this->data['type'] = 'scholarships';
        $this->Site_model->load_site_config('scholarships');
        $this->load->model('Scholarships_model');
        
        $this->editor_create = array(
            array('name' => 'title', 'label' => 'Title', 'type' => 'text'),
            array(
                'name'    => 'category',
                'label'   => 'Category',
                'type'    => 'dropdown',
                'option'  => array(
                    'undergraduate' => 'Undergraduates',
                 //  add freshman and sophomore, junior and senior ....
                    'graduate'      => 'Graduates',
                 //  also add grades
                    'all'           => 'Both'
                ),
                'default' => 'all'
            ),
            array('name' => 'abstract', 'label' => 'Abstract', 'type' => 'textarea'),
            array('name' => 'amount', 'label' => 'Amount', 'type' => 'text'),
            array('name' => 'start_date', 'label' => 'Deadline', 'type' => 'date',),
            array('name' => 'end_date', 'label' => 'Deadline', 'type' => 'date',),
            array('name' => 'content', 'label' => 'Content', 'type' => 'markdown'),
            array('name' => 'attachment', 'label' => 'Attachment', 'type' => 'file')
        );
        $this->add_nav('SCHOLAR');
        $this->name = 'scholarships';
    }
    
    protected function redirect()
    {
        $this->__redirect('scholarships');
    }
    
    public function index()
    {
        //$this->validate_privilege('read');
        //$data = $this->data;
        //$data['page_name'] = 'Scholarships';
        //$data['data'] = array(
        //	'new' => $this->validate_privilege('write')
        //);
        //$this->load->view('common/home', $data);
        $this->redirect_acl('read','site');

        if ($this->input->get('confirm') == 1)
        {
            //$this->Scholarships_model->update_scoreboard($_SESSION['user_id']);
            //$this->Scholarships_model->set_user_state($_SESSION['user_id'], 1);
            $this->__redirect('GPA/degree');
        }
        $terms_body = $this->Site_model->read_config('scholarships/index.json');
        $this->form_navbar();

        $data = array(
            'terms_body'  => json_decode($terms_body, true),
            'confirm_url' => base_url('scholarships?confirm=1')
        );
        $this->data['article'] = true;

        $this->__view('ji/scholarships/index', $data);
    }
    
    public function all()
    {
        $this->data['page_name'] = 'Scholarships';
        $this->add_nav('all')->form_navbar();
        
        $data = array(
            'key' => 'all'
        );
        
        $this->__view('ji/scholarships/list', $data);
    }
    
    public function undergraduate()
    {
        $this->data['page_name'] = 'Scholarships';
        $this->add_nav('undergraduate')->form_navbar();
    
        $data = array(
            'key' => 'undergraduate'
        );
    
        $this->__view('ji/scholarships/list', $data);
    }
    
    public function graduate()
    {
        $this->data['page_name'] = 'Scholarships';
        $this->add_nav('graduate')->form_navbar();
    
        $data = array(
            'key' => 'graduate'
        );
    
        $this->__view('ji/scholarships/list', $data);
    }
    
    public function my()
    {
        $this->data['page_name'] = 'Scholarships';
        $this->add_nav('my')->form_navbar();
    
        $data = array(
            'key' => 'my'
        );
        
        $this->__view('ji/scholarships/list', $data);
    }
    
    public function view()
    {
        $this->data['page_name'] = 'Scholarships - View';
        $this->form_navbar();
        
        $id = $this->input->get('id');
        $scholarships = $this->Scholarships_model->get_by_id($id);
        
        if ($scholarships->is_error()) $this->redirect();
        
        $data = array(
            'data'     => &$scholarships,
            'edit_url' => base_url('scholarships/edit?id=' . $id)
        );
        
        $this->data['js'] = 'ji/scholarships/view';
        $this->data['data'] = json_encode($data);
        
        $this->load->view('common/page', $this->data);
    }
    
    public function edit()
    {
        $this->data['page_name'] = 'Scholarships - Edit';
        $this->form_navbar();
        
        //$this->validate_privilege('admin_write');
        
        $id = $this->input->get('id');
        $scholarships = $this->Scholarships_model->get_by_id($id);
        
        if ($id > 0 && $scholarships->is_error()) $this->redirect();
    
        //print_r(base64_decode($scholarships->attachment));
        $scholarships->attachment = json_decode(base64_decode($scholarships->attachment));
        
        $data = array(
            'data' => &$scholarships,
            'ajax_url' => '/scholarships/ajax_edit'
        );
        
        $this->data['js'] = 'ji/scholarships/edit';
        $this->data['data'] = json_encode($data);
        
        $this->load->view('common/page', $this->data);
    }
    
    public function check()
    {
        //$this->validate_privilege('read');
        $id = $this->input->get('id');
        $scholarships = $this->Scholarships_model->get_by_id($id);
        if ($scholarships->is_error())
        {
            $this->redirect();
        }
        //print_r($scholarships);
        $data['page_name'] = $scholarships->title;
        $data['option'] = array(
            'id'    => $id,
            'type'  => 'scholarships',
            'title' => $data['page_name'],
            'item'  => $this->fill_option($this->editor_create, $scholarships),
            'url'   => '/scholarships/edit',
            'user'  => $_SESSION['user_id']
        );
        $this->load->view('common/viewer', $data);
    }
    
    public function ajax_search()
    {
        error_reporting(0);
        //$this->validate_privilege('read', false);
        $cmd = $this->input->get('cmd');
        $key = $this->input->get('key');
        if ($cmd == 'search')
        {
            $keywords = $this->input->get('keywords');
            $limit = $this->input->get('limit');
            $offset = $this->input->get('offset');
            $order = $this->input->get('order');
            $where = array();
            switch ($key)
            {
            case 'my':
                $data = '';
                echo $data;
                exit();
            case 'undergraduate':
            case 'graduate':
                $where['category'] = array($key, 'all');
                break;
            }
            $data = $this->Scholarships_model->search($keywords, $where, $limit, $offset, $order);
            //print_r(json_encode($key));
            //exit();
            echo $data;
        }
        exit();
    }
    
    public function ajax_edit()
    {
        //error_reporting(0);
        //$this->validate_privilege('admin_write', false);
        $data = $this->input->post('data');
        $id = $this->input->post('id');
        if ($id > 0)
        {
            $scholarships = $this->Scholarships_model->get_by_id($id);
            if ($scholarships->is_error())
            {
                echo 'validation failed';
                exit();
            }
        }
        $info = $this->process_option('scholarships', $id, $this->editor_create, $data);
        
        if ($info != 'success')
        {
            echo $info;
            exit();
        }
        
        if ($id > 0)
        {
            echo '/scholarships/check?id=' . $id;
            exit();
        }
        echo 'unknown error';
        exit();
    }
    
}
