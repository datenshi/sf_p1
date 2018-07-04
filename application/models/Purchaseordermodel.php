<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchaseordermodel extends CI_Model {

    public function insertPurchaseOrderData($purchaseOrderData)
    {
        $result = $this->db->insert('purchaseorder', $purchaseOrderData);

        return $result;
    }

    public function queryPurchaseOrderData($purchaseOrderID)
    {
        $this->db->select('
            purchaseorder.purchaseOrderID,
            material.materialName,
            supplier.supplierName,
            supplier.unitPrice,
            packaging.packaging,
            packaging.unitWeight,
            purchaseorder.purchaseCondition,
            purchaseorder.purchasedPackageNumber,
            purchaseorder.notEnteredPackageNumber');
        $this->db->from('purchaseorder');
        $this->db->join('material', 'purchaseorder.material = material.materialID');
        $this->db->join('supplier', 'purchaseorder.supplier = supplier.supplierID');
        $this->db->join('packaging', 'purchaseorder.packaging = packaging.packagingID');
        if (false != $purchaseOrderID) {
            $this->db->where('purchaseOrderID', $purchaseOrderID);
            $this->db->where('notEnteredPackageNumber >', 0);
        }
        $result = $this->db->get();

        return $result;
    }

    public function queryPurchaseOrderForUnitWeightUnitPrice($purchaseOrderID)
    {
        $this->db->select('
            purchaseorder.material,
            packaging.unitWeight,
            supplier.unitPrice');
        $this->db->from('purchaseorder');
        $this->db->join('supplier', 'purchaseorder.supplier = supplier.supplierID');
        $this->db->join('packaging', 'purchaseorder.packaging = packaging.packagingID');
        $this->db->where('purchaseOrderID', $purchaseOrderID);
        $result = $this->db->get();

        return $result->row_array();
    }

    public function queryPurchaseOrderSpecificColumn($queryData, $isOneRow)
    {
        $result = $this->db->query($queryData);

        if (true == $isOneRow) {
            return $result->row_array();
        }
        else {
            return $result;
        }
    }

    public function deletePurchaseOrderData($purchaseOrderData)
    {
        $this->db->where('purchaseOrderID', $purchaseOrderData['purchaseOrderID']);
        $result = $this->db->delete('purchaseorder');

        return $result;
    }

    public function updatePurchaseOrderQuantityData($purchaseOrder, $storedPackageNumber)
    {
        $this->db->set('notEnteredPackageNumber', 'notEnteredPackageNumber + ' . $storedPackageNumber, FALSE);
        $this->db->where('purchaseOrderID', $purchaseOrder);
        $this->db->update('purchaseorder');
    }
}
