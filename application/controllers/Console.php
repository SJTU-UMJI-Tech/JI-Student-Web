<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Console extends Front_Controller
{
    protected function redirect()
    {
        $this->__redirect('console');
    }
    
    public function index()
    {
        $this->data['js'] = 'ji/console/app';
        $this->output->enable_profiler(false);
        $this->load->view('common/blank', $this->data);
    }
}
