<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materialoutwarehousemodel extends CI_Model {

    public function insertMaterialOutWarehouseData($materialOutWarehouseData)
    {
        $result = $this->db->insert('materialoutwarehouse', $materialOutWarehouseData);

        return $result;
    }

    public function queryMaterialOutWarehouseData()
    {
        $this->db->select('
            materialinwarehouse.material,
            material.materialName,
            supplier.supplierName,
            packaging.packaging,
            materialoutwarehouse.outWarehouseArea,
            materialoutwarehouse.outWarehouseDate,
            materialusage.usingDepartment,
            materialoutwarehouse.outWarehouseMember,
            materialoutwarehouse.outWarehousePackageNumber');
        $this->db->from('materialoutwarehouse');
        $this->db->join('materialinwarehouse', 'materialoutwarehouse.materialInWarehouseID = materialinwarehouse.storedMaterialID');
        $this->db->join('material', 'materialinwarehouse.material = material.materialID');
        $this->db->join('supplier', 'materialinwarehouse.supplier = supplier.supplierID');
        $this->db->join('packaging', 'materialinwarehouse.packagingID = packaging.packagingID');
        $this->db->join('materialusage', 'materialoutwarehouse.outWarehouseDepartment = materialusage.materialUsageID');
        $result = $this->db->get();

        return $result;
    }

    public function deleteMaterialEntryData($materialEntryData)
    {
        $this->db->where('materialEntryID', $materialEntryData['materialEntryID']);
        $result = $this->db->delete('materialentry');

        return $result;
    }
}