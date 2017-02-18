<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Zend\Permissions\Acl\Acl as Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

/**
 * Class ACL_model
 *
 * 使用 zendframework/zend-permissions-acl 进行权限控制
 * @see        http://zendframework.github.io/zend-permissions-acl/
 * @link       https://github.com/zendframework/zend-permissions-acl/
 *
 * @category   ji-life
 * @package    ji-life
 * @author     tc-imba
 * @copyright  2016-2017 umji-sjtu
 */
class ACL_model extends CI_Model
{
    
    /** @var string 权限控制文件储存位置 */
    const CONFIG_FILE = './config/acl.cfg';
    /** @var string 资源列表 */
    const TABLE_RESOURCE = 'acl_resource';
    /** @var string 群组列表 */
    const TABLE_GROUP = 'acl_group';
    /** @var string 群组可访问资源表 */
    const TABLE_ACCESS = 'acl_access';
    
    /**
     * @var Acl 权限控制实例
     */
    private $acl;
    
    /**
     * Privilege_model constructor.
     * 构造时使用 unserialize() 将 $acl 实例化
     * 如果没有文件就使用 $this->init() 生成文件
     */
    function __construct()
    {
        parent::__construct();
        if (file_exists($this::CONFIG_FILE))
        {
            $data = file_get_contents($this::CONFIG_FILE);
            if ($data)
            {
                $this->acl = unserialize($data);
                return;
            }
        }
        $this->init();
    }
    
    /**
     * 使用数据库设置生成一个权限控制文件
     */
    public function init()
    {
        $this->acl = new Acl();
        // 初始化资源表
        $query = $this->db->select('name')->get($this::TABLE_RESOURCE);
        foreach ($query->result() as &$item)
        {
            $this->acl->addResource(new Resource($item->name));
        }
        // 添加超级管理员
        $this->acl->addRole(new Role('super_admin'))
                  ->allow('super_admin', NULL, NULL);
        // 初始化组
        $query = $this->db->select('name,parents')->get($this::TABLE_GROUP);
        foreach ($query->result() as &$item)
        {
            $parents = $item->parents ? explode(',', $item->parents) : NULL;
            $this->acl->addRole(new Role($item->name), $parents);
        }
        // 初始化组权限
        $query = $this->db->select('group,resource,access')->get($this::TABLE_ACCESS);
        foreach ($query->result() as &$item)
        {
            // NULL表示赋予所有权限
            $access = $item->access ? explode(',', $item->access) : NULL;
            $this->acl->allow($item->group, $item->resource, $access);
        }
        //print_r($this->acl);
        file_put_contents($this::CONFIG_FILE, serialize($this->acl));
    }
    
    /**
     * 对 Acl 的 isAllowed() 进行封装，方便调用
     * @param  string $role
     * @param  string $resource
     * @param  string $privilege
     * @return bool
     */
    public function isAllowed($role = NULL, $resource = NULL, $privilege = NULL)
    {
        return $this->acl->isAllowed($role, $resource, $privilege);
    }
}

