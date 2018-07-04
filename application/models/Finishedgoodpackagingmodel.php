<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finishedgoodpackagingmodel extends CI_Model {

    public function insertFinishedGoodPackagingData($finishedGoodPackagingData)
    {
        $result = $this->db->insert('finishedgoodpackaging', $finishedGoodPackagingData);

        return $result;
    }

    public function queryFinishedGoodPackagingData()
    {
        $result = $this->db->get('finishedgoodpackaging');

        return $result;
    }

    public function deleteFinishedGoodPackagingData($packagingData)
    {
        $this->db->where('packagingID', $packagingData['packagingID']);
        $result = $this->db->delete('packaging');

        return $result;
    }

    public function queryFinishedGoodPackagingbyProductIDData($productID)
    {
        $this->db->select('
            finishedgoodpackaging.finishedGoodPackagingID,
            finishedgoodpackaging.product,
            finishedgood.finishedGoodType,
            finishedgoodpackaging.packaging,
            finishedgoodpackaging.unitWeight,
            finishedgoodpackaging.packageNumberOfPallet');
        $this->db->from('finishedgoodpackaging');
        $this->db->join('finishedgood', 'finishedgoodpackaging.product = finishedgood.finishedGoodID');
        $this->db->where('finishedgoodpackaging.product', $productID);
        $result = $this->db->get();

        return $result;
    }

    public function queryFinishedGoodPackagingbyPackagingIDData($finishedGoodPackagingID)
    {
        $this->db->select('
            finishedgoodpackaging.product,
            finishedgood.finishedGoodType,
            finishedgoodpackaging.packaging,
            finishedgoodpackaging.unitWeight,
            finishedgoodpackaging.packageNumberOfPallet');
        $this->db->from('finishedgoodpackaging');
        $this->db->join('finishedgood', 'finishedgoodpackaging.product = finishedgood.finishedGoodID');
        $this->db->where('finishedgoodpackaging.finishedGoodPackagingID', $finishedGoodPackagingID);
        $result = $this->db->get();

        return $result->row_array();
    }

    public function queryUnitWeightPackageNumberOfPalletByFinishedGoodPackagingID($finishedGoodPackagingID)
    {
        $this->db->select('unitWeight, packageNumberOfPallet');
        $this->db->from('finishedgoodpackaging');
        $this->db->where('finishedGoodPackagingID', $finishedGoodPackagingID);
        $result = $this->db->get();

        return $result->row_array();
    }

    public function queryFinishedGoodPackagingUnitWeightByPackagingID($packagingID)
    {
        $this->db->select('unitWeight');
        $this->db->from('packaging');
        $this->db->where('packagingID', $packagingID);
        $result = $this->db->get();

        return $result->row_array();
    }

    public function queryFinishedGoodPackagingByPackagingID($packagingID)
    {
        $this->db->select('packaging');
        $this->db->from('packaging');
        $this->db->where('packagingID', $packagingID);
        $result = $this->db->get();

        return $result->row_array();
    }
}