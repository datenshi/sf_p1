<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addUserView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'f',
            'title' => '新增帳號'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addUserView');
        $this->load->view('footer');
    }

    public function addUser()
    {
        $this->load->model('usermodel');

        $userData['userID'] = $this->input->post('userID');
        $userData['userName'] = $this->input->post('userName');
        $userData['password'] = $this->input->post('password');
        $userData['authority'] = $this->input->post('authority');

        $result = $this->usermodel->insertUserData($userData);
        if (true == $result) {
            echo json_encode($userData);
        }
    }

    public function queryUserView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'f',
            'title' => '查詢帳號'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryUserView');
        $this->load->view('footer');
    }

    public function queryUser()
    {
        $this->load->model('usermodel');

        $query = $this->usermodel->queryUserData();
        echo json_encode($query->result_array());
    }

    public function deleteUser($userID)
    {
        $this->load->model('usermodel');

        $userData['userID'] = $userID;
        $result = $this->usermodel->deleteUserData($userData);
    }

    public function updateUserPassword()
    {
        $this->load->model('usermodel');

        // get userID from session
        $userData['userID'] = $this->input->post('userName');
        $userData['password'] = $this->input->post('password');

        $result = $this->usermodel->updatePasswordData($userData);
        if (true == $result) {
            echo "<h1>success!!</h1>";
        }
        else {
            echo "<h1>NOT success!!</h1>";
        }
    }
}
