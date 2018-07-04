<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finishedgoodrequisitionmodel extends CI_Model {

    public function insertFinishedGoodRequisitionData($finishedGoodRequisitionData)
    {
        $result = $this->db->insert('finishedgoodrequisition', $finishedGoodRequisitionData);

        return $result;
    }

    public function queryFinishedGoodRequisitionData()
    {
        $result = $this->db->get('finishedgoodrequisition');

        return $result;
    }

    public function queryFinishedGoodRequisitionIDData()
    {
        $this->db->select('finishedGoodRequisitionID');
        $this->db->where('notOutPackageNumber >', 0);
        $result = $this->db->get('finishedgoodrequisition');

        return $result;
    }

    public function queryFinishedGoodRequisitionByRequisitionIDData($requisitionID)
    {
        $this->db->select('
            finishedgoodrequisition.finishedGoodRequisitionID,
            finishedgoodrequisition.product,
            finishedgood.finishedGoodType,
            finishedgoodrequisition.packagingID,
            finishedgoodpackaging.packaging,
            finishedgoodpackaging.unitWeight,
            finishedgoodpackaging.packageNumberOfPallet,
            finishedgoodrequisition.requisitionedPackageNumber,
            finishedgoodrequisition.notOutPackageNumber');
        $this->db->from('finishedgoodrequisition');
        $this->db->join('finishedgood', 'finishedgoodrequisition.product = finishedgood.finishedGoodID');
        $this->db->join('finishedgoodpackaging', 'finishedgoodrequisition.packagingID = finishedgoodpackaging.finishedGoodPackagingID');
        $this->db->where('finishedgoodrequisition.finishedGoodRequisitionID', $requisitionID);
        $result = $this->db->get();

        return $result;
    }

    public function updateNotOutPackageNumberData($requisitionID, $packageNumber)
    {
        $this->db->set('notOutPackageNumber', 'notOutPackageNumber + ' . $packageNumber, FALSE);
        $this->db->where('finishedGoodRequisitionID', $requisitionID);
        $result = $this->db->update('finishedgoodrequisition');
    }

    public function deleteFinishedGoodRequisitionData($finishedGoodEntryData)
    {
        $this->db->where('finishedGoodRequisitionID', $finishedGoodEntryData['finishedGoodRequisitionID']);
        $result = $this->db->delete('finishedgoodrequisition');

        return $result;
    }
}
