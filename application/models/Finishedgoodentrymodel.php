<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finishedgoodentrymodel extends CI_Model {

    public function insertFinishedGoodEntryData($finishedGoodEntryData)
    {
        $result = $this->db->insert('finishedgoodentry', $finishedGoodEntryData);

        return $result;
    }

    public function queryFinishedGoodEntryData($isConfirmed, $finishedGoodEntryID)
    {
        $this->db->select('
            finishedgoodentry.finishedGoodEntryID,
            finishedgoodentry.serialNumber,
            finishedgoodentry.product,
            finishedgood.finishedGoodType,
            finishedgoodpackaging.packaging,
            finishedgoodpackaging.unitWeight,
            finishedgoodpackaging.packageNumberOfPallet,
            finishedgoodentry.status,
            finishedgoodentry.expectedStoredArea,
            finishedgoodentry.expectedStoredDate,
            finishedgoodentry.palletNumber,
            finishedgoodentry.expectedStoredPackageNumber,
            finishedgoodentry.expectedStoredWeight,
            finishedgoodentry.notEnteredPalletNumber,
            finishedgoodentry.notEnteredPackageNumber');
        $this->db->from('finishedgoodentry');
        $this->db->join('finishedgood', 'finishedgoodentry.product = finishedgood.finishedGoodID');
        $this->db->join('finishedgoodpackaging', 'finishedgoodentry.packaging = finishedgoodpackaging.finishedGoodPackagingID');
        if ("0" != $finishedGoodEntryID) {
            $this->db->where('finishedgoodentry.finishedGoodEntryID', $finishedGoodEntryID);
        }

        if ("0" == $isConfirmed) {
            $this->db->where('finishedgoodentry.notEnteredPalletNumber >', 0);
            $this->db->where('finishedgoodentry.notEnteredPackageNumber >', 0);
        }
        $this->db->order_by('finishedgoodentry.finishedGoodEntryID', 'ASC');
        $result = $this->db->get();

        return $result;
    }

    public function queryProductPackagingUnitWeightByFinishedGoodEntryIDData($finishedGoodEntryID)
    {
        $this->db->select('
            finishedgoodentry.product,
            finishedgoodentry.packaging,
            finishedgoodpackaging.unitWeight');
        $this->db->from('finishedgoodentry');
        $this->db->join('finishedgoodpackaging', 'finishedgoodentry.packaging = finishedgoodpackaging.finishedGoodPackagingID');
        $this->db->where('finishedgoodentry.finishedGoodEntryID', $finishedGoodEntryID);
        $result = $this->db->get();

        return $result->row_array();
    }

    public function deleteFinishedGoodEntryData($finishedGoodEntryData)
    {
        $this->db->where('finishedGoodEntryID', $finishedGoodEntryData['finishedGoodEntryID']);
        $result = $this->db->delete('finishedgoodentry');

        return $result;
    }

    public function updateFinishedGoodEntryNotEnteredData($finishedGoodEntryID, $storedPalletNumber, $storedPackageNumber)
    {
        $this->db->set('notEnteredPalletNumber', 'notEnteredPalletNumber + ' . $storedPalletNumber, FALSE);
        $this->db->set('notEnteredPackageNumber', 'notEnteredPackageNumber + ' . $storedPackageNumber, FALSE);
        $this->db->where('finishedGoodEntryID', $finishedGoodEntryID);
        $this->db->update('finishedgoodentry');
    }
}