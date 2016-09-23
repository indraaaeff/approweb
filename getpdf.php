<?php 
// $q = ($_GET['q']);
$q = 'PO-HTS/2016/05/0001';
$con = mysqli_connect('report.hts.net.id','iis_web_client','Edccomp123@hts','iis_data_demo');
if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}
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

	// echo $vi_req_att_data.'<br>';
	// echo $vi_req_attach;
	$pdfread=$vi_req_att_data;
	$pdfname=$vi_req_attach;
// 	header("Content-type: application/octet-stream");
//   	header("Content-disposition: attachment;filename=".$vi_req_attach);
//
	// $pdfread = "path_to_file";
	// $fp = fopen($pdfread, "r") ;

	// header("Cache-Control: maxage=1");
	// header("Pragma: public");
	// header("Content-type: application/pdf");
	// header("Content-Disposition: inline; filename=".$vi_req_attach."");
	// header("Content-Description: PHP Generated Data");
	// header("Content-Transfer-Encoding: binary");
	// header('Content-Length:' . filesize($pdfread));
	// ob_clean();
	// flush();
	// while (!feof($fp)) {
	// 	$buff = fread($fp, 1024);
	// 	print $buff;
	// }
	// exit;

// 	header('Content-type: application/pdf');
// header('Content-Disposition: inline; filename='.$pdfname);
// header('Content-Transfer-Encoding: binary');
// header('Accept-Ranges: bytes');
// echo $pdfread;
 }
?>
<a target = '_blank' href="http://localhost/approval/someScript.php">Show Attachment</a>