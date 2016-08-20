<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends Front_Controller
{
	
	public function index()
	{
		error_reporting(0);
		$dir = $this->input->get('dir');
		if ($dir)
		{
			$dir = urldecode($dir);
		}
		else
		{
			$dir = './uploads/temp/';
		}
		$this->load->library('UploadHandler');
		$upload_handler = new UploadHandler(
			array(
				'upload_dir'              => $dir,
				'upload_url'              => base_url('uploads/temp') . '/',
				'script_url'              => base_url('upload'),
				'discard_aborted_uploads' => false,
				'print_response'          => true,
				'download_via_php'        => true
			),
			true
		);
		exit();
	}
}
