<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Machine extends Front_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->add_nav('ENROLL')->add_nav('machine');
    }
    
    protected function redirect()
    {
        $this->__redirect('enrollment/machine');
    }
    
    public function index()
    {
        $this->redirect_login();
        $this->load->model('enrollment/Machine_model');
        
        $group_id = $this->Machine_model->get_user_group_by_id($_SESSION['user_id']);
        $group = $this->Machine_model->get_group_by_id($group_id);
        if ($group->id > 0)
        {
            // 已报名
            //echo 'yes';
            $this->load->model('User_model');
            $leader = $this->User_model->get_user($group->leader_id);
            $member_list = explode(',', $group->member);
            $member_info = $this->Machine_model->get_member_info($member_list, $group->id);
            $data = array(
                'registered'  => true,
                'user_name'   => $leader->user_name,
                'user_id'     => $leader->USER_ID,
                'class_id'    => $group->class_id,
                'member_info' => $member_info,
                'member_list' => $member_list,
                'group_id'    => $group->id,
                'group_url'   => base_url('enrollment/machine/verify?id=' . $group->id),
                'leader'      => $_SESSION['user_id'] == $leader->USER_ID
            );
        }
        else
        {
            // 未报名
            //echo 'no';
            $data = array(
                'registered'  => false,
                'user_name'   => $_SESSION['user_name'],
                'user_id'     => $_SESSION['user_id'],
                'member_info' => array(),
                'member_list' => array(),
                'leader'      => true
            );
        }
        
        $data['member_max'] = $this->Machine_model->member_max;
        $data['season'] = $this->Machine_model->season;
        $data['deadline'] = $this->Machine_model->deadline;
        $data['submit_url'] = base_url('enrollment/machine/submit');
        $data['cancel_url'] = base_url('enrollment/machine/cancel');
        $data['check_url'] = base_url('enrollment/machine/check');
        $data['valid'] = $this->Machine_model->is_time_valid();
        if (!$data['valid']) $data['leader'] = false;
        
        $this->form_navbar();
        
        $this->data['js'] = 'ji/enrollment/machine';
        $this->data['data'] = json_encode($data);
        
        
        $this->load->view('common/page', $this->data);
    }
    
    public function verify()
    {
        $this->redirect_login();
        $group_id = $this->input->get('id');
        $this->load->model('enrollment/Machine_model');
        $this->Machine_model->verify($_SESSION['user_id'], $group_id);
        $this->redirect();
    }
    
    
    public function cancel()
    {
        $this->redirect_login();
        $this->load->model('enrollment/Machine_model');
        $this->Machine_model->cancel($_SESSION['user_id']);
        $this->redirect();
    }
    
    public function submit()
    {
        $data = array(
            'status'  => 'error',
            'message' => 'Unknown error'
        );
        if (!$this->Site_model->is_login())
        {
            $data['message'] = '登录超时，请重新登陆';
            echo json_encode($data);
            exit();
        }
        $class_id = $this->input->get('class_id');
        $member_list = $this->input->get('member_list');
        $member_list = explode(',', $member_list);
        $this->load->model('enrollment/Machine_model');
        $data['message'] = $this->Machine_model->submit($_SESSION['user_id'], $class_id, $member_list);
        if ($data['message'] == 'ok') $data['status'] = 'ok';
        echo json_encode($data);
        exit();
    }
    
    public function check()
    {
        $data = array(
            'status' => 'error',
        );
        if (!$this->Site_model->is_login())
        {
            echo json_encode($data);
            exit();
        }
        $USER_ID = $this->input->get('user_id');
        $this->load->model('User_model');
        $user = $this->User_model->get_user($USER_ID);
        if (!$user->is_error())
        {
            $data['status'] = 'ok';
            $data['name'] = $user->user_name;
        }
        echo json_encode($data);
        exit();
    }
    
    public function qrcode()
    {
        $html = '<style>img{margin:0 auto;padding-top: 20px;}</style>' .
                '<div id="qrcode"></div>' .
                '<script src="' . ROOT_DIR . '/bower_modules/qrcode.js/qrcode.js"></script>' .
                '<script type="text/javascript">' .
                'new QRCode(document.getElementById("qrcode"), "' .
                base_url('enrollment/machine') . '");' .
                '</script>';
        echo $html;
        exit();
    }
    
}
