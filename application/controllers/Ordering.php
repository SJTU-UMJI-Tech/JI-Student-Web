<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ordering extends Front_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->add_nav('ORDER');
        $this->name = 'ordering';
    }

	protected function redirect()
	{
		$this->__redirect();
	}
	
	public function index()
	{
		
	}

	public function water()
    {
        $this->add_nav('water')->form_navbar();
        $data = array(

        );
        $this->__view('ji/ordering/water', $data);
    }

}
