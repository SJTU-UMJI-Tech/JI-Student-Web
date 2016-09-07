<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Front_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->Site_model->load_site_config('user');
	}
	
	protected function redirect()
	{
		$this->__redirect();
	}
	
	public function index()
	{
		
	}
	
	public function login()
	{
		if (ENVIRONMENT == 'development')
		{
			$user_id = $this->input->get('user_id');
			$username = $this->input->get('username');
			if ($user_id)
			{
				$_SESSION['user_id'] = $user_id;
				$_SESSION['username'] = $username;
				redirect(base_url(''));
			}
			header('Location: http://ji.sjtu.edu.cn/student/login.php?type=development');
			exit();
		}
		else
		{
			$redirect_uri = base_url('user/auth') . '?uri=' . urlencode($this->input->get('uri'));
		}
		header('Location: https://jaccount.sjtu.edu.cn/oauth2/authorize?response_type=code&client_id=jaji20150623&redirect_uri=' .
		       $redirect_uri);
	}
	
	public function auth()
	{
		$redirect_uri = base_url($this->input->get('uri'));
		$auth_code = $this->input->get('code');
		
		$url = 'https://jaccount.sjtu.edu.cn/oauth2/token';
		$post_data = array(
			'grant_type'    => 'authorization_code',
			'code'          => $auth_code,
			'redirect_uri'  => $redirect_uri,
			'client_id'     => $this->get_site_config('user_client_id'),
			'client_secret' => $this->get_site_config('user_client_secret')
		);
		
		$token_json = $this->Site_model->request_post($url, $post_data);
		$token_info = json_decode($token_json);
		
		if (isset($token_info->error))
		{
			echo $token_info->error;
			//$_SESSION["user_id"] = '515370910207';
			//$_SESSION["username"] = '刘逸灏';
			redirect($redirect_uri);
		}
		
		$url = "https://api.sjtu.edu.cn/v1/me/profile?access_token=" . $token_info->access_token;
		$usr_json = $this->Site_model->request_get($url);
		$usr_info = json_decode($usr_json);
		
		if ($usr_info->error != 0)
		{
			echo $usr_info->error;
			//$_SESSION["user_id"] = '515370910207';
			//$_SESSION["username"] = '刘逸灏';
			redirect($redirect_uri);
		}
		
		$_SESSION["user_id"] = $usr_info->entities[0]->code;
		$_SESSION["username"] = $usr_info->entities[0]->name;
		
		redirect($redirect_uri);
	}
	
	public function logout()
	{
		$_SESSION["user_id"] = '';
		$_SESSION["username"] = '';
		$this->load->library('JAccount');
		$jam = new JAccountManager('jaji20150623', 'jaccount');
		$jam->logout();
	}
	
	public function settings()
	{
		$data = $this->data;
		$data['page_name'] = 'Settings';
		$this->load->view('user/settings', $data);
		
	}
}
