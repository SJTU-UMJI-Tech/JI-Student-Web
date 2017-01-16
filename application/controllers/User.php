<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Front_Controller
{
    const OAUTH_VER     = 2.0;
    const JIACCOUNT_URL = 'http://www.umji.sjtu.edu.cn/student';
    const OAUTH2_URL    = 'https://jaccount.sjtu.edu.cn/oauth2/';
    
    
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
    
    private function oauth2_url($str)
    {
        if ($str && $str[0] == '/') $str = substr($str, 1);
        return $this::OAUTH2_URL . $str;
    }
    
    public function jiaccount()
    {
        $uri = $this->input->get('url');
        $logout = $this->input->get('logout');
        if (!filter_var($uri, FILTER_VALIDATE_URL))
        {
            echo 'url validation failed!';
            exit();
        }
        $query = array(
            'uri'       => $uri,
            'auth_type' => 'jiaccount'
        );
        if ($logout == '1')
        {
            redirect(base_url('user/logout?' . http_build_query($query)));
        }
        else
        {
            redirect(base_url('user/login?' . http_build_query($query)));
        }
    }
    
    public function jiaccount_logout()
    {
        $this->jiaccount_redirect($this->input->get('uri'), array('logout' => '1'));
    }
    
    protected function jiaccount_redirect($url, $query = array())
    {
        $parsed = parse_url($url);
        if (!isset($parsed['query']))
        {
            $url .= '?' . http_build_query($query);
        }
        else
        {
            $temp = array();
            parse_str($parsed['query'], $temp);
            $query = http_build_query($temp + $query);
            $url = preg_replace('/(?<=\?)(.*)/', $query, $url);
        }
        header('Location: ' . $url);
        exit();
    }
    
    public function login()
    {
        /** In the development mode, we will use ji-account api to login */
        if (ENVIRONMENT == 'development')
        {
            
            $result = $this->input->get('result');
            if ($result == 'success')
            {
                $_SESSION['user_id'] = $this->input->get('user_id');
                $_SESSION['user_name'] = $this->input->get('user_name');
                $_SESSION['user_type'] = $this->input->get('user_type');
                unset($_SESSION["logout"]);
                redirect(base_url($this->input->get('uri')));
            }
            header('Location: ' . $this::JIACCOUNT_URL . '/user/jiaccount?url='
                   . urlencode(base_url('user/login') . '?uri=' . $this->input->get('uri')));
            exit();
            
        }
        /** Here Jaccount have two versions: OAuth 1.0 & 2.0 */
        $redirect_query = array(
            'uri'       => $this->input->get('uri'),
            'auth_type' => $this->input->get('auth_type')
        );
        print_r($redirect_query);
        
        
        if ($this::OAUTH_VER >= 2.0)
        {
            $data = base64_encode(json_encode($redirect_query));
            echo $data;
            $redirect_uri = base_url('user/auth2?data=' . $data);
            $query = array(
                'response_type' => 'code',
                'client_id'     => $this->get_site_config('user_client_id_oauth2'),
                'redirect_uri'  => $redirect_uri
            );
            //echo 'https://jaccount.sjtu.edu.cn/oauth2/authorize?' . http_build_query($query);
            //exit();
            header('Location: ' . $this->oauth2_url('authorize?' . http_build_query($query)));
            exit();
        }
        else if ($this::OAUTH_VER >= 1.0)
        {
            redirect(base_url('user/auth1?' . http_build_query($redirect_query)));
        }
        
        
    }
    
    public function auth2()
    {
        $data = $this->input->get('data');
        $data_info = json_decode(base64_decode($data), true);
        $redirect_uri = base_url('user/auth2?data=') . $data;
        
        print_r($data_info);
        
        $auth_code = $this->input->get('code');
        
        $url = $this->oauth2_url('token');
        $post_data = array(
            'grant_type'    => 'authorization_code',
            'code'          => $auth_code,
            'redirect_uri'  => $redirect_uri,
            'client_id'     => $this->get_site_config('user_client_id_oauth2'),
            'client_secret' => $this->get_site_config('user_client_secret_oauth2')
        );
        
        $token_json = $this->Site_model->request_post($url, $post_data);
        $token_info = json_decode($token_json);
        if (!isset($token_info->error))
        {
            $url = "https://api.sjtu.edu.cn/v1/me/profile?access_token=" . $token_info->access_token;
            $usr_json = $this->Site_model->request_get($url);
            $usr_info = json_decode($usr_json);
            print_r($usr_info);
            if ($usr_info->error == 0)
            {
                // 成功
                if ($data_info['auth_type'] == 'jiaccount')
                {
                    $query = array(
                        'result'    => 'success',
                        'jaccount'  => $usr_info->entities[0]->account,
                        'user_id'   => $usr_info->entities[0]->code,
                        'user_name' => $usr_info->entities[0]->name,
                        'user_type' => $usr_info->entities[0]->userType
                    );
                    $this->jiaccount_redirect($data_info['uri'], $query);
                }
                else
                {
                    $_SESSION["user_id"] = $usr_info->entities[0]->code;
                    $_SESSION["user_name"] = $usr_info->entities[0]->name;
                    $_SESSION["user_type"] = $usr_info->entities[0]->userType;
                    unset($_SESSION["logout"]);
                    $this->__redirect($data_info['uri']);
                }
                exit();
            }
            else
            {
                echo $usr_info->error;
            }
        }
        else
        {
            echo 'login failed...<br>';
            print_r($token_info);
        }
        // 失败
        if ($data_info['auth_type'] == 'jiaccount')
        {
            $query = array('result' => 'fail');
            $this->jiaccount_redirect($data_info['uri'], $query);
        }
        else
        {
            $this->__redirect($data_info['uri']);
        }
        
        
    }
    
    // Deprecated
    public function auth1()
    {
        $redirect_query = array(
            'uri'       => $this->input->get('uri'),
            'auth_type' => $this->input->get('auth_type')
        );
        $redirect_uri = ROOT_DIR . '/user/auth1?' . http_build_query($redirect_query);
        $this->load->library('JAccount');
        $jam = new JAccountManager($this->get_site_config('user_client_id'), 'jaccount');
        $ht = $jam->checkLogin($redirect_uri);
        /*print_r($ht);
        print_r($redirect_query);
        exit();*/
        if ($redirect_query['auth_type'] == 'jiaccount')
        {
            if ($ht != NULL)
            {
                $query = array(
                    'result'    => 'success',
                    'jaccount'  => $ht['uid'],
                    'user_id'   => $ht['id'],
                    'user_name' => $ht['chinesename']
                );
            }
            else
            {
                $query = array('result' => 'fail');
            }
            $this->jiaccount_redirect($redirect_query['uri'], $query);
        }
        else
        {
            if ($ht != NULL)
            {
                $_SESSION["user_id"] = $ht['id'];
                $_SESSION["username"] = $ht['chinesename'];
            }
            redirect(base_url($redirect_query['uri']));
            
        }
    }
    
    protected function remove_sessions()
    {
        unset($_SESSION["user_id"]);
        unset($_SESSION["user_name"]);
        unset($_SESSION["user_type"]);
        $_SESSION["logout"] = 1;
    }
    
    public function logout()
    {
        $uri = $this->input->get('uri');
        $auth_type = $this->input->get('auth_type');
        
        if (!$this->Site_model->is_login() && $auth_type != 'jiaccount')
        {
            header('Location: ' . base_url($uri));
        }
        if (ENVIRONMENT == 'development')
        {
            if (!(isset($_SESSION["user_id"]) && $_SESSION["user_id"]))
            {
                redirect(base_url($uri));
                exit();
            }
            $this->remove_sessions();
            header('Location: ' . $this::JIACCOUNT_URL . '/user/jiaccount?logout=1&url='
                   . urlencode(base_url('user/logout') . '?uri=' . $uri));
        }
        
        if ($auth_type == 'jiaccount')
        {
            $redirect_uri = '/user/jiaccount_logout?uri=' . urlencode($uri);
        }
        else
        {
            $this->remove_sessions();
            $redirect_uri = $uri;
        }
        if ($this::OAUTH_VER >= 2.0)
        {
            $query = array('post_logout_redirect_uri' => base_url($redirect_uri));
            header('Location: ' . $this->oauth2_url('logout?' . http_build_query($query)));
        }
        else if ($this::OAUTH_VER >= 1.0)
        {
            $this->load->library('JAccount');
            $jam = new JAccountManager($this->get_site_config('user_client_id'), 'jaccount');
            $jam->logout(ROOT_DIR . $redirect_uri);
        }
    }
    
    public function settings()
    {
        $data = $this->data;
        $data['page_name'] = 'Settings';
        $this->load->view('user/settings', $data);
        
    }
}
