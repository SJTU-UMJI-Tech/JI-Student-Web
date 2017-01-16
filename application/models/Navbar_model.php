<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Navbar_model
 *
 * @category   ji-life
 * @package    ji-life
 * @author     tc-imba
 * @copyleft   2016-2017 umji-sjtu
 */
class Navbar_model extends CI_Model
{
    /**
     * Navbar_model constructor.
     */
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_navbar_data()
    {
        $filename = './config/navbar.json';
        $file = fopen($filename, 'r');
        if (!$file)
        {
            return NULL;
        }
        $json_data = fread($file, filesize($filename));
        fclose($file);
        $json_info = json_decode($json_data, true);
        return $json_info;
    }
    
    /**
     * @param $data
     * @return string
     */
    protected function generate_navbar_rec($data, $depth = 0)
    {
        $html = '';
        foreach ($data as $key => $value)
        {
            $html .= (isset($value['active']) && $value['active'] ? '<li class="active">' : '<li>');
            $html .= '<a href="' . (isset($value['href']) ? base_url($value['href']) : 'javascript:void(0);') . '">';
            if (isset($value['icon'])) $html .= '<i class="fa ' . $value['icon'] . ' fa-fw"></i>';
            if (isset($value['name']))
            {
                if ($depth == 0) $html .= '<span class="nav-label">' . $value['name'] . '</span>';
                else $html .= $value['name'];
            }
            //$html .= '<span class="nav-label">' . (isset($value['name']) ? $value['name'] : '') . '</span>';
            if (isset($value['children'])) $html .= '<span class="fa arrow"></span>';
            $html .= '</a>';
            if (isset($value['children']))
                $html .= '<ul class="nav ' . ($depth == 0 ? 'nav-second-level' : 'nav-third-level') .
                         (isset($value['active']) && $value['active'] ? '' : ' collapse') . '">' .
                         $this->generate_navbar_rec($value['children'], $depth + 1) . '</ul>';
        }
        $html .= '</li>';
        return $html;
    }
    
    public function generate_navbar($data)
    {
        if (isset($data['children'])) return $this->generate_navbar_rec($data['children']);
        return '';
    }
}

