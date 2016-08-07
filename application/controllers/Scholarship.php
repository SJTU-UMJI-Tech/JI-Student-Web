<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Scholarship extends Front_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->Site_model->load_site_config('scholarship');
		$this->load->model('Scholarship_model');
	}
	
	public function index()
	{
		$data = $this->data;
		$data['page_name'] = 'Scholarships';
		$this->load->view('scholarship/home', $data);
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
			
			$data = $this->Scholarship_model->search($keywords, $limit, $offset, $order);
			echo $data;
		}
		
		exit();
	}
}
