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
					'graduate'      => 'Graduates',
					'all'           => 'Both'
				),
				'default' => 'all'
			),
			array('name' => 'abstract', 'label' => 'Abstract', 'type' => 'textarea'),
			array('name' => 'amount', 'label' => 'Amount', 'type' => 'text'),
			array('name' => 'deadline', 'label' => 'Deadline', 'type' => 'date',),
			array('name' => 'content', 'label' => 'Content', 'type' => 'markdown'),
			array('name' => 'attachment', 'label' => 'Attachment', 'type' => 'file')
		);
	}
	
	public function redirect()
	{
		redirect(base_url('scholarships'));
	}
	
	public function index()
	{
		$data = $this->data;
		$data['page_name'] = 'Scholarships';
		$this->load->view('common/home', $data);
	}
	
	public function edit()
	{
		$id = $this->input->get('id');
		if ($id > 0)
		{
			$scholarships = $this->Scholarships_model->get_by_id($id);
			if ($scholarships->is_error())
			{
				$this->redirect();
			}
			$title = 'Edit -> ' . $scholarships->title;
		}
		else
		{
			$id = 0;
			$title = 'New scholarships';
		}
		$data = $this->data;
		$data['page_name'] = 'Edit scholarships';
		$data['option'] = array(
			'id'    => $id,
			'type'  => 'scholarships',
			'title' => $title,
			'item'  => $this->editor_create,
			'url'   => '/scholarships/ajax_edit',
			'user'  => $_SESSION['user_id']
		);
		$this->load->view('common/editor', $data);
	}
	
	public function check()
	{
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
	
	public function ajax()
	{
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
		$data = json_decode($this->input->post('data'), true);
		
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
		
		if ($id > 0)
		{
			echo '/scholarships/check?id=' . $id;
			exit();
		}
		echo 'unknown error';
		exit();
	}
	
}
