<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

abstract class Front_Controller extends CI_Controller
{
    //public $site_config;
    public $data;
    public $navigation;
    
    abstract protected function redirect();
    
    protected function __redirect($url = '')
    {
        $redirect_url = base_url($url);
        if ($redirect_url != base_url($_SERVER['REQUEST_URI']))
        {
            redirect($redirect_url);
        }
        else
        {
            redirect(base_url(''));
        }
    }
    
    const UPLOAD_DIR = './uploads/';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Site_model');
        $this->load->switch_view_on();
        /** 设置语言 */
        if (!isset($_SESSION['language']))
        {
            $_SESSION['language'] = $this->config->item('language');
        }
        else
        {
            $this->config->set_item('language', $_SESSION['language']);
        }
        
        if (ENVIRONMENT == 'development')
        {
            $this->output->enable_profiler(true);
        }
        
        
        $this->load->library('My_obj');
        $this->Site_model->load_site_config();
        //$this->load->language('ta_main');
        $this->data = array(
            'type' => 'default'
        );
        $this->navigation = array();
    }
    
    public function get_site_config($key)
    {
        return $this->Site_model->site_config[$key];
    }
    
    protected function fill_option(&$options, $object)
    {
        foreach ($options as $index => $option)
        {
            $name = $option['name'];
            if (isset($object->$name))
            {
                $value = $object->$name;
                switch ($option['type'])
                {
                case 'file':
                    $value = json_decode(base64_decode($value), true);
                }
                $options[$index]['value'] = $value;
            }
        }
        return $options;
    }
    
    protected function process_option($table, &$id, $options, $data)
    {
        $files = array();
        $new_data = array();
        foreach ($options as $key => $option)
        {
            $exist = isset($data[$option['name']]);
            $value = $exist ? $data[$option['name']] : '';
            switch ($option['type'])
            {
            case 'text':
            case 'textarea':
            case 'markdown':
                $value = $this->Site_model->html_purify($value);
                if (isset($option['min']) && strlen($value) < $option['min']
                    || isset($option['max']) && strlen($value) > $option['max']
                )
                {
                    return 'text length error';
                }
                break;
            case 'date':
            case 'time':
                $time = strtotime($value);
                if ($time <= 0)
                {
                    return 'time format error';
                }
                if ($option['type'] == 'date')
                {
                    $value = date('Y-m-d', $time);
                }
                else
                {
                    $value = date('Y-m-d H:i', $time);
                }
                break;
            case 'file':
                $files[$key] = $value;
                $value = '';
                break;
            }
            $new_data[$option['name']] = $value;
        }
        
        if ($id <= 0)
        {
            $id = $this->Site_model->create_object($table, $new_data);
        }
        
        foreach ($files as $key => $value)
        {
            $option = $options[$key];
            $dir = $this::UPLOAD_DIR . $table . '/' . $id . '/';
            if (!is_dir($dir))
            {
                mkdir($dir, 0755, true);
                mkdir($dir . '/thumbnail', 0755, true);
            }
            foreach ($value as $index => $file)
            {
                $url = $file['url'];
                $parsed = parse_url($url);
                $query = array();
                parse_str($parsed['query'], $query);
                $filename = urldecode($query['file']);
                if (!isset($query['dir']))
                {
                    if (!rename('./uploads/temp/' . $filename, $dir . $filename))
                    {
                        unset($value[$index]);
                        continue;
                    }
                    rename('./uploads/temp/thumbnail/' . $filename,
                           $dir . 'thumbnail/' . $filename);
                    $query['dir'] = $dir;
                    $url = http_build_query($query, NULL, '&', PHP_QUERY_RFC3986);
                }
                $value[$index]['url'] = base_url('upload?' . $url);
            }
            $new_data[$option['name']] = base64_encode(json_encode($value));
        }
        
        $id = $this->Site_model->edit_object($table, $new_data, $id);
        
        return 'success';
    }
    
    protected function redirect_login()
    {
        if (isset($_SESSION['logout']) && $_SESSION['logout'])
        {
            unset($_SESSION['logout']);
            $this->__redirect();
        }
        if (!$this->Site_model->is_login())
        {
            if ($this->input->get('logout')) $this->__redirect();
            else redirect('user/login?uri=' . $this->Site_model->get_relative_url());
        }
    }
    
    protected function validate_privilege($privilege, $redirect = true, $extra = array())
    {
        $this->load->model('Privilege_model');
        $extra += array($this->data['type'] => $privilege);
        if (!$this->Privilege_model->has_privilege($_SESSION['user_id'], $extra))
        {
            if ($redirect)
            {
                if ($this->input->get('logout') == '1')
                {
                    redirect(base_url());
                }
                else if (!$_SESSION['user_id'])
                {
                    redirect('user/login?uri=' . $this->Site_model->get_relative_url());
                }
                else
                {
                    $this->redirect();
                }
            }
            return false;
        }
        return true;
    }
    
    protected function add_nav($str)
    {
        $this->navigation[] = $str;
        return $this;
    }
    
    protected function form_navbar()
    {
        $this->load->model('Navbar_model');
        $nav = $this->Navbar_model->get_navbar_data();
        $temp = &$nav;
        foreach ($this->navigation as $value)
        {
            if (!isset($temp['children'])) break;
            if (!is_array($temp['children']) || !isset($temp['children'][$value])) break;
            $temp = &$temp['children'][$value];
            $temp['active'] = true;
        }
        $this->data['navbar'] = $this->Navbar_model->generate_navbar($nav);
        return $this;
    }
}