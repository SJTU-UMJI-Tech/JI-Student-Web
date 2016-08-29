<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Front_Controller
{
	public function redirect()
	{
		redirect(base_url(''));
	}
	
	public function index()
	{
		$data['page_name'] = 'UM-SJTU JI Online';
		
		$obj = $this->Site_model->get_object('jbxx', 'User_obj', array('USER_ID' => '515370910207'));
		
		$this->load->view('home', $data);
		
	}
}
