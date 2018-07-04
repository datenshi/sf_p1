<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suppliermodel extends CI_Model {

    public function insertSupplierData($supplierData)
    {
        $result = $this->db->insert('supplier', $supplierData);

        return $result;
    }

    public function querySupplierData()
    {
        $this->db->select('
            supplier.supplierID,
            supplier.supplierName,
            material.materialName,
            supplier.unitPrice');
        $this->db->from('supplier');
        $this->db->join('material', 'supplier.material = material.materialID');
        $result = $this->db->get();

        return $result;
    }

    public function deleteSupplierData($supplierData)
    {
        $this->db->where('supplierID', $supplierData['supplierID']);
        $result = $this->db->delete('supplier');

        return $result;
    }

    public function querysSupplierNameIDByMaterialIDData($materialID)
    {
        $this->db->select('supplierID, supplierName');
        $this->db->from('supplier');
        $this->db->where('material', $materialID);
        $result = $this->db->get();

        return $result;
    }

    public function querySupplierNameBySupplierID($supplierID)
    {
        $this->db->select('supplierName');
        $this->db->from('supplier');
        $this->db->where('supplierID', $supplierID);
        $result = $this->db->get();

        return $result->row_array();
    }

    public function querySupplierUnitPriceBySupplierID($supplierID)
    {
        $this->db->select('unitPrice');
        $this->db->from('supplier');
        $this->db->where('supplierID', $supplierID);
        $result = $this->db->get();

        return $result->row_array();
    }
}