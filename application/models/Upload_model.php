<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Upload_model
 *
 * @category   ji-life
 * @package    ji-life
 * @author     tc-imba
 * @copyright  2016 umji-sjtu
 */
class Upload_model extends CI_Model
{
	/**
	 * Upload_model constructor.
	 */
	function __construct()
	{
		parent::__construct();
	}
	
	
	public function get_file_tree($path = './', $hidden = false)
	{
		if ($path[strlen($path) - 1] != '/')
		{
			$path .= '/';
		}
		if (!is_dir($path))
		{
			return NULL;
		}
		$handle = opendir($path);
		$nodes = array();
		while (($file = readdir($handle)) !== false)
		{
			if ($file == "." || $file == "..")
			{
				continue;
			}
			if (!$hidden && $file[0] == '.')
			{
				continue;
			}
			$node = new stdClass();
			$node->text = iconv('GB2312', 'UTF-8', $file);
			//$node->text = $file;
			$dir = $path . $file . '/';
			if (is_dir($dir))
			{
				$node->nodes = $this->get_file_tree($dir);
			}
			$nodes[] = $node;
		}
		closedir($handle);
		return $nodes;
	}
	
	
}

