<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finishedgoodoutwarehousemodel extends CI_Model {

    public function insertFinishedGoodOutWarehouseData($finishedGoodOutWarehouseData)
    {
        $result = $this->db->insert('finishedgoodoutwarehouse', $finishedGoodOutWarehouseData);

        return $result;
    }

    public function queryFinishedGoodOutWarehouseData()
    {
        $this->db->select('
            finishedgoodinwarehouse.product,
            finishedgood.finishedGoodType,
            finishedgoodpackaging.packaging,
            finishedgoodpackaging.unitWeight,
            finishedgoodpackaging.packageNumberOfPallet,
            finishedgoodoutwarehouse.takenOutArea,
            finishedgoodoutwarehouse.takenOutDate,
            finishedgoodoutwarehouse.takingOutDepartment,
            finishedgoodoutwarehouse.takingOutMember,
            finishedgoodoutwarehouse.takenOutPackageNumber');
        $this->db->from('finishedgoodoutwarehouse');
        $this->db->join('finishedgoodinwarehouse', 'finishedgoodoutwarehouse.inWarehouseID = finishedgoodinwarehouse.storedFinishedGoodID');
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
}