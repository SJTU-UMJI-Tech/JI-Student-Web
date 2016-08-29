<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Orgnization extends Front_Controller
{
	public function redirect()
	{
		redirect(base_url('orgnization'));
	}
	
	public function index()
	{
		$this->load->view('common/header');
	}
}
