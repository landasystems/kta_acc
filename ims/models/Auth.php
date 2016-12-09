<?php

class Auth extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function modules() {

        return array(
            array('visible' => landa()->checkAccess('Dashboard', 'r'), 'label' => 'Dashboard', 'url' => array('/dashboard'), 'auth_id' => 'Dashboard'),
            array('visible' => landa()->checkAccess('SiteConfig', 'r') || landa()->checkAccess('Departement', 'r') || landa()->checkAccess('Roles', 'r') || landa()->checkAccess('User', 'r'), 'label' => 'Settings', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
//                    array('visible' => landa()->checkAccess('SiteConfig', 'r'), 'auth_id' => 'SiteConfig', 'label' => 'Site config', 'url' => array('/siteConfig/update/1'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Departement', 'r'), 'auth_id' => 'Departement', 'label' => 'Unit Kerja', 'url' => array('/departement'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Roles', 'r'), 'auth_id' => 'Roles', 'label' => 'Access', 'url' => array('/roles'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('User', 'r'), 'label' => 'User', 'url' => array('/user'), 'auth_id' => 'User', 'crud' => array("r" => 1)),
                ),
            ),
            array('visible' => landa()->checkAccess('GroupSupplier', 'r') || landa()->checkAccess('userInvoice', 'r') || landa()->checkAccess('Supplier', 'r'), 'label' => 'Supplier', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('Supplier', 'r'), 'label' => 'Supplier', 'url' => array('/supplier'), 'auth_id' => 'Supplier', 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('userInvoice', 'r'), 'label' => 'Supplier Payment', 'url' => array('/supplier/payment'), 'auth_id' => 'userInvoice', 'crud' => array("r" => 1)),
                )),
            array('visible' => landa()->checkAccess('Customer', 'r') || landa()->checkAccess('Customer', 'r'), 'label' => 'Customer', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('label' => 'Customer', 'url' => array('/customer'), 'auth_id' => 'Customer', 'crud' => array("r" => 1)),
                    array('label' => 'Customer Invoice', 'url' => array('/customer/receivable'), 'auth_id' => 'Customer', 'crud' => array("r" => 1)),
                )),
            array('visible' => (landa()->checkAccess('AccCoa', 'r') || landa()->checkAccess('AccJurnal', 'r') || landa()->checkAccess('BeginningBalance', 'r') || landa()->checkAccess('BeginningBalanceKartu', 'r')), 'label' => 'Accounting', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('AccCoa', 'r'), 'label' => 'Daftar Perkiraan', 'url' => array('/accCoa'), 'auth_id' => 'AccCoa', 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('AccJurnal', 'r'), 'label' => 'Jurnal', 'url' => array('/accJurnal'), 'auth_id' => 'AccJurnal', 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('BeginningBalance', 'r'), 'label' => 'Saldo Awal', 'url' => array('/accCoa/beginningbalance'), 'auth_id' => 'BeginningBalance', 'crud' => array("r" => 1)),
                )),
            array('visible' => (landa()->checkAccess('AccCashIn', 'r') || landa()->checkAccess('AccCashOut', 'r')), 'label' => 'Transaksi', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('AccCashIn', 'r'), 'label' => 'Uang Masuk', 'url' => array('/accCashIn'), 'auth_id' => 'AccCashIn', 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('AccCashOut', 'r'), 'label' => 'Uang Keluar', 'url' => array('/accCashOut'), 'auth_id' => 'AccCashOut', 'crud' => array("r" => 1)),
                )),
            array('visible' => (landa()->checkAccess('Report_Jurnal', 'r') || landa()->checkAccess('Report_Kasharian', 'r') || landa()->checkAccess('Report_Generalledger', 'r') || landa()->checkAccess('Report_NeracaSaldo', 'r')), 'label' => 'Laporan', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array(landa()->checkAccess('Report_Jurnal', 'r'), 'label' => 'Jurnal', 'url' => array('/report/jurnalUmum'), 'auth_id' => 'Report_Jurnal', 'crud' => array("r" => 1)),
                    array(landa()->checkAccess('Report_Kasharian', 'r'), 'label' => 'Kas Harian', 'url' => array('/report/kasHarian'), 'auth_id' => 'Report_Kasharian', 'crud' => array("r" => 1)),
                    array(landa()->checkAccess('Report_Generalledger', 'r'), 'label' => 'Buku Besar', 'url' => array('/report/generalLedger'), 'auth_id' => 'Report_Generalledger', 'crud' => array("r" => 1)),
                    array(landa()->checkAccess('Report_NeracaSaldo', 'r'), 'label' => 'Neraca Saldo', 'url' => array('/report/neracaSaldo'), 'auth_id' => 'Report_NeracaSaldo', 'crud' => array("r" => 1)),
                )),
            array('visible' => (landa()->checkAccess('kartuPiutang', 'r') || landa()->checkAccess('RekapPiutang', 'r') || landa()->checkAccess('kartuHutang', 'r') || landa()->checkAccess('RekapHutang', 'r')), 'label' => 'Buku Pembantu', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('kartuPiutang', 'r'), 'label' => 'Kartu Piutang', 'url' => array('/report/kartuPiutang'), 'auth_id' => 'kartuPiutang', 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('RekapPiutang', 'r'), 'label' => 'Rekap Kartu Piutang', 'url' => array('/report/RekapPiutang'), 'auth_id' => 'RekapPiutang', 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('kartuHutang', 'r'), 'label' => 'Kartu Hutang', 'url' => array('/report/kartuHutang'), 'auth_id' => 'kartuHutang', 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('RekapHutang', 'r'), 'label' => 'Rekap Kartu Hutang', 'url' => array('/report/RekapHutang'), 'auth_id' => 'RekapHutang', 'crud' => array("r" => 1)),
                )),
            array('visible' => (landa()->checkAccess('DateConfig', 'r') || landa()->checkAccess('accFormatting', 'r')), 'label' => 'Tools', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('DateConfig', 'r'), 'label' => 'Auto Number', 'url' => array('dateConfig/index'), 'auth_id' => 'DateConfig', 'crud' => array("r" => 1)),
                )),
        );
    }

}
