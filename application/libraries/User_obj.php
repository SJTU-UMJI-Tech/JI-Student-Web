<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class User_obj
 *
 * The operations of users
 *
 * @category   ji
 * @package    ji
 * @author     tc-imba
 * @copyright  2016 umji-sjtu
 */
class User_obj extends My_obj
{
    /** -- The vars in the table `user` -- */
    /** @var int    varchar(20) 学号 */
    public $USER_ID;
    /** @var string varchar(50) 姓名 */
    public $user_name;
    /** @var string varchar(20) 用户类型 */
    public $user_type;
    /** @var string varchar(50) JAccount 账号 */
    public $account;
    /** @var string varchar(20) 出生日期 */
    public $birth;
    /** @var string varchar(10) 性别 */
    public $gender;
    /** @var string varchar(100)邮箱 */
    public $email;
    /** @var int    varchar(20) 手机号 */
    public $mobile;
    /** @var int    varchar(30) 学院编号 */
    public $institute_id;
    /** @var string varchar(20) 身份证 */
    public $card;
    /** @var string varchar(20) 身份证类型 */
    public $card_type;
    /** @var string timestamp   创建时间 */
    public $CREATE_TIMESTAMP;
    /** @var string timestamp   更新时间 */
    public $UPDATE_TIMESTAMP;
    
    public function __construct($data = array())
    {
        parent::__construct($data, 'USER_ID');
    }
    
}

