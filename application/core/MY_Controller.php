<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

defined('VERSION') OR define('VERSION', '0.2.0');

abstract class Front_Controller extends CI_Controller
{
    public $data;
    public $navigation;
    
    /** @var string $name 验证权限时使用的资源名 */
    public $name = NULL;
    
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
    
    /**
     * Front_Controller constructor.
     * 所有前端Controller都应继承Front_Controller
     * 对网站进行整体初始化
     */
    public function __construct()
    {
        parent::__construct();
        // 加载站点主模块
        $this->load->model('Site_model');
        //$this->load->switch_view_on();
        
        // 设置语言
        if (!isset($_SESSION['language']))
        {
            $_SESSION['language'] = $this->config->item('language');
        }
        else
        {
            $this->config->set_item('language', $_SESSION['language']);
        }
        //$this->load->language('ta_main');
        
        
        // 开启方便调试的profiler
        if (ENVIRONMENT == 'development')
        {
            $this->output->enable_profiler(true);
        }
        
        // 加载ACL模块
        $this->load->model('ACL_model');
        // 加载数据库对象模块
        $this->load->library('My_obj');
        // 从数据库加载站点设置
        $this->Site_model->load_site_config();
        // 初始化页面和侧边栏数据
        $this->data = array(
            'type' => 'default'
        );
        $this->navigation = array();
    }
    
    /**
     * 对Site_model->site_config进行封装方便调用
     * @param $key
     * @return string
     */
    public function get_site_config($key)
    {
        return $this->Site_model->site_config[$key];
    }
    
    /**
     * @TODO 移除这个函数
     * @deprecated
     * @param $options
     * @param $object
     * @return mixed
     */
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
    
    /**
     * @TODO 统一json返回格式
     * @param $table
     * @param $id
     * @param $options
     * @param $data
     * @return string
     */
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
    
    /**
     * 重定向登陆
     * 对一些状态进行判断以免循环调用时造成死循环
     */
    protected function redirect_login()
    {
        //if (ENVIRONMENT == 'development') return;
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
    
    /**
     * 根据ACL验证权限并重定向
     * 无权限且未登录则调用$this->redirect_login()
     * 无权限且已登陆则调用$this->__redirect()
     * @param  string $privilege
     * @param  string $resource
     */
    protected function redirect_acl($privilege = NULL, $resource = NULL)
    {
        if ($this->validate_acl($privilege, $resource)) return;
        if ($this->Site_model->is_login()) $this->redirect();
        else $this->redirect_login();
    }
    
    /**
     *
     * @param string $privilege
     * @param string $resource
     * @return bool
     */
    protected function validate_acl($privilege = NULL, $resource = NULL)
    {
        if ($resource == NULL) $resource = $this->name;
        return $this->ACL_model->isAllowed($resource, $privilege);
    }
    
    /**
     * 对侧边导航栏添加一层导航
     * @param $str
     * @return $this
     */
    protected function add_nav($str)
    {
        $this->navigation[] = $str;
        return $this;
    }
    
    /**
     * 将侧边导航栏数据写入$this->data['navbar_data']
     * @return $this
     */
    protected function form_navbar()
    {
        $this->load->model('Navbar_model');
        $nav = $this->Navbar_model->get_navbar_data();
        $nav['first'] = true;
        $temp = &$nav;
        foreach ($this->navigation as $value)
        {
            if (!isset($temp['children'])) break;
            if (!is_array($temp['children']) || !isset($temp['children'][$value])) break;
            $temp = &$temp['children'][$value];
            $temp['active'] = true;
        }
        $this->data['navbar'] = $this->Navbar_model->generate_navbar($nav);
        $this->data['navbar_data'] = json_encode($nav);
        return $this;
    }
    
    /**
     * 对前端框架进行初始化，并调用views/common/page
     * @param string $js
     * @param array  $data
     */
    protected function __view($js, &$data = array())
    {
        $this->form_navbar();
        $this->data['js'] = $js;
        $this->data['data'] = json_encode($data);
        $this->load->view('common/page', $this->data);
    }
}