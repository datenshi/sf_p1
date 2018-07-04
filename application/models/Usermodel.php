<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usermodel extends CI_Model {

    public function insertUserData($userData)
    {
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        $result = $this->db->insert('user', $userData);

        return $result;
    }

    public function queryUserData()
    {
        $result = $this->db->get('user');

        return $result;
    }

    public function deleteUserData($userData)
    {
        $this->db->where('userID', $userData['userID']);
        $result = $this->db->delete('user');

        return $result;
    }

    public function updatePasswordData($userData)
    {
        // Get ID from session data
        $passwordData = array(
            'password' => password_hash($userData['password'], PASSWORD_DEFAULT)
        );
        //$userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        $this->db->where('userID', $userData['userID']);
        $result = $this->db->update('user', $passwordData);

        return $result;
    }
}