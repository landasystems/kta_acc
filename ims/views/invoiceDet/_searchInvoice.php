<div class="well well-small">
    <?php
    $data = array(0 => 'Pilih') + CHtml::listData($array, 'id', 'name');
    echo CHtml::dropDownList('accountName', '', $data, array('style'=>'width:100%'));
    ?>
</div>
<input type="hidden" name="type_account" id="type_account" value="">

<table class="table table-striped">
    <thead>
        <tr>
            <th style="text-align: center;width:10%;">Kode</th>
            <th style="text-align: center;width:40%">Keterangan</th>
            <th style="text-align: center;width:20%">Nilai</th>
            <th style="text-align: center;width:20%">Balance</th>
            <th style="text-align: center;width:10%">#</th>
        </tr>
    </thead>
    <tbody id="detail">

    </tbody>
</table>

<input type="hidden" value="" id="selectedClass"/>
<script type="text/javascript">
//    $("#accountName").select2();

    $("body").on("click", ".delInvoice", function () {
        var id = $(this).attr("det_id");
        var answer = confirm("YYakin mau menghapus invoice ini ? semua transaksi yang berkaitan dengan invoice ini akan ikut terhapus juga!");
        if (answer) {
            $.ajax({
                type: 'POST',
                data: {id: id},
                url: "<?php echo url('invoiceDet/dellInv'); ?>",
                success: function (data) {
                    selectInvoice();
                },
                error: function () {
                    $.toaster({priority: 'error', message: "Terjadi Kesalahan"});
                }
            });
        }
    });
</script>
