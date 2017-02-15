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
        $this->__redirect('Enrollment/Machine');
    }
    
    public function index()
    {
        $this->form_navbar();
        
        $data = array(
            'user_name' => $_SESSION['user_name'],
            'user_id'   => $_SESSION['user_id']
            //'data'     => &$scholarships,
            //'edit_url' => base_url('scholarships/edit?id=' . $id)
        );
        
        $this->data['js'] = 'ji/enrollment/machine';
        $this->data['data'] = json_encode($data);
        
        $this->load->view('common/page', $this->data);
    }
    
    public function machine()
    {
        $this->form_navbar();
        
        
        $this->load->view('common/page', $this->data);
    }
    
    public function qrcode()
    {
        $html = '<style>img{margin:0 auto;padding-top: 20px;}</style>' .
                '<div id="qrcode"></div>' .
                '<script src="' . ROOT_DIR . '/bower_modules/qrcode.js/qrcode.js"></script>' .
                '<script type="text/javascript">' .
                'new QRCode(document.getElementById("qrcode"), "' .
                base_url('Enrollment/Machine') . '");' .
                '</script>';
        echo $html;
        exit();
    }
    
}
