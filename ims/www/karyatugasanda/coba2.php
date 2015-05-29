<table class="tbPrint">
    <tbody>
        <tr>
            <td class="print" style="text-align:center;" width="20%">
                {cash_out}<br />
                {date}
            </td>
            <td class="print" style="text-align: center" width="60%">
                <h4 style="line-height: 10px">BUKTI PENGELUARAN</h4>
                <b>Kas / Bank [ {account} ]</b>
            </td>
            <td class="print" style="text-align:center;" width="20%">
                {no_approval}<br />
                {date_approval}
            </td>
        </tr>
        <tr>
            <td align="left" class="print" colspan="3">Dibayar Kepada : {description_to}</td>
        </tr>
    </tbody>
</table>
<div>{detail_cash}</div>
<table class="tbPrint">
    <tbody>
        <tr>
            <td class="print" width="180" rowspan="2" style="vertical-align: top">Giro a.n : {description_giro_an}</td>
            <td class="print" width="200">|&nbsp;&nbsp;|Tunai|&nbsp;&nbsp;|Cek|&nbsp;&nbsp;|B. Giro</td>
            <td class="print">No. :</td>
        </tr>
        <tr align="left">
            <td class="print">Bank :</td>
            <td class="print">Tgl. :</td>
        </tr>
        <tr>
            <td class="print" style="text-align:center" width="33%">Pimpinan</td>
            <td class="print" style="text-align:center">Administrasi</td>
            <td class="print" style="text-align:center" width="33%">Penerima</td>
        </tr>
        <tr height="100">
            <td class="print">&nbsp;</td>
            <td class="print">&nbsp;</td>
            <td class="print">&nbsp;</td>
        </tr>
    </tbody>
</table>
