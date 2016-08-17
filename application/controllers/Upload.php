<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends Front_Controller
{
	
	public function index()
	{
		error_reporting(0);
		$this->load->library('UploadHandler');
		$upload_handler = new UploadHandler(
			array(
				'upload_dir'              => './files/',
				'upload_url'              => base_url('files') . '/',
				'script_url'              => base_url('upload'),
				'discard_aborted_uploads' => false,
				'print_response'          => true
			),
			true
		);
		exit();
	}
}
