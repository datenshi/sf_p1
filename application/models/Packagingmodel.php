<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packagingmodel extends CI_Model {

    public function insertPackagingData($packagingData)
    {
        $result = $this->db->insert('packaging', $packagingData);

        return $result;
    }

    public function queryPackagingData()
    {
        $this->db->select('
            packaging.packagingID,
            material.materialName,
            packaging.packaging,
            packaging.unitWeight');
        $this->db->from('packaging');
        $this->db->join('material', 'packaging.material = material.materialID');
        $result = $this->db->get();

        return $result;
    }

    public function deletePackagingData($packagingData)
    {
        $this->db->where('packagingID', $packagingData['packagingID']);
        $result = $this->db->delete('packaging');

        return $result;
    }

    public function queryPackagingSpecificColumn($queryData, $isOneRow)
    {
        $result = $this->db->query($queryData);

        if (true == $isOneRow) {
            return $result->row_array();
        }
        else {
            return $result;
        }
    }

    public function queryPackagingByPackagingID($packagingID)
    {
        $this->db->select('packaging');
        $this->db->from('packaging');
        $this->db->where('packagingID', $packagingID);
        $result = $this->db->get();

        return $result->row_array();
    }

    public function queryPackagingUnitWeightByPackagingID($packagingID)
    {
        $this->db->select('unitWeight');
        $this->db->from('packaging');
        $this->db->where('packagingID', $packagingID);
        $result = $this->db->get();

        return $result->row_array();
    }
}