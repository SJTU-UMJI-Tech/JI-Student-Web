<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CPC extends Front_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->data['type'] = 'CPC';
		$this->Site_model->load_site_config('CPC');
		//$this->load->model('CPC_model');
	}
	
	protected function redirect()
	{
		$this->__redirect('CPC');
	}
	
	public function index()
	{
		$data = $this->data;
		$data['page_name'] = 'CPC';
		$this->load->view('common/home', $data);
	}
	
	public function edit()
	{
		$id = $this->input->get('id');
		if ($id > 0)
		{
			$scholarships = $this->Scholarships_model->get_by_id($id);
			if ($scholarships->is_error())
			{
				$this->redirect();
			}
			$title = 'Edit -> ' . $scholarships->title;
		}
		else
		{
			$id = 0;
			$title = 'New scholarships';
		}
		$data = $this->data;
		$data['page_name'] = 'Edit scholarships';
		$data['option'] = array(
			'id'    => $id,
			'type'  => 'scholarships',
			'title' => $title,
			'item'  => array(
				array('name' => 'Title', 'type' => 'text'),
				array('name' => 'Abstract', 'type' => 'textarea'),
				array('name' => 'Content', 'type' => 'editor')
			),
			'url'   => '/scholarships/ajax_edit'
		);
		$this->load->view('common/editor', $data);
	}
	
	public function check()
	{
		$id = $this->input->get('id');
		$scholarships = $this->Scholarships_model->get_by_id($id);
		if ($scholarships->is_error())
		{
			$this->redirect();
		}
		$data['page_name'] = $scholarships->title;
		$data['option'] = array(
			'id'    => $id,
			'type'  => 'scholarships',
			'title' => $data['page_name'],
			'item'  => array(
				array('name' => 'Abstract', 'type' => 'text', 'value' => $scholarships->abstract),
				array('name' => 'Content', 'type' => 'md', 'value' => $scholarships->content)
			),
			'url'   => '/scholarships/edit'
		);
		$this->load->view('common/viewer', $data);
	}
	
	public function ajax_intro()
	{
		error_reporting(0);
		echo '
# 密西根学院党委
交大密西根学院党委 (密院党委) 是在校党委领导下贯彻和落实新时期的党的路线、方针和政策；围绕学生指导发展委员会开展全院党建工作的部门。目前，下设有4个本科生党支部、3个研究生党支部、一个博士生党支部，维护一个党委院刊，一个党建工作微信推送平台等。

### 党委工作职责

一、 制定和组织实施学生党员发展规划和年度发展工作。

二、 积极开展马克思主义理论学习和时事政策教育活动，协助校党委搞好校、院两级理论中心组的学习，推进马克思主义理论研究队伍的建设。

三、 开展调查研究工作，及时把握师生员工的思想动态，深化对师生员工的思想政治教育。

四、 加强意识形态领域各项工作的领导，坚持以科学的理论武装人，以正确的舆论引导人，以高尚的精神塑造人，以优秀的形象鼓舞人。

五、 加强多媒体推送的工作，管理和协调月刊、微信等媒体推送党建新闻，把握舆论导向，负责入党申请者到正式党员的党务知识更新教育以及校级党委的组织筹备工作，公开宣传推广党员活动。

六、加强对海内外党员活动的指导和支持，营造海内外党员共同树立模范、共建卓越创新型院系的氛围。


### 党委负责人

李新碗：密西根学院党委书记

杨艳春：密西根学院党委主任

戴炯：密西根学院交工党支部负责人

吴琦：密西根学院学生党建负责人
		';
		exit();
	}
	
	public function ajax_theorem()
	{
		error_reporting(0);
		$this->load->model('Upload_model');
		$data = $this->Upload_model->get_file_tree('./uploads/CPC');
		//print_r($data);
		echo json_encode($data);
		//echo '[{"text": "Parent 2"},{"text": "Parent 3"},{"text": "Parent 4"},{"text": "Parent 5"}]';
		exit();
	}
	
	public function ajax()
	{
		$cmd = $this->input->get('cmd');
		$key = $this->input->get('key');
		if ($cmd == 'search')
		{
			$keywords = $this->input->get('keywords');
			$limit = $this->input->get('limit');
			$offset = $this->input->get('offset');
			$order = $this->input->get('order');
			$where = array();
			switch ($key)
			{
			case 'my':
				$data = '';
				echo $data;
				exit();
			case 'undergraduate':
			case 'graduate':
				$where['type'] = array($key, 'all');
				break;
			}
			$data = $this->Scholarships_model->search($keywords, $where, $limit, $offset, $order);
			//print_r(json_encode($key));
			//exit();
			echo $data;
		}
		exit();
	}
	
	public function ajax_edit()
	{
		$data = json_decode($this->input->post('data'), true);
		
		$id = $this->input->post('id');
		if ($id > 0)
		{
			$scholarships = $this->Scholarships_model->get_by_id($id);
			if ($scholarships->is_error())
			{
				echo 'validation failed';
				exit();
			}
		}
		$id = $this->Scholarships_model->edit_by_id($id, $data['Title'], $data['Abstract'], $data['Content']);
		if ($id > 0)
		{
			echo '/scholarships/check?id=' . $id;
			exit();
		}
		echo 'unknown error';
		exit();
	}
	
}
