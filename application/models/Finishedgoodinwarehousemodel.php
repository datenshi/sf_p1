<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finishedgoodinwarehousemodel extends CI_Model {

    public function insertFinishedGoodInWarehouseData($finishedGoodEntryData)
    {
        $result = $this->db->insert('finishedgoodinwarehouse', $finishedGoodEntryData);

        return $result;
    }

    public function queryFinishedGoodInWarehouseData()
    {
        $this->db->select('
            finishedgoodinwarehouse.finishedGoodEntry,
            finishedgoodinwarehouse.product,
            finishedgood.finishedGoodType,
            finishedgoodinwarehouse.packagingID,
            finishedgoodpackaging.unitWeight,
            finishedgoodpackaging.packageNumberOfPallet,
            finishedgoodinwarehouse.storedArea,
            finishedgoodinwarehouse.storedDate,
            finishedgoodinwarehouse.storedPalletNumber,
            finishedgoodinwarehouse.storedPackageNumber,
            finishedgoodinwarehouse.storedWeight');
        $this->db->from('finishedgoodinwarehouse');
        $this->db->join('finishedgood', 'finishedgoodinwarehouse.product = finishedgood.finishedGoodID');
        $this->db->join('finishedgoodpackaging', 'finishedgoodinwarehouse.packagingID = finishedgoodpackaging.finishedGoodPackagingID');
        $result = $this->db->get();

        return $result;
    }

    public function queryProductInWarehouseData()
    {
        $this->db->select('
            finishedgoodinwarehouse.product,
            finishedgood.finishedGoodType');
        $this->db->distinct();
        $this->db->from('finishedgoodinwarehouse');
        $this->db->join('finishedgood', 'finishedgoodinwarehouse.product = finishedgood.finishedGoodID');
        $result = $this->db->get();

        return $result;
    }

    public function queryPackagingInWarehouseByProductIDData($product)
    {
        $this->db->select('
            finishedgoodinwarehouse.packagingID,
            finishedgoodpackaging.packaging,
            finishedgoodpackaging.unitWeight,
            finishedgoodpackaging.packageNumberOfPallet');
        $this->db->distinct();
        $this->db->from('finishedgoodinwarehouse');
        $this->db->join('finishedgoodpackaging', 'finishedgoodinwarehouse.packagingID = finishedgoodpackaging.finishedGoodPackagingID');
        $this->db->where('finishedgoodinwarehouse.product', $product);
        $result = $this->db->get();

        return $result;
    }

    public function queryPackagNumberInWarehouseByProductPackagingIDData($product, $packaging)
    {
        $this->db->select_sum('remainingPackageNumber');
        $this->db->from('finishedgoodinwarehouse');
        $this->db->where('product', $product);
        $this->db->where('packagingID', $packaging);
        $result = $this->db->get();

        return $result;
    }

    public function queryAreaInWarehouseByProductPackagingIDData($product, $packaging)
    {
        $this->db->select('storedArea');
        $this->db->from('finishedgoodinwarehouse');
        $this->db->where('product', $product);
        $this->db->where('packagingID', $packaging);
        $this->db->where('remainingPackageNumber >', 0);
        $this->db->order_by('storedArea', 'ASC');
        $result = $this->db->get();

        return $result;
    }

    public function queryFinishedGoodInWarehouseByProductPackagingIDAreaData($product, $packaging, $area)
    {
        $this->db->select('
            finishedgoodinwarehouse.storedFinishedGoodID,
            finishedgoodinwarehouse.finishedGoodEntry,
            finishedgoodinwarehouse.product,
            finishedgood.finishedGoodType,
            finishedgoodpackaging.packaging,
            finishedgoodpackaging.unitWeight,
            finishedgoodpackaging.packageNumberOfPallet,
            finishedgoodinwarehouse.storedArea,
            finishedgoodinwarehouse.storedDate,
            finishedgoodinwarehouse.storedPalletNumber,
            finishedgoodinwarehouse.storedPackageNumber,
            finishedgoodinwarehouse.storedWeight,
            finishedgoodinwarehouse.remainingPackageNumber');
        $this->db->from('finishedgoodinwarehouse');
        $this->db->join('finishedgood', 'finishedgoodinwarehouse.product = finishedgood.finishedGoodID');
        $this->db->join('finishedgoodpackaging', 'finishedgoodinwarehouse.packagingID = finishedgoodpackaging.finishedGoodPackagingID');
        $this->db->where('finishedgoodinwarehouse.product', $product);
        $this->db->where('finishedgoodinwarehouse.packagingID', $packaging);
        $this->db->where('finishedgoodinwarehouse.storedArea', $area);
        $this->db->where('remainingPackageNumber >', 0);
        $result = $this->db->get();

        return $result;
    }

    public function updateRemainingPackageNumberData($storedFinishedGoodID, $packageNumber)
    {
        $this->db->set('remainingPackageNumber', 'remainingPackageNumber + ' . $packageNumber, FALSE);
        $this->db->where('storedFinishedGoodID', $storedFinishedGoodID);
        $result = $this->db->update('finishedgoodinwarehouse');
    }
}