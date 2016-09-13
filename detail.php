<?php
if ( isset($_GET['u']) && isset($_GET['p']) && isset($_GET['k']) ) {
	include "connection.php";
	include "user.php";
	include "jam.php";
	$PPO_Number = $_GET['p'];
	$PPO_Table = $Database->query( "Call GetPPO_Number( '$PPO_Number' )" );
	$key = "";
	if ( $PPO_Table && ($PPO_Table->num_rows > 0) ) { 
		$row = $PPO_Table->fetch_assoc();
		
		if ( ($_GET['k'] == $row['key_param'])  && ($tgl <= $row['expiration'])  ) {
			$by =$row['submit_by'];
			$tgl_pengajuan=$row['tgl_pengajuan'];
			$ex=$row['expiration'];
			$key = $_GET['k'];
		}
		$PPO_Table->data_seek(0);  // Go top
	}

	if ($key != ""){
		switch ( $_GET['u'] ) {
			case BOD_RT_ID : 
			$_SESSION['username'] = BOD_RT;
			break;
			case BOD_HP_ID : 
			$_SESSION['username'] = BOD_HP;
			break;
			case BOD_DL_ID : 
			$_SESSION['username'] = BOD_DL;
			break;
		}
	}
}

if( !isset( $_SESSION['username'] )) {
	if ( $PPO_Table ) {
		$PPO_Table->free();
		$Database->close();
	}

	if ( isset($_GET['u']) )
		header( "Location:expired.php?u=$_GET[u]" );
	else
		header( "Location:expired.php" );
	
}
	//user session
$user=$_SESSION['username'];
echo $user;
?>
<?php 
$sql = "SELECT * FROM vendor_invoice_detail";
$result = $Database->query($sql);
if ( $result && ($result->num_rows > 0) ) { 
		$row = $result->fetch_assoc();
		
			$no_po =$row['no_po'];
			// $tgl_pengajuan=$row['tgl_pengajuan'];
			// $ex=$row['expiration'];
			// $key = $_GET['k'];

		$result->data_seek(0);  // Go top
	}

 ?>