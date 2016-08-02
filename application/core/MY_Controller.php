<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_Controller extends CI_Controller
{
	//public $site_config;
	public $data;
	
    public function __construct()
    {
        parent::__construct();

	    /** 设置语言 */
	    if (!isset($_SESSION['language']))
	    {
		    $_SESSION['language'] = $this->config->item('language');
	    }
	    else
	    {
		    $this->config->set_item('language', $_SESSION['language']);
	    }

	    $this->output->enable_profiler(TRUE);
		
        $this->load->model('Site_model');
	    $this->load->library('My_obj');
        $this->Site_model->load_site_config();
	    //$this->load->language('ta_main');
	    $this->data = array();
    }
	
	public function get_site_config($key)
	{
		return $this->Site_model->site_config[$key];
	}
}