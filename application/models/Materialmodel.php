<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materialmodel extends CI_Model {

    public function insertMaterialData($materialData)
    {
        $result = $this->db->insert('material', $materialData);

        return $result;
    }

    public function queryMaterialData()
    {
        $this->db->select('
            material.materialID,
            material.materialName,
            supplier.supplierName,
            packaging.packaging,
            packaging.unitWeight,
            materialusage.usingDepartment,
            supplier.unitPrice');
        $this->db->from('material');
        $this->db->join('supplier', 'material.materialID = supplier.material');
        $this->db->join('packaging', 'material.materialID = packaging.material');
        $this->db->join('materialusage', 'material.materialID = materialusage.material');
        $result = $this->db->get();

        return $result;
    }

    public function deleteMaterialData($materialData)
    {
        $this->db->where('materialID', $materialData['materialID']);
        $result = $this->db->delete('material');

        return $result;
    }

    public function queryMaterialSpecificColumn($queryData, $isOneRow)
    {
        $result = $this->db->query($queryData);

        if (true == $isOneRow) {
            return $result->row_array();
        }
        else {
            return $result;
        }
    }

    public function queryMaterialNameByMaterialID($materialID)
    {
        $this->db->select('materialName');
        $this->db->from('material');
        $this->db->where('materialID', $materialID);
        $result = $this->db->get();

        return $result->row_array();
    }

    public function updateMaterialQuantityData($material, $packageNumber, $weight, $money)
    {
        $this->db->set('totalPackageNumber', 'totalPackageNumber + ' . $packageNumber, FALSE);
        $this->db->set('totalWeight', 'totalWeight + ' . $weight, FALSE);
        $this->db->set('totalMoney', 'totalMoney + ' . $money, FALSE);
        $this->db->where('materialID', $material);
        $this->db->update('material');
    }
}
