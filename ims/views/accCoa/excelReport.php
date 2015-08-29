<table width="100%">
    <tr>
        <td  style="text-align: center" colspan="4"><h2>Daftar Akun</h2></td>
    </tr>
    <tr>
        <td style="text-align: center" colspan="4">
            Dicetak pada Tanggal : <?php echo date('d F Y'); ?>
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td>
        </td>
    </tr>
</table>
<?php if ($model !== null): ?>
    <table border="1">

        <tr>
            <th width="80px">
                id		</th>
            <th width="80px">
                Nama		</th>
            <th width="80px">
                Deskripsi		</th>
            <th width="80px">
                Saldo Saat Ini		</th>
        </tr>
        <?php foreach ($model as $row): ?>
            <tr>
                <td>
                    <?php echo $row->id; ?>
                </td>
                <td>
                    <?php echo $row->nestedName; ?>
                </td>
                <td>
                    <?php echo $row->description; ?>
                </td>
                <td>
                    <?php echo landa()->rp(AccCoaDet::model()->beginingBalance(date("Y-m-d"), $row->id)); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php endif; ?>