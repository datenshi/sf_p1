<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loginmodel extends CI_Model {

    public function isLoginValid($loginData)
    {
        $this->db->select('userID, password');
        $this->db->where('userID', $loginData['userID']);
        $query = $this->db->get('account');

        if (empty($query->result())) {
            return false;
        }
        else {
            foreach ($query->result() as $row)
            {
                if (true == password_verify($loginData['password'], $row->password)) {
                    return true;
                }
            }
            return false;
        }
    }
}