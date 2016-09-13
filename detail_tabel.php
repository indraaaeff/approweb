<!DOCTYPE html>
<html>
<head>
<style>
table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    /*border: 1px solid black;*/
    padding: 5px;
}

th {text-align: left;}
</style>
</head>
<body>

<?php
$q = ($_GET['q']);
//database vendor invoice
$con = mysqli_connect('report.hts.net.id','iis_web_client','Edccomp123@hts','iis_data_demo');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
//queri ke vendor invoice
$getVendor ="SELECT * FROM vendor_invoice WHERE no_po = '$q'";
$resgetven = mysqli_query($con,$getVendor);

while($row = mysqli_fetch_array($resgetven)) {

    $kv = $row['kode_vendor'];
    $vi_tglpo = $row['tgl_po'];
    $vi_nopo = $row['no_po'];
    $vi_reqby = $row['request_by'];
    $vi_inby = $row['input_by'];
    $vi_noppo = $row['no_ppo'];
    $vi_tglpengajuan = $row['po_tgl_pengajuan'];
    $vi_subby = $row['submit_by'];
    $vi_approved = $row['po_approved'];

    $vi_dpp = $row['dpp'];
    $vi_ppn = $row['ppn'];
    $vi_pph23 = $row['pph_23'];
    $vi_pphfinal = $row['pph_final'];
    $totalPPO = $row['total'];
    $vi_ket = $row['keterangan'];

    // echo $kv;

//database vendor
// $con = mysqli_connect('report.hts.net.id','iis_web_client','Edccomp123@hts','iis_data_demo');
// if (!$con) {
//     die('Could not connect: ' . mysqli_error($con));
// }
// echo $kv;
$vendor = "SELECT * FROM vendor where kode_vendor= '$kv'";
$resven = mysqli_query($con,$vendor);

while($row = mysqli_fetch_array($resven)) {
    $nama_vendor = $row['nama_vendor'];
    $cabang = $row['cabang'];

?>
<div class="col-md-6">
    <form class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-sm-4" for="vendor">Vendor:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="vendor" value="<?php echo $nama_vendor; ?>" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="up">Up:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="cabang" value="<?php ?>" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="nomorpo">Nomor PO :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="nomor_po" value="<?php echo $q; ?>" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="tanggalpo">Tanggal PO :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="tgl_po" value="<?php echo $vi_tglpo; ?>" readonly>
            </div>
        </div>
    </form>
</div>
<div class="col-md-6">
    <?php 
    if ($vi_ppn==0) {
        echo "<h5 style='color:red;'>NON PPN</h5>";
    } else {
        echo "<h5 style='color:red;'>PPN</h5>";
    } 

    ?>
</div>
<?php
}//end while vendor database


//database vendor_invoice_detail
// $con1 = mysqli_connect('report.hts.net.id','iis_web_client','Edccomp123@hts','iis_data_demo');
// if (!$con1) {
//     die('Could not connect: ' . mysqli_error($con1));
// }
$detail ="SELECT * FROM vendor_invoice_detail WHERE no_po = '$q'";
$resdet = mysqli_query($con,$detail);

// start table detail
echo "<div class='col-md-12'>
<div class='table-responsive table_modal'>
    <table class='table table-stripped table-bordered table-hover' style='margin-top:20px;'>
    <thead>
    <tr>
    <th class='tengah'>Nomor Urut</th>
    <th class='tengah'>Item Description</th>
    <th class='tengah'>Periode</th>
    <th class='tengah'>Monthly</th>
    <th class='tengah'>Quantity</th>
    <th class='tengah'>Amount</th>
    <th class='tengah'>Total</th>
</tr>
</thead>";
while($row = mysqli_fetch_array($resdet)) {
    // $kv = $row['kode_vendor'];
    // echo $kv;
    

    //data vendor_invoice_detail
    $total_item;
    $nopo = $row['no_po'];
    $no_urut = $row['no_urut'];
    $item = $row['item_desc'];
    $periode = $row['periode'];
    $qty = $row['quantity'];
    $amount = $row['amount'];
    $monthly = $row['monthly'];

    echo "<tbody>";
    echo "<tr align='center'>";
    // echo "<td>" . $row['no_po'] . "</td>";
    echo "<td>" . $no_urut . "</td>";
    echo "<td>" . $item . "</td>";
    echo "<td>" . $periode . "</td>";
    if ($monthly==0) {
        echo "<td>Tidak</td>";
    } else {
        echo "<td>Ya</td>";
    }
    echo "<td>" . $qty . "</td>";
    // echo "<td>" . $amount . "</td>";
    echo "<td>".number_format($amount,2,",",".")."</td>";
    $total_item = $qty * $amount;
    // echo "<td>" . $total_item . "</td>";
    echo "<td>".number_format($total_item,2,",",".")."</td>";
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
echo "</div>";
echo "</div>";
     ?>

     <div class="col-md-12">
         <div class="col-md-5">
            <div class="col-md-12">
                <p>Request by : <b><?php echo $vi_reqby; ?></b></p>
                <p>Input by   : <b><?php echo $vi_inby; ?></b></p>
                <button>Show Attachment</button>
            </div>
            <div class="col-md-12" style="border:1px solid black;margin-top:10px;">
                <p>Nomor PPO : <b><?php echo $vi_noppo; ?></b></p>
                <p>Tanggal Pengajuan   : <b><?php echo $vi_tglpengajuan; ?></b></p>
                <p>Submit By : <b><?php echo $vi_subby; ?></b></p>
                <p>Approved : <b><?php echo $vi_approved; ?></b></p>
            </div>
         </div>
         <div class="col-md-7">
            <form class="form-horizontal">
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-4" for="dpp">DPP</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="dpp" value="<?php echo "Rp. ".number_format($vi_dpp,2,",","."); ?>" readonly>
                    </div>
                </div>
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-4" for="ppn">PPN (10%)</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="ppn" value="<?php echo "Rp. ".number_format($vi_ppn,2,",","."); ?>" readonly>
                    </div>
                </div>
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-4" for="pph23">PPH23 (2%)</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="pph23" value="<?php echo "Rp. ".number_format($vi_pph23,2,",","."); ?>" readonly>
                    </div>
                </div>
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-4" for="pphfinal">PPH Final (10%)</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="pphfinal" value="<?php echo "Rp. ".number_format($vi_pphfinal,2,",","."); ?>" readonly>
                    </div>
                </div>
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-4" for="total">Total</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="total" value='<?php echo "Rp. ".number_format($totalPPO,2,",","."); ?>' readonly>
                    </div>
                </div>
                <div class="form-group nomarbot">
                    <label class="control-label col-sm-4" for="tanggalpo">Keterangan</label>
                    <div class="col-sm-8">
                        <textarea name="keterangan" id="keterangan" class="form-control" cols="20" rows="3" readonly><?php echo $vi_ket; ?></textarea>
                    </div>
                </div>
            </form>
         </div>
     </div>
     <?php 
     }//end while vendor_invoice database
// mysqli_close($con);
?>
</body>
</html>