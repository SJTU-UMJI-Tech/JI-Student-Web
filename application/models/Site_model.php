<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Site_model
 *
 * @category   ji-life
 * @package    ji-life
 * @author     tc-imba
 * @copyright  2016 umji-sjtu
 */
class Site_model extends CI_Model
{
    const TABLE_CONFIG = 'config';
    
    
    /** @var array */
    public $site_config;
    
    
    /**
     * Site_model constructor.
     */
    function __construct()
    {
        parent::__construct();
        
    }
    
    /**
     * 加载所有的网站设置项
     * @param string|array $keys
     * @return array
     */
    public function load_site_config($keys = array())
    {
        if (!is_array($keys))
        {
            $keys = array($keys);
        }
        $query = $this->db->select('obj,value')->from($this::TABLE_CONFIG)->where(1);
        if (count($keys) == 0)
        {
            $query = $query->where(array('module' => 'global'));
            $mtime = explode(' ', microtime());
            $startTime = floor(($mtime[1] + $mtime[0]) * 1000);
            $this->site_config['global_server_time'] = $startTime;
        }
        else
        {
            foreach ($keys as $key)
            {
                $query = $query->or_where(array('module' => $key));
            }
        }
        $query = $query->get();
        $settings = $query->result_array();
        foreach ($settings as $setting)
        {
            $this->site_config[$setting['obj']] = $setting['value'];
        }
        $this->load->vars($this->site_config);
        return $this->site_config;
    }
    
    /**
     * 更新网站设置
     * @param   array $data
     * @return  bool
     */
    public function update_site_config($data)
    {
        $update_data = array();
        foreach ($data as $key => $value)
        {
            $update_data[] = array(
                'obj'   => $key,
                'value' => $value
            );
        }
        return $this->db->update_batch($this::TABLE_CONFIG, $update_data, 'obj');
    }
    
    
    public function request_post($url = '', $post_data = array())
    {
        if (empty($url) || empty($post_data))
        {
            return false;
        }
        
        $o = "";
        foreach ($post_data as $k => $v)
        {
            $o .= $k . "=" . $v . "&";
        }
        $post_data = substr($o, 0, -1);
        
        $postUrl = $url;
        $curlPost = $post_data;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); // 要访问的地址
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($ch, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); // Post提交的数据包
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        
        $data = curl_exec($ch);//运行curl
        if (curl_errno($ch))
        {
            echo 'Errno' . curl_error($ch);//捕抓异常
        }
        curl_close($ch);
        return $data;
    }
    
    public function request_get($url = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        //curl_setopt($ch, CURLOPT_POST, 1); // 发送一个常规的Post请求
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); // Post提交的数据包
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        
        $data = curl_exec($ch);//运行curl
        if (curl_errno($ch))
        {
            echo 'Errno' . curl_error($ch);//捕抓异常
        }
        curl_close($ch);
        return $data;
    }
    
    /**
     * @param string       $table
     * @param string       $library
     * @param array        $keys
     * @param bool         $array
     * @param string|array $select
     * @return My_obj|array
     */
    public function get_object($table, $library = 'My_obj', $keys = array(), $array = false, $select = '*')
    {
        $query = $this->db->select($select)->from($table)->where($keys)->get();
        $this->load->library($library);
        /** @var My_obj $obj */
        if (!$array)
        {
            if ($query->num_rows() > 0)
            {
                $obj = $query->row(0, $library);
                return $obj;
            }
            eval('$obj = new ' . $library . '();');
            return $obj;
        }
        $obj_list = array();
        foreach ($query->result($library) as $obj)
        {
            if (!$obj->is_error())
            {
                $obj_list[] = $obj;
            }
        }
        return $obj_list;
    }
    
    /**
     * @param string       $table
     * @param string       $library
     * @param array        $fields
     * @param array|string $keywords
     * @param array        $where
     * @param array        $orders
     * @param int          $limit
     * @param int          $offset
     * @param array        $where_in
     * @param string       $select
     * @return string
     */
    public function search_object($table, $library = 'My_obj', $fields, $keywords = array(),
                                  $where = array(), $orders = array('CREATE_TIMESTAMP', 'DESC'),
                                  $limit = 0, $offset = 0, $where_in = array(), $select = '*')
    {
        if (is_string($keywords))
        {
            $keywords = $this->keywords_arr($keywords);
        }
        $this->db->select($select)->from($table);
        foreach ($where as $name => $items)
        {
            $this->db->group_start();
            foreach ($items as $item)
            {
                $this->db->or_where($name, $item);
            }
            $this->db->group_end();
        }
        foreach ($keywords as $keyword)
        {
            $this->db->group_start();
            foreach ($fields as $field)
            {
                $this->db->or_like($field, $keyword);
            }
            $this->db->group_end();
        }
        if ($limit > 0)
        {
            $this->db->limit($limit);
        }
        $this->db->offset($offset);
        foreach ($orders as $field => $order)
        {
            $this->db->order_by($field, $order);
        }
        $arr = $this->db->get()->result($library);
        $obj_list = array();
        foreach ($arr as $obj)
        {
            /** @var My_obj $obj */
            if (!$obj->is_error())
            {
                $obj_list[] = $obj;
            }
        }
        return json_encode($obj_list);
    }
    
    public function create_object($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    
    public function edit_object($table, $data, $id)
    {
        $this->db->update($table, $data, array('id' => $id));
        return $id;
    }
    
    
    /**
     * 净化 HTML
     * @param string $string
     * @return string
     */
    public function html_purify($string)
    {
        return preg_replace("/<([a-zA-Z]+)[^>]*>/", "", $string);
    }
    
    /**
     * BASE64 加密 HTML（净化）
     * @param string $string
     * @return string
     */
    public function html_base64($string)
    {
        return base64_encode($this->html_purify($string));
    }
    
    public function keywords_arr($keywords)
    {
        $arr = array();
        foreach (explode(' ', $keywords) as $str)
        {
            if ($str != '')
            {
                $arr[] = $str;
            }
        }
        return $arr;
    }
    
    
    /**
     * 重定向登录
     * @param string $type
     */
    public function redirect_login($type)
    {
        
    }
    
    public function is_login()
    {
        return isset($_SESSION['user_id']) && $_SESSION['user_id'] != '';
    }
    
    public function get_avatar()
    {
        if ($this->is_login())
        {
            $filename = 'uploads/avatar/' . $_SESSION['user_id'] . '.png';
            if (file_exists('./' . $filename))
            {
                return base_url($filename);
            }
        }
        return base_url('img/avatar-default.png');
    }
    
    public function get_relative_url()
    {
        $url = $_SERVER["REQUEST_URI"];
        if (ROOT_DIR != '')
        {
            $pattern = preg_replace('/\//', '\\/', ROOT_DIR);
            $url = preg_replace('/^' . $pattern . '\//', '/', $url);
        }
        return $url;
    }
    
    /**
     * @param $path string
     * @return null|string
     */
    public function read_config($path)
    {
        $filename = './config/' . $path;
        $file = fopen($filename, 'r');
        if (!$file)
        {
            return NULL;
        }
        $json_data = fread($file, filesize($filename));
        fclose($file);
        return $json_data;
    }
    
}

