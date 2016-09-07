<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends Front_Controller
{
	protected function redirect()
	{
		$this->__redirect();
	}
	
	public function index()
	{
		error_reporting(0);
		/** @TODO permission delete */
		$dir = $this->input->get('dir');
		if ($dir)
		{
			$new_dir = urldecode($dir);
		}
		else
		{
			$new_dir = './uploads/temp/';
		}
		$this->load->library('UploadHandler');
		$upload_handler = new UploadHandler(
			array(
				'upload_dir'              => $new_dir,
				'upload_url'              => base_url('uploads/temp') . '/',
				'script_url'              => base_url('upload'),
				'discard_aborted_uploads' => false,
				'print_response'          => true,
				'download_via_php'        => true,
				'delete_protection'       => $dir != ''
			),
			true
		);
		exit();
	}
}
