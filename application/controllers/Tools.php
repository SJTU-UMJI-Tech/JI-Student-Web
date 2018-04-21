<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends Front_Controller
{
	protected function redirect()
	{
		$this->__redirect('tools');
	}
	
	public function index()
	{
		$this->load->view('common/header');
	}
	
	public function ExceltoLaTex()
	{
		$this->load->view('tools/exceltolatex');
	}
	
	public function gomoku(){
	    redirect('http://gomoku.sstia.tech');
    }
}
