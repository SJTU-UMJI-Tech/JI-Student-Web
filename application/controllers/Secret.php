<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Secret extends Front_Controller
{
	protected function redirect()
	{
		$this->__redirect();
	}
	
	public function index()
	{
		
	}
	
	
	public function vote()
	{
		$data['page_name'] = 'VOTE FOR YLM';
		$this->load->view('secret/vote', $data);
	}
}
