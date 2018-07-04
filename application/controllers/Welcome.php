<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index()
    {
        $this->load->view('header');
        $this->load->view('iframeContent');
        $this->load->view('footer');
    }

    public function welcomeView()
    {
        $data = array(
            'theme' => 'a',
            'title' => '倉儲系統'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('welcomeView');
        $this->load->view('footer');
    }
}
