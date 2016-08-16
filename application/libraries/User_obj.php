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
	/** -- The vars in the table `jbxx` -- */
	/** @var int    varchar(50) JAccount 账号 */
	public $ACCOUNT;
	/** @var int    varchar(50) 姓名 */
	public $USER_NAME;
	/** @var int    varchar(50) 用户类型 */
	public $USER_STYLE;
	/** @var int    varchar(50) 学号 */
	public $USER_ID;
	/** @var int    varchar(24) 出生日期 */
	public $CSRQ;
	/** @var int    varchar(100)邮箱 */
	public $EMAIL;
	/** @var int    char(1)     删除标记 */
	public $SCBJ;
	/** @var string timestamp   创建时间 */
	public $CREATE_TIMESTAMP;
	/** @var string timestamp   更新时间 */
	public $UPDATE_TIMESTAMP;
	
	public function __construct($data = array())
	{
		parent::__construct($data, 'USER_ID');
	}
	
}

