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
	
	private function get_icon($filename)
	{
		$pos = strrpos($filename, '.');
		if ($pos !== false)
		{
			switch (substr($filename, $pos + 1))
			{
			case 'pdf':
				return 'fa fa-file-pdf-o';
			case 'ppt':
				return 'fa fa-file-powerpoint-o';
			}
		}
		return 'fa fa-file';
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
			$dir = $path . $node->text . '/';
			if (is_dir($dir))
			{
				$node->nodes = $this->get_file_tree($dir);
				$node->icon = 'fa fa-folder-o';
			}
			else
			{
				$node->href = '/upload?file=' . urlencode($node->text) . '&dir=' . urlencode($path)
				              . '&download=1';
				$node->icon = $this->get_icon($node->text);
			}
			$nodes[] = $node;
		}
		closedir($handle);
		return $nodes;
	}
	
	
}


