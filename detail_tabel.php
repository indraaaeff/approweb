<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/detail-tabel.css">
<style>

</style>
</head>
<body>

<?php
$q = ($_GET['q']);
//tabel vendor invoice
include "connection.php";

//queri ke vendor invoice
$getVendor ="SELECT * FROM vendor_invoice WHERE no_po = '$q'";
$resgetven = $Database->query( $getVendor );

while($row = $resgetven->fetch_assoc()) {

    $kv = $row['kode_vendor'];
    $vi_tglpo = $row['tgl_po'];
    $vi_nopo = $row['no_po'];
    $vi_reqby = $row['request_by'];
    $vi_inby = $row['input_by'];
    $vi_noppo = $row['no_ppo'];
    $vi_tglpengajuan = $row['po_tgl_pengajuan'];
    $vi_subby = $row['submit_by'];
    $vi_approved = $row['po_approved'];
    $vi_noinv = $row['no_invoice'];
    $vi_tglinv = $row['tgl_invoice'];
    $vi_req_attach = $row['req_attachment'];
    $vi_req_att_data = $row['req_attach_data'];

    $vi_dpp = $row['dpp'];
    $vi_ppn = $row['ppn'];
    $vi_pph23 = $row['pph_23'];
    $vi_pphfinal = $row['pph_final'];
    $totalPPO = $row['total'];
    $vi_ket = $row['keterangan'];

    // echo $kv;

//tabel vendor

$vendor = "SELECT * FROM vendor where kode_vendor= '$kv'";
$resven = $Database->query( $vendor );

while($row = $resven->fetch_assoc()) {
    $nama_vendor = $row['nama_vendor'];
    $cabang = $row['cabang'];

?>
<div class="col-md-6 header_detail">
    <form class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-sm-3 ratakanan mob" for="vendor">Vendor:</label>
            <div class="col-sm-9 nopaddleft mob">
                <input type="text" class="form-control fontsz kotak_input" id="vendor" value="<?php echo $nama_vendor; ?>" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3 ratakanan mob" for="up">Up:</label>
            <div class="col-sm-9 nopaddleft">
                <input type="text" class="form-control fontsz kotak_input" id="cabang" value="<?php ?>" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3 ratakanan mob" for="nomorpo">Nomor PO :</label>
            <div class="col-sm-6 nopaddleft mob">
                <input type="text" class="form-control fontsz kotak_input" id="nomor_po" value="<?php echo $q; ?>" readonly>
            </div>
            <div class="col-sm-3" id="ppn" style="width: 101px !important;height: 25px;border: 1px solid red !important;margin-bottom: 6px;border-radius: 3px;">
                <?php 
                if ($vi_ppn==0) {
                    echo "<h5 style='color:red;margin: 7px 5px 5px -10px;'>NON PPN</h5>";
                } else {
                    echo "<h5 style='color:red;margin: 7px 5px 5px -10px;'><b>PPN</b></h5>";
                } 

                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3 ratakanan mob" for="tanggalpo">Tanggal PO :</label>
            <div class="col-sm-9 nopaddleft mob">
                <input type="text" class="form-control fontsz kotak_input" id="tgl_po" value="<?php echo $vi_tglpo; ?>" readonly>
            </div>
        </div>
    </form>
</div>
<div class="col-md-6">
    <input type="checkbox" id="cbmodal">
    <input type="text" value="<?php echo $q; ?>">
</div>
<?php
}//end while vendor database


//tabel vendor_invoice_detail

$detail ="SELECT * FROM vendor_invoice_detail WHERE no_po = '$q'";
$resdet = $Database->query( $detail );

// start table detail
echo "<div class='col-md-12 table-responsive' style='padding-bottom:10px;'>
    <table class='table table-stripped table-bordered table-hover tinggi_table'>
    <thead class='fontsz' style='background-color: #f9ecdd;'>
    <tr class='tra'>
    <th class='col-xs-1'>No.</th>
    <th class='col-xs-4'>Description</th>
    <th class='col-xs-1'>Periode</th>
    <th class='col-xs-1'>Monthly</th>
    <th class='col-xs-1'>Quantity</th>
    <th class='col-xs-2'>Amount</th>
    <th class='col-xs-2'>Total</th>

</tr>
</thead>";
    echo "<tbody class='tbody scroll'>";
while($row = $resdet->fetch_assoc()) {

    //data vendor_invoice_detail
    $total_item;
    $nopo = $row['no_po'];
    $no_urut = $row['no_urut'];
    $item = $row['item_desc'];
    $periode = $row['periode'];
    $qty = $row['quantity'];
    $amount = $row['amount'];
    $monthly = $row['monthly'];

    echo "<tr align='center' class='tra'>";
    echo "<td class='fontsz col-xs-1 table_modal'>" . $no_urut . "</td>";
    echo "<td class='fontsz col-xs-4 ratakiri table_modal'>" . $item . "</td>";
    if (empty($periode)) {
        echo  "<td class='fontsz col-xs-1 table_modal'> - </td>";
    } else {
        echo  "<td class='fontsz col-xs-1 table_modal'>" . $periode . "</td>";
    }
    if ($monthly==0) {
        echo "<td class='fontsz col-xs-1 table_modal'><input type='checkbox' disabled></td>";
    } else {
        echo "<td class='fontsz col-xs-1 table_modal'><input type='checkbox' disabled checked></td>";
    }
    echo "<td class='fontsz col-xs-1 ratakanan table_modal'>" . $qty . "</td>";
    echo "<td class='fontsz col-xs-2 ratakanan table_modal'>".number_format($amount)."</td>";
    $total_item = $qty * $amount;
    echo "<td class='fontsz col-xs-2 ratakanan table_modal'>".number_format($total_item)."</td>";
    echo "</tr>";
}
    echo "</tbody>";
echo "</table>";
echo "</div>";
     ?>

     <div class="col-md-12 col-sm-12">
         <div class="col-md-4 col-sm-4" style="padding-bottom:5px;">
            <div class="col-md-12 info_modal_atas">
                <p>Request by : <b><?php echo $vi_reqby; ?></b></p>
                <p>Input by   : <b><?php echo $vi_inby; ?></b></p>
                <?php 
                    // header("Content-type: application/pdf");
                    // @read($vi_req_att_data);
                ?>
                <a>Show Attachment</a> 
                <p>
                <!-- <?php echo $vi_req_attach; ?> -->
                </p>
                <p>
                <!-- <?php echo $vi_req_att_data; ?>  -->
                </p>
            </div>
            <div class="col-md-12 info_modal_bawah" style="border: 1px solid #6b6161;padding: 8px 8px 0px 8px;margin-top: 4px;border-radius: 5px;">
                <p>Nomor PPO : <b><?php echo $vi_noppo; ?></b></p>
                <p>Tanggal Pengajuan   : <b><?php echo $vi_tglpengajuan; ?></b></p>
                <p>Submit By : <b><?php echo $vi_subby; ?></b></p>
                <p>Approved : <b><?php echo $vi_approved; ?></b></p>
            </div>
         </div>
         <div class="col-md-4 col-sm-4">
         <?php 
            if (empty($vi_noinv) && empty($vi_tglinv)) {
                echo '<p style="color:red;margin:0px;">Invoice Belum Tersedia.</p>';
            }
         ?>
            <form class="form-horizontal" style="float:right;" id="detailss">
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-6" for="dpp">Nomor Invoice :</label>
                    <div class="col-sm-6 nopaddleft">
                        <input type="text" class="form-control fontsz kotak_total ratakanan" id="dpp" value="<?php echo $vi_noinv; ?>" readonly>
                    </div>
                </div>
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-6" for="ppn">Tanggal Invoice :</label>
                    <div class="col-sm-6 nopaddleft">
                        <input type="text" class="form-control fontsz kotak_total ratakanan" id="ppn" value="<?php echo $vi_tglinv; ?>" readonly>
                    </div>
                </div>
            </form>

            <button disabled>Show Attachment</button>
        </div>
         <div class="col-md-4 col-sm-4 col-xs-12" style="padding-left:10px;">
            <form class="form-horizontal" style="float:right;" id="detailss">
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-6" for="dpp">DPP :</label>
                    <div class="col-sm-6 nopaddleft">
                        <input type="text" class="form-control fontsz kotak_total ratakanan" id="dpp" value="<?php echo "Rp. &nbsp;".number_format($vi_dpp); ?>" readonly>
                    </div>
                </div>
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-6" for="ppn">PPN (10%) :</label>
                    <div class="col-sm-6 nopaddleft">
                        <input type="text" class="form-control fontsz kotak_total ratakanan" id="ppn" value="<?php echo "Rp. &nbsp;".number_format($vi_ppn); ?>" readonly>
                    </div>
                </div>
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-6" for="pph23">PPH23 (2%) :</label>
                    <div class="col-sm-6 nopaddleft">
                        <input type="text" class="form-control fontsz kotak_total ratakanan" id="pph23" value="<?php echo "Rp. &nbsp;".number_format($vi_pph23); ?>" readonly>
                    </div>
                </div>
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-6" for="pphfinal">PPH Final (10%) :</label>
                    <div class="col-sm-6 nopaddleft">
                        <input type="text" class="form-control fontsz kotak_total ratakanan" id="pphfinal" value="<?php echo "Rp. &nbsp;".number_format($vi_pphfinal); ?>" readonly>
                    </div>
                </div>
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-6" for="total">Total :</label>
                    <div class="col-sm-6 nopaddleft">
                        <input type="text" class="form-control fontsz kotak_total ratakanan" id="total" value='<?php echo "Rp. &nbsp;".number_format($totalPPO); ?>' readonly>
                    </div>
                </div>
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-4" for="tanggalpo" style="padding:0px;">Keterangan :&nbsp;&nbsp;</label>
                    <div class="col-sm-8 nopaddleft">
                        <textarea name="keterangan" id="keterangan" class="form-control fontsz kotak_total " cols="20" rows="3" readonly><?php echo $vi_ket; ?></textarea>
                    </div>
                </div>
            </form>
         </div>
     </div>
     <?php 
     }//end while vendor_invoice tabel
?>
</body>
</html>