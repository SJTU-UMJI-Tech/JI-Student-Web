<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Scholarships extends Front_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->Site_model->load_site_config('scholarships');
		$this->load->model('Scholarships_model');
	}
	
	public function index()
	{
		$data = $this->data;
		$data['page_name'] = 'Scholarships';
		$this->load->view('scholarships/home', $data);
	}
	
	public function create()
	{
		$data = $this->data;
		$data['page_name'] = 'Create scholarships';
		$this->load->view('scholarships/create', $data);
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
			
			$data = $this->Scholarships_model->search($keywords, $limit, $offset, $order);
			echo $data;
		}
		exit();
	}
}
