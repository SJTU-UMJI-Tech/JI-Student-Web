<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Front_Controller
{
	
	
	public function index()
	{
		$data['page_name'] = 'UM-SJTU JI Online';
		$this->load->view('home', $data);
	}
}
