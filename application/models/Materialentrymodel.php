<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materialentrymodel extends CI_Model {

    public function insertMaterialEntryData($materialEntryData)
    {
        $result = $this->db->insert('materialentry', $materialEntryData);

        return $result;
    }

    public function queryMaterialEntryData($isConfirmed, $materialEntryID)
    {
        $this->db->select('
            materialentry.materialEntryID,
            materialentry.serialNumber,
            materialentry.purchaseOrder,
            materialentry.expectedStoredArea,
            purchaseorder.material,
            material.materialName,
            purchaseorder.purchaseCondition,
            materialentry.expectedStoredDate,
            supplier.supplierName,
            packaging.packaging,
            packaging.unitWeight,
            materialentry.packageNumberOfPallet,
            materialentry.palletNumber,
            materialentry.expectedStoredPackageNumber,
            materialentry.expectedStoredWeight,
            materialusage.usingDepartment,
            supplier.unitPrice,
            materialentry.expectedStoredMoney,
            materialentry.confirmation');
        $this->db->from('materialentry');
        $this->db->join('purchaseorder', 'materialentry.purchaseOrder = purchaseorder.purchaseOrderID');
        $this->db->join('material', 'purchaseorder.material = material.materialID');
        $this->db->join('supplier', 'purchaseorder.supplier = supplier.supplierID');
        $this->db->join('packaging', 'purchaseorder.packaging = packaging.packagingID');
        $this->db->join('materialusage', 'purchaseorder.material = materialusage.material');
        if ("0" != $materialEntryID) {
            $this->db->where('materialentry.materialEntryID', $materialEntryID);
        }
        $this->db->where('materialentry.confirmation', $isConfirmed);
        $this->db->order_by('materialentry.materialEntryID', 'ASC');
        $result = $this->db->get();

        return $result;
    }

    // To update totalPackageNumber, totalWeight and totalMoney by material ID
    public function queryMaterialEntryDataToUpdateMaterial($materialEntryID)
    {
        $this->db->select('
            purchaseorder.material,
            materialentry.materialEntryID,
            purchaseorder.supplier,
            packaging.packagingID,
            materialentry.expectedStoredArea,
            materialentry.expectedStoredPackageNumber,
            materialentry.expectedStoredWeight,
            materialentry.expectedStoredMoney');
        $this->db->from('materialentry');
        $this->db->join('purchaseorder', 'materialentry.purchaseOrder = purchaseorder.purchaseOrderID');
        $this->db->join('packaging', 'purchaseorder.packaging = packaging.packagingID');
        $this->db->where('materialentry.materialEntryID', $materialEntryID);
        $result = $this->db->get();

        return $result->row_array();
    }

    public function deleteMaterialEntryData($materialEntryData)
    {
        $this->db->where('materialEntryID', $materialEntryData['materialEntryID']);
        $result = $this->db->delete('materialentry');

        return $result;
    }

    public function updateMaterialEntryConfirmationData($materialEntryID)
    {
        $this->db->set('confirmation', 1, FALSE);
        $this->db->where('materialEntryID', $materialEntryID);
        $this->db->update('materialentry');
    }

    public function updateMaterialEntryPackageNumberData(
        $materialEntryID,
        $storedArea,
        $packageNumberOfPallet,
        $palletNumber,
        $storedPackageNumber,
        $storedWeight,
        $storedMoney
    )
    {
        if (null != $storedArea) {
            $this->db->set('expectedStoredArea', $storedArea);
        }
        $this->db->set('packageNumberOfPallet', 'packageNumberOfPallet + ' . $packageNumberOfPallet, FALSE);
        $this->db->set('palletNumber', 'palletNumber + ' . $palletNumber, FALSE);
        $this->db->set('expectedStoredPackageNumber', 'expectedStoredPackageNumber + ' . $storedPackageNumber, FALSE);
        $this->db->set('expectedStoredWeight', 'expectedStoredWeight + ' . $storedWeight, FALSE);
        $this->db->set('expectedStoredMoney', 'expectedStoredMoney + ' . $storedMoney, FALSE);
        $this->db->where('materialEntryID', $materialEntryID);
        $this->db->update('materialentry');
    }
}