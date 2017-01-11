<?php defined('BASEPATH') OR exit('No direct script access allowed');

class GPA extends Front_Controller
{
	protected function redirect()
	{
		$this->__redirect('gpa');
	}
	
	public function index()
	{
		$this->load->view('common/header');
	}
}
