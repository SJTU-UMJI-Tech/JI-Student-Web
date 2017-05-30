<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Career extends Front_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->data['type'] = 'career';
        $this->Site_model->load_site_config('career');
        $this->load->model('Career_model');
    }

    protected function redirect()
    {
        $this->__redirect('career');
    }

    public function index()
    {
        $data = $this->data;
        $data['page_name'] = 'Career';
        $this->load->view('common/home', $data);
    }

    public function edit()
    {
        $id = $this->input->get('id');
        if ($id > 0) {
            $scholarships = $this->Scholarships_model->get_by_id($id);
            if ($scholarships->is_error()) {
                $this->redirect();
            }
            $title = 'Edit -> ' . $scholarships->title;
        } else {
            $id = 0;
            $title = 'New scholarships';
        }
        $data = $this->data;
        $data['page_name'] = 'Edit scholarships';
        $data['option'] = array(
            'id' => $id,
            'type' => 'scholarships',
            'title' => $title,
            'item' => array(
                array('name' => 'Title', 'type' => 'text'),
                array('name' => 'Abstract', 'type' => 'textarea'),
                array('name' => 'Content', 'type' => 'editor')
            ),
            'url' => '/scholarships/ajax_edit'
        );
        $this->load->view('common/editor', $data);
    }

    public function check()
    {
        $id = $this->input->get('id');
        $scholarships = $this->Career_model->get_by_id($id);
        if ($scholarships->is_error()) {
            $this->redirect();
        }
        $data['page_name'] = $scholarships->title;
        $data['option'] = array(
            'id' => $id,
            'type' => 'scholarships',
            'title' => $data['page_name'],
            'item' => array(
                array('name' => 'Abstract', 'type' => 'text', 'value' => $scholarships->abstract),
                array('name' => 'Content', 'type' => 'md', 'value' => $scholarships->content)
            ),
            'url' => '/scholarships/edit'
        );
        $this->load->view('common/viewer', $data);
    }

    public function ajax()
    {
        $cmd = $this->input->get('cmd');
        $key = $this->input->get('key');
        if ($cmd == 'search') {
            $keywords = $this->input->get('keywords');
            $limit = $this->input->get('limit');
            $offset = $this->input->get('offset');
            $order = $this->input->get('order');
            $where = array();
            switch ($key) {
                case 'my':
                    $data = '';
                    echo $data;
                    exit();
                case 'undergraduate':
                case 'graduate':
                    $where['type'] = array($key, 'all');
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
        $data = json_decode($this->input->post('data'), true);

        $id = $this->input->post('id');
        if ($id > 0) {
            $scholarships = $this->Scholarships_model->get_by_id($id);
            if ($scholarships->is_error()) {
                echo 'validation failed';
                exit();
            }
        }
        $id = $this->Scholarships_model->edit_by_id($id, $data['Title'], $data['Abstract'], $data['Content']);
        if ($id > 0) {
            echo '/scholarships/check?id=' . $id;
            exit();
        }
        echo 'unknown error';
        exit();
    }

    public function wechat_list()
    {
        $data = $this->data;
        $data['category'] = $this->Career_model->get_category();
        //print_r($data['category']);
        $this->output->enable_profiler(false);
        $this->load->view('career/wechat_list', $data);
        print_r($data);
    }

    public function wechat_list_data()
    {
        $data = $this->data;
        $cmd = $this->input->get('cmd');
        $data = $this->Career_model->get_data();
        if ($cmd == 'search') {
            $category = $this->input->get('category');
            $keywords = $this->input->get('keyword');
            $page = $this->input->get('page');
            if ($page > 100) $page = 100;
            $per_page = $this->input->get('per_page');
            $where = array();
            $limit = ($page - 1) * $per_page;
            //$data = $this->Scholarships_model->search($keywords, $where, $limit, $offset);
            $data = $this->Career_model->search($keywords, $where, $limit, $per_page);
            echo $data;
        }
        print_r($data);
        exit();
    }

    public function wechat_list_edit()
    {
        error_reporting(0);
        //$this->validate_privilege('admin_write', false);
        $data = $this->Career_model->get_data();
        $id = $this->input->post('id');
        if ($id > 0) {
            $career = $this->Career_model->get_by_id($id);
            if ($career->is_error()) {
                echo 'validation failed';
                exit();
            }
            $per_page = $this->input->get('per_page');
            $abstract = $this->input->get('abstract');
            $limit = $per_page * ($abstract - 1);
            $where = array();
            $data = $this->Career_model->search($id, $where, $limit, $per_page);
            foreach ($data as $item) {
                if ($item['id'] == $id) {
                    $change_category = $this->input->post('change_category');
                    $change_title = $this->input->post('change_title');
                    if ($change_category > 0) $item['category'] = $change_category;
                    if ($change_title > 0) $item['title'] = $change_title;
                }
            }
        }
        else echo 'Please enter id';
        print_r($data);
    }
}


    

