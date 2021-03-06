<?php echo ($alert) ? '<div class="alert in alert-block fade alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Sukses!</strong> Penyimpanan Berhasil.</div>' : ''; ?>
<table class="responsive table table-bordered">
    <thead>
        <tr>
            <th width="20%" style="max-width:10%">BBM</th>
            <th width="20%" style="max-width:10%">Invoice</th>
            <th width="10%">Tgl. Tagihan</th>
            <th width="10%">Tgl. Jatuh Tempo</th>
            <th width="55%">Keterangan</th>
            <th>Total</th>
            <th width="5%">#</th>
        </tr>
    </thead>
    <tbody>
        <tr style="display:<?php echo ($ambil || isset($_POST['yt0'])) ? '' : 'none'; ?>">
            <td>
                <input type="text" class="codes" style="width:94%;">
            </td>
            <td>
                <input type="text" class="invoice_no" style="width:94%;">
            </td>
            <td style="text-align: center">
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'dateCoa',
                    'value' => (isset($_POST['dateCoa'])) ? $_POST['dateCoa'] : '',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'showAnim' => 'fold',
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                    ),
                    'htmlOptions' => array(
                        'id' => 'dateCoa',
                        'class' => 'dateCoa datepicker',
                        'style' => 'width:89%'
                    ),
                ));
                ?>
            </td>
            <td style="text-align: center">
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'terms',
                    'value' => (isset($_POST['terms'])) ? $_POST['terms'] : '',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'showAnim' => 'fold',
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                    ),
                    'htmlOptions' => array(
                        'id' => 'terms',
                        'class' => 'terms datepicker',
                        'style' => 'width:89%'
                    ),
                ));
                ?>
            <td>
                <input type="text" class="description" style="width:98%;">
            </td>
            <td style="text-align:center">
                <div class="input-prepend">
                    <span class="add-on">Rp.</span>
                    <input type="text" class="angka payment nilai" value="0">
                </div>
            </td>
            <td style="text-align:center">
                <a class="btn"><div class="addRow"><i class="icon-plus-sign"></i></div></a>
                <!--<input type="hidden" class="angka sup_id" value="107">-->
            </td>
        </tr>
        <?php
        if (empty($balance)) {
            echo '<tr class="kosong"><td colspan="7">Data Kosong</td></tr>';
        } else {
            $paymentss = 0;
            foreach ($balance as $res) {
                $paymentss = $res->payment;
                $coaDet = AccCoaDet::model()->find(array(
                    'condition' => 'reff_type="invoice" AND invoice_det_id =' . $res->id,
                    'order' => 'id ASC'
                ));
                $coaId = (!empty($coaDet->id)) ? $coaDet->id : '';
                $coaDate = (!empty($coaDet->date_coa)) ? $coaDet->date_coa : '';
                $termDate = (!empty($res->term_date)) ? $res->term_date : '';
                echo '<tr>';
                echo '<td><input type="text" class="code" name="code[]" value="' . $res->code . '" style="width:95%;"></td>';
                echo '<td><input type="text" class="code" name="invoice_no[]" value="' . $res->invoice_no . '" style="width:95%;"></td>';
                echo '<td><input type="text" class="dateStart" style="width:90%" name="date_coa[]" value="' . $coaDate . '"></td>';
                echo '<td><input type="text" class="term" style="width:90%" name="term_date[]" value="' . $termDate . '"></td>';
                echo '<td><input type="text" name="description[]" value="' . $res->description . '" style="width:98%;"></td>';
                echo '<td><div class="input-prepend">
                                <span class="add-on">Rp.</span>
                                <input class="angka nilai" style="width:95%;" type="text" value="' . $paymentss . '" name="payment[]"></td>';
                echo '<td>
                            <span style="width:12px" class="btn delInv"><i class="icon-trash"></i></span>
                            <input type="hidden" class="user" name="user_id[]" value="' . $res->user_id . '">
                            <input type="hidden" class="id_invoice" name="id[]" value="' . $res->id . '">
                            <input type="hidden" class="id_coaDet" name="id_coaDet[]" value="' . $coaId . '">
                        </td>';
                echo '</tr>';
            }
        }
        ?>
        <tr class="addRows" style="display:none;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align:center;font-weight:bold">Total <?php // echo $sTot                       ?></td>
            <td class="span2" style="text-align:center">
                <div class="input-prepend">
                    <span class="add-on">Rp.</span>
                    <input id="total_charge" class="angka" value="0" style="width:75px;" type="text" readonly="readonly">
                </div>
            </td>
            <td width="5%"></td>
        </tr>
    </tfoot>
    <?php // } ?>
</table>
<?php if ($ambil || isset($_POST['yt0'])) { ?>
    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'icon' => 'ok white',
            'label' => 'Simpan',
        ));
        ?>
    </div>
<?php } ?>
