-- --------------------------------------------------------
-- 主機:                           localhost
-- 伺服器版本:                        10.2.13-MariaDB-log - mariadb.org binary distribution
-- 伺服器操作系統:                      Win64
-- HeidiSQL 版本:                  9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- 傾印 sf 的資料庫結構
CREATE DATABASE IF NOT EXISTS `sf` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `sf`;

-- 傾印  表格 sf.finishedgood 結構
CREATE TABLE IF NOT EXISTS `finishedgood` (
  `finishedGoodID` varchar(256) NOT NULL,
  `finishedGoodType` varchar(256) NOT NULL,
  PRIMARY KEY (`finishedGoodID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.finishedgood 的資料：~8 rows (大約)
/*!40000 ALTER TABLE `finishedgood` DISABLE KEYS */;
REPLACE INTO `finishedgood` (`finishedGoodID`, `finishedGoodType`) VALUES
	('DF-920T', '呋喃樹脂'),
	('SIY-28', '潤滑劑'),
	('SIY-28L', '潤滑劑'),
	('SIY-28S', '石墨棒'),
	('SPC-47', '木模用離型劑'),
	('SW-1005S', '鑄鐵塗模劑'),
	('TG-6020S', '呋喃樹脂'),
	('TG-7010', '綠色呋喃樹脂');
/*!40000 ALTER TABLE `finishedgood` ENABLE KEYS */;

-- 傾印  表格 sf.finishedgoodentry 結構
CREATE TABLE IF NOT EXISTS `finishedgoodentry` (
  `finishedGoodEntryID` varchar(256) NOT NULL,
  `serialNumber` varchar(256) NOT NULL,
  `product` varchar(256) NOT NULL,
  `packaging` int(16) unsigned NOT NULL,
  `status` varchar(256) NOT NULL,
  `expectedStoredArea` varchar(8) NOT NULL,
  `expectedStoredDate` date NOT NULL,
  `palletNumber` int(8) unsigned NOT NULL DEFAULT 0,
  `expectedStoredPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  `expectedStoredWeight` int(16) unsigned NOT NULL DEFAULT 0,
  `notEnteredPalletNumber` int(8) unsigned NOT NULL DEFAULT 0,
  `notEnteredPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`finishedGoodEntryID`),
  KEY `entryFinishedGood` (`product`),
  KEY `finishedGoodEntryPackagingID` (`packaging`),
  CONSTRAINT `finishedGoodEntryPackagingID` FOREIGN KEY (`packaging`) REFERENCES `finishedgoodpackaging` (`finishedGoodPackagingID`),
  CONSTRAINT `finishedGoodEntryProductID` FOREIGN KEY (`product`) REFERENCES `finishedgood` (`finishedGoodID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.finishedgoodentry 的資料：~4 rows (大約)
/*!40000 ALTER TABLE `finishedgoodentry` DISABLE KEYS */;
REPLACE INTO `finishedgoodentry` (`finishedGoodEntryID`, `serialNumber`, `product`, `packaging`, `status`, `expectedStoredArea`, `expectedStoredDate`, `palletNumber`, `expectedStoredPackageNumber`, `expectedStoredWeight`, `notEnteredPalletNumber`, `notEnteredPackageNumber`) VALUES
	('PE001', '20180620001', 'DF-920T', 2, '正常', 'A', '2018-06-20', 50, 50, 50000, 0, 0),
	('PE002', '20180620002', 'SIY-28L', 8, '正常', 'E', '2018-06-20', 10, 300, 4500, 10, 300),
	('PE003', '20180620003', 'SIY-28S', 7, '急貨', 'D', '2018-06-20', 12, 288, 7200, 12, 288),
	('PE004', '20180620004', 'SIY-28', 4, '正常', 'B', '2018-06-20', 50, 200, 30000, 40, 160);
/*!40000 ALTER TABLE `finishedgoodentry` ENABLE KEYS */;

-- 傾印  表格 sf.finishedgoodinwarehouse 結構
CREATE TABLE IF NOT EXISTS `finishedgoodinwarehouse` (
  `storedFinishedGoodID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `finishedGoodEntry` varchar(256) NOT NULL,
  `product` varchar(256) NOT NULL,
  `packagingID` int(16) unsigned NOT NULL,
  `storedArea` varchar(8) NOT NULL,
  `storedDate` datetime NOT NULL,
  `storedPalletNumber` int(8) unsigned NOT NULL DEFAULT 0,
  `storedPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  `storedWeight` int(16) unsigned NOT NULL DEFAULT 0,
  `remainingPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`storedFinishedGoodID`),
  KEY `storedFinishedGoodEntryID` (`finishedGoodEntry`),
  KEY `storedFinishedGoodID` (`product`),
  KEY `storedFinishedGoodPackagingID` (`packagingID`),
  CONSTRAINT `storedFinishedGoodEntryID` FOREIGN KEY (`finishedGoodEntry`) REFERENCES `finishedgoodentry` (`finishedGoodEntryID`),
  CONSTRAINT `storedFinishedGoodID` FOREIGN KEY (`product`) REFERENCES `finishedgood` (`finishedGoodID`),
  CONSTRAINT `storedFinishedGoodPackagingID` FOREIGN KEY (`packagingID`) REFERENCES `finishedgoodpackaging` (`finishedGoodPackagingID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.finishedgoodinwarehouse 的資料：~2 rows (大約)
/*!40000 ALTER TABLE `finishedgoodinwarehouse` DISABLE KEYS */;
REPLACE INTO `finishedgoodinwarehouse` (`storedFinishedGoodID`, `finishedGoodEntry`, `product`, `packagingID`, `storedArea`, `storedDate`, `storedPalletNumber`, `storedPackageNumber`, `storedWeight`, `remainingPackageNumber`) VALUES
	(1, 'PE001', 'DF-920T', 2, 'B', '2018-06-21 11:52:53', 30, 30, 30000, 10),
	(2, 'PE001', 'DF-920T', 2, 'A', '2018-06-21 11:52:55', 20, 20, 20000, 0),
	(3, 'PE004', 'SIY-28', 4, 'E', '2018-06-21 12:56:20', 10, 40, 6000, 40);
/*!40000 ALTER TABLE `finishedgoodinwarehouse` ENABLE KEYS */;

-- 傾印  表格 sf.finishedgoodoutwarehouse 結構
CREATE TABLE IF NOT EXISTS `finishedgoodoutwarehouse` (
  `takenOutFinishedGoodID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `inWarehouseID` int(16) unsigned NOT NULL,
  `finishedGoodRequisition` varchar(256) NOT NULL,
  `takenOutArea` varchar(8) NOT NULL,
  `takenOutDate` datetime NOT NULL,
  `takingOutDepartment` varchar(256) NOT NULL,
  `takingOutMember` varchar(256) NOT NULL,
  `takenOutPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`takenOutFinishedGoodID`),
  KEY `outFinishedGoodRequisitionID` (`finishedGoodRequisition`),
  KEY `outFinishedGoodInWarehouseID` (`inWarehouseID`),
  CONSTRAINT `outFinishedGoodInWarehouseID` FOREIGN KEY (`inWarehouseID`) REFERENCES `finishedgoodinwarehouse` (`storedFinishedGoodID`),
  CONSTRAINT `outFinishedGoodRequisitionID` FOREIGN KEY (`finishedGoodRequisition`) REFERENCES `finishedgoodrequisition` (`finishedGoodRequisitionID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.finishedgoodoutwarehouse 的資料：~2 rows (大約)
/*!40000 ALTER TABLE `finishedgoodoutwarehouse` DISABLE KEYS */;
REPLACE INTO `finishedgoodoutwarehouse` (`takenOutFinishedGoodID`, `inWarehouseID`, `finishedGoodRequisition`, `takenOutArea`, `takenOutDate`, `takingOutDepartment`, `takingOutMember`, `takenOutPackageNumber`) VALUES
	(1, 2, 'PR001', 'A', '2018-06-22 10:19:27', '二課', '乙員', 20),
	(2, 1, 'PR001', 'B', '2018-06-22 10:20:09', '二課', '乙員', 20);
/*!40000 ALTER TABLE `finishedgoodoutwarehouse` ENABLE KEYS */;

-- 傾印  表格 sf.finishedgoodpackaging 結構
CREATE TABLE IF NOT EXISTS `finishedgoodpackaging` (
  `finishedGoodPackagingID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `product` varchar(256) NOT NULL,
  `packaging` varchar(256) NOT NULL,
  `unitWeight` int(16) unsigned NOT NULL,
  `packageNumberOfPallet` int(8) unsigned NOT NULL,
  PRIMARY KEY (`finishedGoodPackagingID`),
  KEY `finishedGoodPackagingProductID` (`product`),
  CONSTRAINT `finishedGoodPackagingProductID` FOREIGN KEY (`product`) REFERENCES `finishedgood` (`finishedGoodID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.finishedgoodpackaging 的資料：~9 rows (大約)
/*!40000 ALTER TABLE `finishedgoodpackaging` DISABLE KEYS */;
REPLACE INTO `finishedgoodpackaging` (`finishedGoodPackagingID`, `product`, `packaging`, `unitWeight`, `packageNumberOfPallet`) VALUES
	(1, 'DF-920T', '小桶', 230, 4),
	(2, 'DF-920T', '噸桶', 1000, 1),
	(3, 'SIY-28', '大桶', 15, 30),
	(4, 'SIY-28', '大桶', 150, 4),
	(5, 'SIY-28', '小桶', 15, 24),
	(6, 'SIY-28S', '小桶', 25, 30),
	(7, 'SIY-28S', '小桶', 25, 24),
	(8, 'SIY-28L', '小桶', 15, 30),
	(9, 'SIY-28L', '小桶', 15, 24);
/*!40000 ALTER TABLE `finishedgoodpackaging` ENABLE KEYS */;

-- 傾印  表格 sf.finishedgoodrequisition 結構
CREATE TABLE IF NOT EXISTS `finishedgoodrequisition` (
  `finishedGoodRequisitionID` varchar(256) NOT NULL,
  `product` varchar(256) NOT NULL,
  `packagingID` int(16) unsigned NOT NULL,
  `requisitioningDate` date NOT NULL,
  `requisitioningDepartment` varchar(256) NOT NULL,
  `requisitioningMember` varchar(256) NOT NULL,
  `requisitionedPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  `notOutPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`finishedGoodRequisitionID`),
  KEY `requisitionProduct` (`product`),
  KEY `requisitionPackaging` (`packagingID`),
  CONSTRAINT `requisitionPackaging` FOREIGN KEY (`packagingID`) REFERENCES `finishedgoodpackaging` (`finishedGoodPackagingID`),
  CONSTRAINT `requisitionProduct` FOREIGN KEY (`product`) REFERENCES `finishedgood` (`finishedGoodID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.finishedgoodrequisition 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `finishedgoodrequisition` DISABLE KEYS */;
REPLACE INTO `finishedgoodrequisition` (`finishedGoodRequisitionID`, `product`, `packagingID`, `requisitioningDate`, `requisitioningDepartment`, `requisitioningMember`, `requisitionedPackageNumber`, `notOutPackageNumber`) VALUES
	('PR001', 'DF-920T', 2, '2018-06-21', '二課', '甲員', 40, 0);
/*!40000 ALTER TABLE `finishedgoodrequisition` ENABLE KEYS */;

-- 傾印  表格 sf.material 結構
CREATE TABLE IF NOT EXISTS `material` (
  `materialID` varchar(256) NOT NULL,
  `materialName` varchar(256) NOT NULL,
  `totalPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  `totalWeight` int(16) unsigned NOT NULL DEFAULT 0,
  `totalMoney` int(16) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`materialID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.material 的資料：~6 rows (大約)
/*!40000 ALTER TABLE `material` DISABLE KEYS */;
REPLACE INTO `material` (`materialID`, `materialName`, `totalPackageNumber`, `totalWeight`, `totalMoney`) VALUES
	('013A', '精鹽', 30, 750, 3000),
	('113', '尿素膠', 172, 62560, 1000960),
	('217', '鋁粉', 12, 9600, 412800),
	('219A', '二甲苯', 0, 0, 0),
	('435', '土狀黑鉛', 6, 6000, 84000),
	('439A', '氧化鐵', 0, 0, 0);
/*!40000 ALTER TABLE `material` ENABLE KEYS */;

-- 傾印  表格 sf.materialentry 結構
CREATE TABLE IF NOT EXISTS `materialentry` (
  `materialEntryID` varchar(256) NOT NULL,
  `serialNumber` varchar(256) NOT NULL,
  `purchaseOrder` varchar(256) NOT NULL,
  `QRCode` blob DEFAULT NULL,
  `expectedStoredArea` varchar(8) NOT NULL,
  `expectedStoredDate` date NOT NULL,
  `packageNumberOfPallet` int(8) unsigned NOT NULL DEFAULT 0,
  `palletNumber` int(8) unsigned NOT NULL DEFAULT 0,
  `expectedStoredPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  `expectedStoredWeight` int(16) unsigned NOT NULL DEFAULT 0,
  `expectedStoredMoney` int(16) unsigned NOT NULL DEFAULT 0,
  `confirmation` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`materialEntryID`),
  KEY `entryPurchaseOrder` (`purchaseOrder`),
  CONSTRAINT `entryPurchaseOrder` FOREIGN KEY (`purchaseOrder`) REFERENCES `purchaseorder` (`purchaseOrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.materialentry 的資料：~8 rows (大約)
/*!40000 ALTER TABLE `materialentry` DISABLE KEYS */;
REPLACE INTO `materialentry` (`materialEntryID`, `serialNumber`, `purchaseOrder`, `QRCode`, `expectedStoredArea`, `expectedStoredDate`, `packageNumberOfPallet`, `palletNumber`, `expectedStoredPackageNumber`, `expectedStoredWeight`, `expectedStoredMoney`, `confirmation`) VALUES
	('EN001', '20180611001', 'PO001', NULL, 'A', '2018-06-11', 10, 3, 30, 750, 3000, 1),
	('EN002', '20180611002', 'PO002', NULL, 'B', '2018-06-11', 7, 4, 28, 28000, 448000, 1),
	('EN003', '20180611003', 'PO003', NULL, 'C', '2018-06-11', 6, 2, 12, 9600, 412800, 1),
	('EN004', '20180611004', 'PO004', NULL, 'D', '2018-06-11', 2, 4, 8, 1376, 35776, 0),
	('EN005', '20180611005', 'PO005', NULL, 'C', '2018-06-11', 2, 3, 6, 6000, 84000, 1),
	('EN006', '20180611006', 'PO004', NULL, 'A', '2018-06-11', 6, 2, 12, 2064, 53664, 0),
	('EN007', '20180611007', 'PO007', NULL, 'C', '2018-06-11', 15, 4, 60, 14400, 230400, 1),
	('EN008', '20180612001', 'PO008', NULL, 'D', '2018-06-12', 14, 6, 84, 20160, 322560, 1);
/*!40000 ALTER TABLE `materialentry` ENABLE KEYS */;

-- 傾印  表格 sf.materialinwarehouse 結構
CREATE TABLE IF NOT EXISTS `materialinwarehouse` (
  `storedMaterialID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `material` varchar(256) NOT NULL,
  `materialEntry` varchar(256) NOT NULL,
  `supplier` int(16) unsigned NOT NULL,
  `packagingID` int(16) unsigned NOT NULL,
  `storedArea` varchar(8) NOT NULL,
  `storedDate` datetime NOT NULL,
  `storedPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  `storedWeight` int(16) unsigned NOT NULL DEFAULT 0,
  `storedMoney` int(16) unsigned NOT NULL DEFAULT 0,
  `remainingPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`storedMaterialID`),
  KEY `storedMaterialID` (`material`),
  KEY `storedMaterialPackagingID` (`packagingID`),
  KEY `storedMaterialEntryID` (`materialEntry`),
  KEY `storedSupplierID` (`supplier`),
  CONSTRAINT `storedMaterialEntryID` FOREIGN KEY (`materialEntry`) REFERENCES `materialentry` (`materialEntryID`),
  CONSTRAINT `storedMaterialID` FOREIGN KEY (`material`) REFERENCES `material` (`materialID`),
  CONSTRAINT `storedMaterialPackagingID` FOREIGN KEY (`packagingID`) REFERENCES `packaging` (`packagingID`),
  CONSTRAINT `storedSupplierID` FOREIGN KEY (`supplier`) REFERENCES `supplier` (`supplierID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.materialinwarehouse 的資料：~6 rows (大約)
/*!40000 ALTER TABLE `materialinwarehouse` DISABLE KEYS */;
REPLACE INTO `materialinwarehouse` (`storedMaterialID`, `material`, `materialEntry`, `supplier`, `packagingID`, `storedArea`, `storedDate`, `storedPackageNumber`, `storedWeight`, `storedMoney`, `remainingPackageNumber`) VALUES
	(1, '013A', 'EN001', 1, 1, 'A', '2018-06-11 17:17:52', 30, 750, 3000, 30),
	(2, '113', 'EN007', 11, 7, 'C', '2018-06-11 17:19:39', 60, 14400, 230400, 20),
	(3, '435', 'EN005', 8, 4, 'C', '2018-06-11 17:58:19', 6, 6000, 84000, 6),
	(4, '113', 'EN002', 12, 6, 'B', '2018-06-12 14:38:42', 28, 28000, 448000, 28),
	(5, '113', 'EN008', 12, 7, 'D', '2018-06-12 15:27:06', 84, 20160, 322560, 84),
	(6, '217', 'EN003', 3, 2, 'C', '2018-06-17 16:26:43', 12, 9600, 412800, 12);
/*!40000 ALTER TABLE `materialinwarehouse` ENABLE KEYS */;

-- 傾印  表格 sf.materialoutwarehouse 結構
CREATE TABLE IF NOT EXISTS `materialoutwarehouse` (
  `tookOutMaterialID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `materialInWarehouseID` int(16) unsigned NOT NULL,
  `materialRequisition` varchar(256) NOT NULL,
  `outWarehouseArea` varchar(8) NOT NULL,
  `outWarehouseDate` datetime NOT NULL,
  `outWarehouseDepartment` varchar(256) NOT NULL,
  `outWarehouseMember` varchar(256) NOT NULL,
  `outWarehousePackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`tookOutMaterialID`),
  KEY `outWarehouseMaterialRequisitionID` (`materialRequisition`),
  KEY `outWarehouseMaterialInWarehouseID` (`materialInWarehouseID`),
  CONSTRAINT `outWarehouseMaterialInWarehouseID` FOREIGN KEY (`materialInWarehouseID`) REFERENCES `materialinwarehouse` (`storedMaterialID`),
  CONSTRAINT `outWarehouseMaterialRequisitionID` FOREIGN KEY (`materialRequisition`) REFERENCES `materialrequisition` (`materialRequisitionID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.materialoutwarehouse 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `materialoutwarehouse` DISABLE KEYS */;
REPLACE INTO `materialoutwarehouse` (`tookOutMaterialID`, `materialInWarehouseID`, `materialRequisition`, `outWarehouseArea`, `outWarehouseDate`, `outWarehouseDepartment`, `outWarehouseMember`, `outWarehousePackageNumber`) VALUES
	(1, 2, 'MR001', 'C', '2018-06-22 18:06:33', '2', '甲員', 40);
/*!40000 ALTER TABLE `materialoutwarehouse` ENABLE KEYS */;

-- 傾印  表格 sf.materialrequisition 結構
CREATE TABLE IF NOT EXISTS `materialrequisition` (
  `materialRequisitionID` varchar(256) NOT NULL,
  `material` varchar(256) NOT NULL,
  `supplier` int(16) unsigned NOT NULL,
  `packaging` int(16) unsigned NOT NULL,
  `requisitioningDate` date NOT NULL,
  `requisitioningDepartment` varchar(256) NOT NULL,
  `requisitioningMember` varchar(256) NOT NULL,
  `requisitionedPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  `notOutPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`materialRequisitionID`),
  KEY `requisitionMaterial` (`material`),
  KEY `requisitionSupplierID` (`supplier`),
  KEY `requisitionPackagingID` (`packaging`),
  CONSTRAINT `requisitionMaterial` FOREIGN KEY (`material`) REFERENCES `material` (`materialID`),
  CONSTRAINT `requisitionPackagingID` FOREIGN KEY (`packaging`) REFERENCES `packaging` (`packagingID`),
  CONSTRAINT `requisitionSupplierID` FOREIGN KEY (`supplier`) REFERENCES `supplier` (`supplierID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.materialrequisition 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `materialrequisition` DISABLE KEYS */;
REPLACE INTO `materialrequisition` (`materialRequisitionID`, `material`, `supplier`, `packaging`, `requisitioningDate`, `requisitioningDepartment`, `requisitioningMember`, `requisitionedPackageNumber`, `notOutPackageNumber`) VALUES
	('MR001', '113', 11, 7, '2018-06-22', '2', '甲員', 40, 0);
/*!40000 ALTER TABLE `materialrequisition` ENABLE KEYS */;

-- 傾印  表格 sf.materialusage 結構
CREATE TABLE IF NOT EXISTS `materialusage` (
  `materialUsageID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `material` varchar(256) NOT NULL,
  `usingDepartment` varchar(256) NOT NULL,
  PRIMARY KEY (`materialUsageID`),
  KEY `materialUsageMaterialID` (`material`),
  CONSTRAINT `materialUsageMaterialID` FOREIGN KEY (`material`) REFERENCES `material` (`materialID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.materialusage 的資料：~7 rows (大約)
/*!40000 ALTER TABLE `materialusage` DISABLE KEYS */;
REPLACE INTO `materialusage` (`materialUsageID`, `material`, `usingDepartment`) VALUES
	(1, '013A', '混合組'),
	(2, '113', '樹脂區'),
	(3, '217', '混合組'),
	(4, '219A', '塗模劑'),
	(5, '435', '塗模劑'),
	(6, '439A', '混合組'),
	(7, '439A', '塗模劑');
/*!40000 ALTER TABLE `materialusage` ENABLE KEYS */;

-- 傾印  表格 sf.packaging 結構
CREATE TABLE IF NOT EXISTS `packaging` (
  `packagingID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `material` varchar(256) NOT NULL,
  `packaging` varchar(256) NOT NULL,
  `unitWeight` int(16) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`packagingID`),
  KEY `packagingMaterialID` (`material`),
  CONSTRAINT `packagingMaterialID` FOREIGN KEY (`material`) REFERENCES `material` (`materialID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.packaging 的資料：~7 rows (大約)
/*!40000 ALTER TABLE `packaging` DISABLE KEYS */;
REPLACE INTO `packaging` (`packagingID`, `material`, `packaging`, `unitWeight`) VALUES
	(1, '013A', '包', 25),
	(2, '217', '噸袋', 800),
	(3, '219A', '桶', 172),
	(4, '435', '噸袋', 1000),
	(5, '439A', '噸袋', 850),
	(6, '113', '噸桶', 1000),
	(7, '113', '桶', 240);
/*!40000 ALTER TABLE `packaging` ENABLE KEYS */;

-- 傾印  表格 sf.purchaseorder 結構
CREATE TABLE IF NOT EXISTS `purchaseorder` (
  `purchaseOrderID` varchar(256) NOT NULL,
  `material` varchar(256) NOT NULL,
  `supplier` int(16) unsigned NOT NULL,
  `packaging` int(16) unsigned NOT NULL,
  `purchaseCondition` varchar(256) NOT NULL,
  `purchasedPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  `notEnteredPackageNumber` int(8) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`purchaseOrderID`),
  KEY `purchaseOrderMaterialID` (`material`),
  KEY `purchaseOrderSupplierID` (`supplier`),
  KEY `purchaseOrderPackagingID` (`packaging`),
  CONSTRAINT `purchaseOrderMaterialID` FOREIGN KEY (`material`) REFERENCES `material` (`materialID`),
  CONSTRAINT `purchaseOrderPackagingID` FOREIGN KEY (`packaging`) REFERENCES `packaging` (`packagingID`),
  CONSTRAINT `purchaseOrderSupplierID` FOREIGN KEY (`supplier`) REFERENCES `supplier` (`supplierID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.purchaseorder 的資料：~8 rows (大約)
/*!40000 ALTER TABLE `purchaseorder` DISABLE KEYS */;
REPLACE INTO `purchaseorder` (`purchaseOrderID`, `material`, `supplier`, `packaging`, `purchaseCondition`, `purchasedPackageNumber`, `notEnteredPackageNumber`) VALUES
	('PO001', '013A', 1, 1, '一般', 50, 20),
	('PO002', '113', 12, 6, '特採', 40, 68),
	('PO003', '217', 3, 2, '回收料', 30, 18),
	('PO004', '219A', 5, 3, '一般', 20, 0),
	('PO005', '435', 8, 4, '特採', 10, 4),
	('PO006', '439A', 10, 5, '回收料', 60, 60),
	('PO007', '113', 11, 7, '特採', 80, 20),
	('PO008', '113', 12, 7, '回收料', 100, 16);
/*!40000 ALTER TABLE `purchaseorder` ENABLE KEYS */;

-- 傾印  表格 sf.supplier 結構
CREATE TABLE IF NOT EXISTS `supplier` (
  `supplierID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `supplierName` varchar(256) NOT NULL,
  `material` varchar(256) NOT NULL,
  `unitPrice` int(8) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`supplierID`),
  KEY `supplierMaterial` (`material`),
  CONSTRAINT `supplierMaterial` FOREIGN KEY (`material`) REFERENCES `material` (`materialID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.supplier 的資料：~12 rows (大約)
/*!40000 ALTER TABLE `supplier` DISABLE KEYS */;
REPLACE INTO `supplier` (`supplierID`, `supplierName`, `material`, `unitPrice`) VALUES
	(1, '建鹽', '013A', 4),
	(2, '冠佳', '013A', 4),
	(3, '弘竹', '217', 43),
	(4, '鮮豐', '217', 43),
	(5, '興龍銓', '219A', 26),
	(6, '南方石墨', '435', 14),
	(7, '潤優', '435', 14),
	(8, '青島泛化', '435', 14),
	(9, '晟邦', '435', 14),
	(10, '穗曄', '439A', 16),
	(11, '佳欣', '113', 16),
	(12, ' 欣和', '113', 16);
/*!40000 ALTER TABLE `supplier` ENABLE KEYS */;

-- 傾印  表格 sf.user 結構
CREATE TABLE IF NOT EXISTS `user` (
  `userID` varchar(256) NOT NULL,
  `userName` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `authority` char(8) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  sf.user 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
REPLACE INTO `user` (`userID`, `userName`, `password`, `authority`) VALUES
	('admin', 'System Administrator', '$2y$10$VIBa5Ziq56jFt5HjI83EKOzG7618HQ9vVp7N3cSB.HJiNMvcCs2ay', 'admin');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
