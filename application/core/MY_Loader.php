<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
	
	//开启新的视图目录
	public function switch_view_on()
	{
		$this->_ci_view_paths = array(FCPATH . 'views/' => true);
		//print_r($this->_ci_view_paths);
	}
	
	//关闭新的视图目录
	public function switch_view_off()
	{
		#just do nothing
	}
}