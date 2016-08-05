<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Scholarship_obj
 *
 * The operations of scholarships
 *
 * @category   ji
 * @package    ji
 * @author     tc-imba
 * @copyright  2016 umji-sjtu
 */
class Scholarship_obj extends My_obj
{
	/** -- The vars in the table `scholarship` -- */
	/** @var int    int(11)      */
	public $id;
	/** @var string varchar(200) */
	public $title;
	/** @var string TEXT         */
	public $abstract;
	/** @var string TEXT         */
	public $content;
	/** @var string varchar(25)  */
	public $amount;
	/** @var string varchar(25)  */
	public $type;
	/** @var string varchar(25)  */
	public $category;
	/** @var string varchar(25)  */
	public $deadline;
	/** @var string varchar(100)邮箱 */
	public $email;
	/** @var string timestamp   创建时间 */
	public $CREATE_TIMESTAMP;
	/** @var string timestamp   更新时间 */
	public $UPDATE_TIMESTAMP;
	
	public function __construct($data = array())
	{
		parent::__construct($data, 'id');
	}
	
}

