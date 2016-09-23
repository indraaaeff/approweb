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
	$pdfdata=$vi_req_att_data;
	$pdfname=$vi_req_attach;

//OPEN IN NEW TAB

// 	header('Content-type: application/pdf');
// header('Content-Disposition: inline; filename='.$pdfname);
// header('Content-Transfer-Encoding: binary');
// header('Accept-Ranges: bytes');
// echo $pdfread;

//     $file_handle = fopen($pdfdata, "r");

// 	header('Content-type: application/pdf');
//     header('Content-Disposition: inline; filename='.$pdfname.'"');
//     header('Content-Transfer-Encoding: binary');
//     header('Accept-Ranges: bytes');
// download
// // header('Content-Description: File Transfer');
// //     header('Content-Type: application/octet-stream');
// //     header('Content-Disposition: attachment; filename="'.basename($pdfname).'"');
// //     header('Expires: 0');
// //     header('Cache-Control: must-revalidate');
// //     header('Pragma: public');
// //     header('Content-Length: ' . filesize($pdfdata));
//     ob_clean();
//     ob_flush();
//     while (!feof($file_handle)) {
//     	$line = fgets($file_handle);
//     	// $read = fread($file_handle, filesize($pdfdata));
//     	// echo $line;
//     	// echo $read;
//     	echo fread($file_handle, filesize($pdfdata));
//     }
//     fclose($file_handle);
// $pdfdata = base64_encode($vi_req_att_data);
$file = $pdfdata;
$fp = fopen($pdfdata, "rb") ;

// header("Cache-Control: maxage=1");
// header("Pragma: public");
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=".basename($pdfname)."");
header("Content-Description: PHP Generated Data");
header("Content-Transfer-Encoding: binary");
header('Content-Length:' . filesize($pdfdata));
ob_clean();
flush();
while (!feof($fp)) {
   $buff = fopen($fp, filesize($pdfdata));
   // $buff = fgets($fp);
   echo $buff;
   // echo $pdfdata;
}
fclose($fp);



//DOWNLOAD
    // echo $pdfread;
    // print($pdfread);

	  // $file = $pdfread;
  // $filename = $pdfname;
  // header('Content-type: application/pdf');
  // header('Content-Length: '.$file); 
  // header('Content-Disposition: inline; filename="'.$filename.'"'); 
  // // header('Content-Disposition: inline; filename="' . $filename . '"');
  // header('Content-Transfer-Encoding: binary');
  // header('Accept-Ranges: bytes');
    // header('Content-Description: File Transfer');
    // header('Content-Type: application/octet-stream');
    // header('Content-Disposition: attachment; filename="'.basename($filename).'"');
    // header('Expires: 0');
    // header('Cache-Control: must-revalidate');
    // header('Pragma: public');
    // header('Content-Length: ' . filesize($file));
  	// ob_flush();
  	// ob_clean();
    // readfile($file);
    // exit;
  	// $fp = fopen($file, 'rb');
  	// while (!feof($fp))
  	{
  		// $final = fread($fp, filesize($file));
  		// print_r($final);
		// flush(); // this is essential for large downloads
	} 
	// fclose($fp);	 
	// echo $fp;
 }
?>