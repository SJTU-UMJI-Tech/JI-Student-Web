<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_Controller extends CI_Controller
{
	public $site_config;
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
		
        //$this->load->model('Mta_site');
	    //$this->load->library('My_obj');
        //$this->site_config = $this->Mta_site->get_site_config();
        //$this->load->vars($this->site_config);
	    //$this->load->language('ta_main');
	    //$this->data = array();
    }
	
	public function get_site_config($key)
	{
		return $this->site_config[$key];
	}
}