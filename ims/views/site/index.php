<?php
/* @var $this SiteController */
$this->pageTitle = 'Dashboard - Selamat Datang di Area Administrator';
$siteConfig = SiteConfig::model()->listSiteConfig();
?>
<div class="row-fluid">
    <div class="span8">
        <div class="row-fluid">
            <div class="box gradient">
                <div class="content" style="display: block;">
                    <?php
                    $coadet = cmd('
                            SELECT DATE_FORMAT(acca_acc_coa_det.date_coa,"%d %M %Y") as dateMonth,
                            SUM(acca_acc_coa_det.debet) as debet,
                            SUM(acca_acc_coa_det.credit) as ss
                            FROM acca_acc_coa_det,acca_acc_coa
                            WHERE acca_acc_coa_det.acc_coa_id = acca_acc_coa.id
                            AND acca_acc_coa_det.date_coa <= now() 
                            AND acca_acc_coa_det.date_coa >= now() - INTERVAL 7 DAY
                            AND ((acca_acc_coa.type_sub_ledger = "ks")
                            OR (acca_acc_coa.type_sub_ledger = "bk"))
                            GROUP BY YEAR(acca_acc_coa_det.date_coa),
                            MONTH(acca_acc_coa_det.date_coa),
                            DAY(acca_acc_coa_det.date_coa) ORDER BY acca_acc_coa_det.date_coa ASC')->queryAll();

                    $result = array();
                    $credit = array();
                    foreach ($coadet as $value) {
                        $result[$value['dateMonth']] = $value['debet'];
                    }
                    foreach ($coadet as $value) {
                        $credit[$value['dateMonth']] = $value['ss'];
                    }


                    $arrdebet = array();
                    $arrcredit = array();
                    for ($index = 6; $index > -1; $index--) {
                        $sDate = date("d F Y", strtotime("-" . $index . " days"));
                        $categories[] = $sDate;
                        $arrdebet[] = (isset($result[$sDate]) ? (int) $result[$sDate] : 0);
                        $arrcredit[] = (isset($credit[$sDate]) ? (int) $credit[$sDate] : 0);
                    }

                    trace($credit);
                    $this->widget(
                            'bootstrap.widgets.TbHighCharts', array(
                        'options' => array(
                            'title' => array(
                                'text' => 'Grafik Arus Kas/Bank',
                                'x' => -20 //center
                            ),
                            'subtitle' => array(
                                'text' => 'Arus Keluar/Masuk akun Kas/Bank',
                                'x' - 20
                            ),
                            'xAxis' => array(
                                'categories' => $categories,
                            ),
                            'yAxis' => array(
                                'title' => array(
                                    'text' => 'Amount (Rp.)',
                                ),
                            ),
                            'tooltip' => array(
                                'valueSuffix' => ' Rupiah'
                            ),
                            'legend' => array(
                                'layout' => 'vertical',
                                'align' => 'right',
                                'verticalAlign' => 'bottom',
                                'borderWidth' => 0
                            ),
                            'series' => array(
                                [
                                    'name' => 'Debet',
                                    'data' => $arrdebet
                                ],
                                [
                                    'name' => 'Credit',
                                    'data' => $arrcredit
                                ],
                            ),
                            'credits' => array(
                                'enabled' => false
                            )
                        ),
                        'htmlOptions' => array(
                            'style' => 'min-width: 310px; height: 400px; margin: 0 auto'
                        )
                            )
                    );
                    ?>
                </div>
            </div><!-- End .box -->


        </div>
    </div>
    <div class="span4">
        <div class="row-fluid">
            <div class="box">
                <div class="title">

                    <h4>
                        <span class="icon16 silk-icon-office"></span>
                        <span><?php echo $siteConfig->client_name ?></span>
                    </h4>
                </div>
                <div class="content">
                    <?php
                    $img = Yii::app()->landa->urlImg('site/', $siteConfig->client_logo, param('id'));
                    echo '<img src="' . $img['big'] . '" class="img-polaroid"/>';
                    ?>
                    <div class="clearfix"></div>
                    <dl>
                    </dl>
                </div>

            </div>
        </div>
    </div>
</div>
