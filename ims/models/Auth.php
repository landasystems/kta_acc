<?php

class Auth extends CActiveRecord {
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function modules() {

        return array(
            array('visible' => landa()->checkAccess('Dashboard', 'r'), 'label' => '<span class="icon16 icomoon-icon-screen"></span>Dashboard', 'url' => array('/dashboard'), 'auth_id' => 'Dashboard'),
            array('visible' => landa()->checkAccess('SiteConfig', 'r') || landa()->checkAccess('Departement', 'r') || landa()->checkAccess('Roles', 'r'), 'label' => '<span class="icon16 icomoon-icon-cog"></span>Settings', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('SiteConfig', 'r'),'auth_id' => 'SiteConfig','label' => '<span class="icon16 iconic-icon-new-window"></span>Site config', 'url' => array('/siteConfig/update/1'), 'crud'=>array("r"=>1)),
                    array('visible' => landa()->checkAccess('Departement', 'r'),'auth_id' => 'Departement','label' => '<span class="icon16 minia-icon-office"></span>Unit Kerja', 'url' => array('/departement'), 'crud'=>array("c"=>1,"r"=>1,"u"=>1,"d"=>1)),
                    array('visible' => landa()->checkAccess('Roles', 'r'),'auth_id' => 'Roles','label' => '<span class="icon16 entypo-icon-users"></span>Access', 'url' => array('/roles'), 'crud'=>array("c"=>1,"r"=>1,"u"=>1,"d"=>1)),
                ),
            ),
            array('visible' => landa()->checkAccess('User', 'r'), 'label' => '<span class="icon16 icomoon-icon-user-3"></span>User', 'url' => array('/user'), 'auth_id' => 'User'),
            array('visible' => landa()->checkAccess('GroupSupplier', 'r') || landa()->checkAccess('userInvoice', 'r') || landa()->checkAccess('Supplier', 'r'), 'label' => '<span class="icon16 wpzoom-user-2"></span>Supplier', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('Supplier', 'r'), 'label' => '<span class="icon16 iconic-icon-pen"></span>Supplier', 'url' => array('/supplier'), 'auth_id' => 'Supplier'),
                    array('visible' => landa()->checkAccess('userInvoice', 'r'), 'label' => '<span class="icon16 iconic-icon-pen"></span>Supplier Payment', 'url' => array('/supplier/payment'), 'auth_id' => 'userInvoice'),
                )),
            array('visible' => landa()->checkAccess('Customer', 'r') || landa()->checkAccess('Customer', 'r'), 'label' => '<span class="icon16 wpzoom-user-2"></span>Customer', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('label' => '<span class="icon16 iconic-icon-pen"></span>Customer', 'url' => array('/customer'), 'auth_id' => 'Customer'),
                    array('label' => '<span class="icon16 iconic-icon-pen"></span>Customer Invoice', 'url' => array('/customer/receivable'), 'auth_id' => 'Customer'),
                )),
            array('visible' => (landa()->checkAccess('AccCoa', 'r') || landa()->checkAccess('AccJurnal', 'r') || landa()->checkAccess('BeginningBalance', 'r') || landa()->checkAccess('BeginningBalanceKartu', 'r')), 'label' => '<span class="icon16 silk-icon-checklist"></span>Accounting', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('AccCoa', 'r'), 'label' => '<span class="icon16 iconic-icon-pen"></span>Daftar Perkiraan', 'url' => array('/accCoa'), 'auth_id' => 'AccCoa'),
                    array('visible' => landa()->checkAccess('AccJurnal', 'r'), 'label' => '<span class="icon16 iconic-icon-pen"></span>Jurnal', 'url' => array('/accJurnal'), 'auth_id' => 'AccJurnal'),
                    array('visible' => landa()->checkAccess('BeginningBalance', 'r'), 'label' => '<span class="icon16 iconic-icon-pen"></span>Saldo Awal', 'url' => array('/accCoa/beginningbalance'), 'auth_id' => 'BeginningBalance'),
                )),
            array('visible' => (landa()->checkAccess('AccCashIn', 'r') || landa()->checkAccess('AccCashOut', 'r')), 'label' => '<span class="icon16 icomoon-icon-clipboard-2"></span>Transaksi', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('AccCashIn', 'r'), 'label' => '<span class="icon16 iconic-icon-pen"></span>Uang Masuk', 'url' => array('/accCashIn'), 'auth_id' => 'AccCashIn'),
                    array('visible' => landa()->checkAccess('AccCashOut', 'r'), 'label' => '<span class="icon16 iconic-icon-pen"></span>Uang Keluar', 'url' => array('/accCashOut'), 'auth_id' => 'AccCashOut'),
                )),
            array('visible' => (landa()->checkAccess('Report_Jurnal', 'r') || landa()->checkAccess('Report_Kasharian', 'r') || landa()->checkAccess('Report_Generalledger', 'r') || landa()->checkAccess('Report_NeracaSaldo', 'r')), 'label' => '<span class="icon16 cut-icon-printer-2"></span>Laporan', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array(landa()->checkAccess('Report_Jurnal', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Jurnal', 'url' => array('/report/jurnalUmum'), 'auth_id' => 'Report_Jurnal'),
                    array(landa()->checkAccess('Report_Kasharian', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Kas Harian', 'url' => array('/report/kasHarian'), 'auth_id' => 'Report_Kasharian'),
                    array(landa()->checkAccess('Report_Generalledger', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Buku Besar', 'url' => array('/report/generalLedger'), 'auth_id' => 'Report_Generalledger'),
                    array(landa()->checkAccess('Report_NeracaSaldo', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Neraca Saldo', 'url' => array('/report/neracaSaldo'), 'auth_id' => 'Report_NeracaSaldo'),
                )),
            array('visible' => (landa()->checkAccess('kartuPiutang', 'r') || landa()->checkAccess('RekapPiutang', 'r') || landa()->checkAccess('kartuHutang', 'r') || landa()->checkAccess('RekapHutang', 'r')), 'label' => '<span class="icon16 cut-icon-printer-2"></span>Buku Pembantu', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('kartuPiutang', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Kartu Piutang', 'url' => array('/report/kartuPiutang'), 'auth_id' => 'kartuPiutang'),
                    array('visible' => landa()->checkAccess('RekapPiutang', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Rekap Kartu Piutang', 'url' => array('/report/RekapPiutang'), 'auth_id' => 'RekapPiutang'),
                    array('visible' => landa()->checkAccess('kartuHutang', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Kartu Hutang', 'url' => array('/report/kartuHutang'), 'auth_id' => 'kartuHutang'),
                    array('visible' => landa()->checkAccess('RekapHutang', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Rekap Kartu Hutang', 'url' => array('/report/RekapHutang'), 'auth_id' => 'RekapHutang'),
                )),
            array('visible' => (landa()->checkAccess('DateConfig', 'r') || landa()->checkAccess('accFormatting', 'r')), 'label' => '<span class="icon16 wpzoom-settings"></span>Tools', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('DateConfig', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Auto Number', 'url' => array('dateConfig/index'), 'auth_id' => 'DateConfig'),
                )),
        );
    }

}
