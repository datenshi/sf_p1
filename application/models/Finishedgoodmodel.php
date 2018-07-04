<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finishedgoodmodel extends CI_Model {

    public function insertFinishedGoodData($finishedGoodData)
    {
        $result = $this->db->insert('finishedgood', $finishedGoodData);

        return $result;
    }

    public function queryFinishedGoodData()
    {
        $result = $this->db->get('finishedgood');

        return $result;
    }

    public function queryFinishedGoodIDTypeData()
    {
        $this->db->select('finishedGoodID, finishedGoodType');
        $this->db->from('finishedgood');
        $result = $this->db->get();

        return $result;
    }

    public function updateFinishedGoodQuantityData($product, $packageNumber, $weight)
    {
        $this->db->set('totalPackageNumber', 'totalPackageNumber + ' . $packageNumber, FALSE);
        $this->db->set('totalWeight', 'totalWeight + ' . $weight, FALSE);
        $this->db->where('finishedGoodID', $product);
        $this->db->update('finishedgood');
    }
}