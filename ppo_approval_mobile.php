<?php
if ( isset($_GET['u']) && isset($_GET['p']) && isset($_GET['k']) ) {
	include "connection.php";
	include "user.php";
	include "jam.php";
	$PPO_Number = $_GET['p'];
	$PPO_Table = $Database->query( "Call GetPPO_Number( '$PPO_Number' )" );
	$key = "";
	$u = $_GET['u'];
	$p = $_GET['p'];
	$k = $_GET['k'];
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
?>
<html>
<head>
	<title>HTS APP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

  	<link rel="stylesheet" href="js/bootstrap.js">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/sticky-footer-navbar.css">
	<link rel="stylesheet" type="text/css" href="css/stylehome.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</head>
<body>

	<div class="header-nav">
		<nav class="navbar navbar-fixed-top navbar-style">
			<div class="container-fluid" style="padding-left:5%;padding-right:5%;">
	          <a class="navbar-brand logo" href="#">Approval</a>
	          <div>
	          	<img class="logo-image" src="img/logo_hts_glowing.png" alt="">
	          </div>
			</div>
			<div class="header-date">
				<div class="container-fluid" style="padding-left:5%;padding-right:5%;">
					<div class="date">
						<?php echo $hari.",";?> <?php echo $tanggal;?> <?php echo $bulan;?> <?php echo $tahun;?> 
					</div>
					<div id="clock" class="time"></div>
					<div class="user">
						<b>
							<?php echo ucwords($user); ?>
						</b>
					</div>
				</div>
			</div>
		</nav>
	</div>
	<!-- spasi header dan content -->
	<div class="kotak_user">
	<div class="user_res">
		<b><?php echo ucwords($user);?></b>
	</div>
	</div>
	<!-- spasi user dengan content -->
    <?php
		if ($PPO_Table && ($PPO_Table->num_rows > 0)) {
	    $PPO_Table->data_seek(0);  // Go top
	    $row = $PPO_Table->fetch_assoc();
		$PPO_Number = $row['no_ppo'];
		$PPO_Table->free();
		$Database->next_result();
		}
    ?>

    <?php

	if (isset($_GET['p'])) {
	//$user=BOD_RT;
		$PPO_TableDetail = $Database->query( "Call GetPPO_Detail( '$PPO_Number' )" );
			if ($PPO_TableDetail && ($PPO_TableDetail->num_rows > 0)) {
		
	?>
	<div class="kotak_po">
		<div class="panel panel-default">
			<div class="panel-heading nopadding" style="width:100%; !important">
				<div class="hidden_po">
				 	<b class="head-mob">No Pengajuan : <?php echo $PPO_Number; ?><br></b>
					<b class="head-mob">Tanggal :  <?php echo $tgl_pengajuan;?><br></b>
					<b class="head-mob">Submitted By <?php echo $by;?><br></b>
						<?php 
							$total_ppo=$PPO_TableDetail->num_rows;
							echo '<b class="head-mob">Total PO : $total_ppo</b>';
						?>
				</div>
			</div>
			<div class="panel-body">
				<form method="post" action="post.php">
					<div id="tabel" class="table-responsive">
						<table class="table table-striped table-bordered table-hover">

								<?php
								$no = +1;
								$grand= 0;
								while ($row = $PPO_TableDetail->fetch_assoc()) {

								?>
							<tbody class="desktop">
								<!-- loop for row details -->
								<tr align="center" id="detail_column">
									<?php $Tanggal_po = date( 'd-m-Y', strtotime( $row['tgl_po'] )); ?>

            						<?php 
            						$po_approve_by_rt   = $row['approve_by_rt'];
            						$po_tgl_approved_rt = $row['tgl_approved_rt'];	  
            						$po_comment_rt      = $row['comment_rt'];		

            						$po_approve_by_hp   = $row['approve_by_hp'];
            						$po_tgl_approved_hp = $row['tgl_approved_hp'];	  
            						$po_comment_hp      = $row['comment_hp'];		

            						$po_approve_by_dl   = $row['approve_by_dl'];
            						$po_tgl_approved_dl = $row['tgl_approved_dl'];	  
            						$po_comment_dl      = $row['comment_dl'];

            						?>
            						
            						<?php 
            							$grand += $row['total'];
            						?>
            						
            					</tr>
            					<div class="gadget">
            					<input type="hidden" name="key" value="<?php echo $key;?>">
								<input type="hidden" name="u" value="<?php echo $_GET['u'];?>">
								<input type="hidden" name="total_ppo" id="total_ppo" value="<?php echo $total_ppo;?>"	>
								<input type="hidden" name="tgl_pengajuan" value="<?php echo $tgl_pengajuan;?>">	
            						<ul class="nav nav-tabs responsive" id="myTab">
            							<li class="">
            								<a href="#<?php echo 'po_details'.$no; ?>">
            									<?php $Tanggal_po = date( 'd-m-Y', strtotime( $row['tgl_po'] )); ?>
            									<b>No PO :</b> <?php echo $row['no_po']; ?><br>
            									<b>Tanggal PO :</b> <?php echo $Tanggal_po; ?><br>
            									<b>Nama Vendor :</b> <?php echo $row['nama_vendor']; ?><br><br>

            									<?php 
            									if(!empty($po_tgl_approved_rt)){
            										if($po_approve_by_rt==0){
            											echo '<p class="status" style="color:red;font-weight:bold;">Rejected</p>';
            										} else {
            											echo '<p class="status" style="color:green;font-weight:bold;">Approved</p>';
            										}
            									} else if (!empty($po_tgl_approved_hp) && !empty($po_tgl_approved_dl)) {
            										if ($po_approve_by_hp==0 && $po_approve_by_dl==0) {
            											echo '<p class="status"style="color:red;font-weight:bold;">Rejected</p>';
            										} else if ($po_approve_by_hp==1  && $po_approve_by_dl==1){
            											echo '<p class="status" style="color:green;font-weight:bold;">Approved</p>';
            										} else if ($po_approve_by_hp==0 && $po_approve_by_dl==1) {
            											echo '<p class="status"style="color:red;font-weight:bold;">Rejected</p>';
            										} else if ($po_approve_by_hp==1  && $po_approve_by_dl==0){
            											echo '<p class="status" style="color:red;font-weight:bold;">Rejected</p>';
            										}
            									} else if (!empty($po_tgl_approved_hp)){
            										if ($po_approve_by_hp==0) {
            											echo '<p class="status"style="color:red;font-weight:bold;">Rejected</p>';
            										} else {
            											echo '<p class="status" style="color:orange;font-weight:bold;">In Progress</p>';
            										}
            									} else if (!empty($po_tgl_approved_dl)){
            										if($po_approve_by_dl==0){
            											echo '<p class="status"style="color:red;font-weight:bold;">Rejected</p>';
            										} else {
            											echo '<p class="status" style="color:orange;font-weight:bold;">In Progress</p>';
            										}
            									}else {
            										echo '<p class="status" style="color:orange;font-weight:bold;">In Progress</p>';
            									}
            									?>
            									<?php 
            									if ($user == BOD_RT) {
            										if (!empty($po_tgl_approved_rt)) {
	            										if ($po_approve_by_rt==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Anda</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Anda</b> -</i></u></p>';
	            										}
            										}
            										if (!empty($po_tgl_approved_hp)) {
	            										if ($po_approve_by_hp==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Harijanto Pribadi</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Harijanto Pribadi</b> -</i></u></p>';
	            										}
            										}
            										if (!empty($po_tgl_approved_dl)) {
	            										if ($po_approve_by_dl==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Dicky Lisal</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Dicky Lisal</b> -</i></u></p>';
	            										}
            										}
            									} else if ($user == BOD_HP){
            										if (!empty($po_tgl_approved_rt)) {
	            										if ($po_approve_by_rt==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Richardus Teddy</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Richardus Teddy</b> -</i></u></p>';
	            										}
            										}
            										if (!empty($po_tgl_approved_hp)) {
	            										if ($po_approve_by_hp==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Anda</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Anda</b> -</i></u></p>';
	            										}
            										}
            										if (!empty($po_tgl_approved_dl)) {
	            										if ($po_approve_by_dl==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Dicky Lisal</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Dicky Lisal</b> -</i></u></p>';
	            										}
            										}
            									} else {
            										if (!empty($po_tgl_approved_rt)) {
	            										if ($po_approve_by_rt==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Richardus Teddy</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Richardus Teddy</b> -</i></u></p>';
	            										}
            										}
            										if (!empty($po_tgl_approved_hp)) {
	            										if ($po_approve_by_hp==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Harijanto Pribadi</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Harijanto Pribadi</b> -</i></u></p>';
	            										}
            										}
            										if (!empty($po_tgl_approved_dl)) {
	            										if ($po_approve_by_dl==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Anda</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Anda</b> -</i></u></p>';
	            										}
            										}
            									}
            									?>
            								</a>
            							</li>
            						</ul>
            						<div class="tab-content responsive">
            							<div class="tab-pane active" id="<?php echo 'po_details'.$no; ?>">
            								<b>
            									<!-- check PPN -->
            									<?php 
            									if ($row['non_ppn']==0) {
            										$ppn='Ya';
            									}else{
            										$ppn='Tidak';
            									}
            									?>
												<b>Total : </b><?php echo number_format($row['total']); ?><br>
            									PPN : <?php echo $ppn; ?><br><br>
            									<u>NOTE</u>  <br>
            									<!-- RT -->
            									<?php 
            										if ($user == BOD_RT) {
            									?>
            									<!-- rt on rt -->
            									Richardus Teddy
            									<?php 
            									if (!empty($po_tgl_approved_rt)) {
            										if ($po_approve_by_rt==1) {
														echo '<input type="checkbox" class="checked" disabled checked>';//checked disabled
            										} else {
														echo '<span class="glyphicon glyphicon-remove reject"></span>';
            										}
            									} else {
            										// echo "ini empty dan belum di proses RT ";
            										if (!empty($po_tgl_approved_hp) && !empty($po_tgl_approved_dl)) {
            											if ($po_approve_by_hp==1 && $po_approve_by_dl==1) {
            												echo '<input type="checkbox" class="checked" disabled>';
            											} else if ($po_approve_by_hp==1 && $po_approve_by_dl==0){
            												echo '<input type="checkbox" class="checked" disabled>';
            											} else {
            												echo '<input type="checkbox" class="checked" disabled>';
            											}
            										} else if (!empty($po_tgl_approved_hp)){
            											if ($po_approve_by_hp==0) {
            												echo '<input type="checkbox" class="checked" disabled>';
            											} else {
            									?>
            									<input  type="checkbox" class="tanggal cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" <?php if($user==BOD_HP || $user==BOD_DL){echo " disabled";}?> >
            									<?php 
            											}
            										} else if (!empty($po_tgl_approved_dl)){
            											if ($po_approve_by_dl==0) {
            												echo '<input type="checkbox" class="checked" disabled>';
            											} else {
            									?>
												<input  type="checkbox" class="tanggal cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" <?php if($user==BOD_HP || $user==BOD_DL){echo " disabled";}?>>
            									<?php
            											}
            										} else {
            									?>
            									<input  type="checkbox" class="tanggal cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" <?php if($user==BOD_HP || $user==BOD_DL){echo " disabled";}?>>
            									<?php
            										}
            									?>
            									<!-- tambah variabel HP DAN DL di login RT -->
            									<input  type="hidden"  name="po_tgl_app_hp[]" value="<?php echo $po_tgl_approved_hp;?>" >
            									<input  type="hidden"  name="po_app_hp[]"     value="<?php echo $po_approve_by_hp;?>" >
            									<input  type="hidden"  name="po_tgl_app_dl[]" value="<?php echo $po_tgl_approved_dl;?>" >
            									<input  type="hidden"  name="po_app_dl[]"     value="<?php echo $po_approve_by_dl;?>" >

			            						<!-- variabel RT -->
			            						<input type="hidden" name="user" id="<?php echo $user.$no; ?>" value="<?php echo $user; ?>">
			            						<input type="hidden" name="po_tgl_approved_rt[]" class="datetime" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_rt;?>" readonly="readonly">
			            						<input type="hidden" name="po_approve_by_rt[]" class="approved" id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_rt;?>" readonly="readonly">
			            						<input type="hidden" name="no_po[]"  value="<?php echo $row['no_po'];?>">
			            						<input type="hidden" name="total[]"  value="<?php echo $row['total'];?>">
			            						<input type="hidden" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
			            						<input type="hidden" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
			            						<input type="hidden" name="x[]" class="xy" id="x<?php echo $no;?>">
			            						<input type="hidden" name="no_ppo" value="<?php echo $PPO_Number;?>">
			            						<input type="hidden" name="sub_by" value="<?php echo $by;?>">
            									<?php 
            									}
            									?>
            									<textarea class="form-control" rows="2" name="po_comment_rt[]" id="ta<?php echo $no; ?>" <?php if($user == BOD_HP || $user == BOD_DL){echo "readonly";} ?>
            										<?php 
            										if (!empty($po_tgl_approved_rt)) {
            											echo "readonly";
            										} else {
            											if (!empty($po_tgl_approved_hp)&&!empty($po_tgl_approved_dl)) {
            												if ($po_approve_by_hp==1 && $po_approve_by_dl==1) {
            													echo "readonly";
            												}else if ($po_approve_by_hp==0 && $po_approve_by_dl==0){
            													echo "readonly";
            												}else{
            													echo "";
            												}
            											} elseif (!empty($po_tgl_approved_hp)) {
            												if ($po_approve_by_hp==0) {
            													echo "readonly";
            												}else{
            													echo "";
            												}
            											} elseif (!empty($po_tgl_approved_dl)) {
            												if ($po_approve_by_dl==0) {
            													echo "readonly";
            												}else{
            													echo"";
            												}	
            											} else {
            												echo "";
            											}
            										}
            										?>

            									><?php if(!empty($po_comment_rt)){echo $po_comment_rt;} ?></textarea>
            									<!-- hp on rt -->
            									Harijanto Pribadi
												<?php
												if (!empty($po_tgl_approved_hp)) {
													if ($po_approve_by_hp==1) {
														echo '<input type="checkbox" class="checked" disabled checked>';
													} else {
														echo '<span class="glyphicon glyphicon-remove reject"></span>';
													}
												} else {
											// echo "ini HP blm proses ";
													if (!empty($po_tgl_approved_rt)) {
														if ($po_approve_by_rt==1) {
															echo '<input type="checkbox" class="checked" disabled>';
														}else{
															echo '<input type="checkbox" class="checked" disabled>';
														}
													} else if (!empty($po_tgl_approved_dl)){
														if ($po_approve_by_dl==1) {
												?>
															<input type="checkbox" class="checked" disabled>
												<?php
														} else {
															echo '<input type="checkbox" class="checked" disabled>';
														}
													} else {
												?>
														<input type="checkbox" class="checked" disabled>
												<?php
													}
												}
												?>
												<textarea class="form-control" rows="2" name="po_comment_hp[]" <?php if($user == BOD_RT || $user == BOD_DL){echo "readonly";} ?> 
													<?php 
													if (!empty($po_tgl_approved_rt)) {
														echo "readonly";
													}else {
														if (!empty($po_tgl_approved_hp)) {
															if ($po_approve_by_hp==0) {
																echo "readonly";
															} else {
																echo "readonly";
															}
														} else if(!empty($po_tgl_approved_dl)){
															if ($po_approve_by_dl==0) {
																echo "readonly";
															} else {
																echo "";
															}
														} else {
															echo "";
														}
													}
													?> 
													><?php if(!empty($po_comment_hp)){echo $po_comment_hp;} ?></textarea>
												<!-- DL on RT -->
												Dicky Lisal
												<?php
													if (!empty($po_tgl_approved_dl)) {
														if ($po_approve_by_dl==1) {
															echo '<input type="checkbox" class="checked" disabled checked>';
														} else {
															echo '<span class="glyphicon glyphicon-remove reject"></span>';
														}
													} else {
														// echo "ini HP blm proses ";
														if (!empty($po_tgl_approved_rt)) {
															if ($po_approve_by_rt==1) {
																echo '<input type="checkbox" class="checked" disabled>';
															}else{
																echo '<input type="checkbox" class="checked" disabled>';
															}
														} else if (!empty($po_tgl_approved_hp)){
															if ($po_approve_by_hp==1) {
												?>
												<input type="checkbox" class="checked" disabled>
												<?php
															} else {
																echo '<input type="checkbox" class="checked" disabled>';
															}
														} else {
												?>
												<input type="checkbox" class="checked" disabled>
												<?php
														}
													}
												?>
												<textarea class="form-control" rows="2" name="po_comment_dl[]" <?php if($user == BOD_RT || $user == BOD_HP){echo "readonly";} ?> 
													<?php 
													if (!empty($po_tgl_approved_rt)) {
														echo "readonly";
													}else {
														if (!empty($po_tgl_approved_dl)) {
															if ($po_approve_by_dl==0) {
																echo "readonly";
															} else {
																echo "readonly";
															}
														} else if(!empty($po_tgl_approved_hp)){
															if ($po_approve_by_hp==0) {
																echo "readonly";
															} else {
																echo "";
															}
														} else {
															echo "";
														}
													}
													?>
													><?php if(!empty($po_comment_dl)){echo $po_comment_dl;} ?></textarea>
												<!-- BOD_HP SESSION -->
            									<?php 
            										} else if ( $user == BOD_HP)
													{
            									?>
            									<!-- rt on hp -->
            									Richardus Teddy
            									<?php 
            									if (!empty($po_tgl_approved_rt)) {
            										if ($po_approve_by_rt==1) {
														echo '<input type="checkbox" class="checked" disabled checked>';//checked disabled
            										} else {
														echo '<span class="glyphicon glyphicon-remove reject"></span>';
            										}
            									} else {
            										// echo "ini empty dan belum di proses RT ";
            										if (!empty($po_tgl_approved_hp) && !empty($po_tgl_approved_dl)) {
            											if ($po_approve_by_hp==1 && $po_approve_by_dl==1) {
            												echo '<input type="checkbox" class="checked" disabled>';
            											} else if ($po_approve_by_hp==1 && $po_approve_by_dl==0){
            												echo '<input type="checkbox" class="checked" disabled>';
            											} else {
            												echo '<input type="checkbox" class="checked" disabled>';
            											}
            										} else if (!empty($po_tgl_approved_hp)){
            											if ($po_approve_by_hp==0) {
            												echo '<input type="checkbox" class="checked" disabled>';
            											} else {
            									?>
            									<input type="checkbox" class="checked" disabled>
            									<?php 
            											}
            										} else if (!empty($po_tgl_approved_dl)){
            											if ($po_approve_by_dl==0) {
            												echo '<input type="checkbox" class="checked" disabled>';
            											} else {
            									?>
            									<input type="checkbox" class="checked" disabled>
            									<?php
            											}
            										} else {
            									?>
            									<input type="checkbox" class="checked" disabled>
            									<?php
            										}
            									}
            									?>
            									<textarea class="form-control" rows="2" name="po_comment_rt[]" id="ta<?php echo $no; ?>" <?php if($user == BOD_HP || $user == BOD_DL){echo "readonly";} ?>
            										<?php 
            										if (!empty($po_tgl_approved_rt)) {
            											echo "readonly";
            										} else {
            											if (!empty($po_tgl_approved_hp)&&!empty($po_tgl_approved_dl)) {
            												if ($po_approve_by_hp==1 && $po_approve_by_dl==1) {
            													echo "readonly";
            												}else if ($po_approve_by_hp==0 && $po_approve_by_dl==0){
            													echo "readonly";
            												}else{
            													echo "";
            												}
            											} elseif (!empty($po_tgl_approved_hp)) {
            												if ($po_approve_by_hp==0) {
            													echo "readonly";
            												}else{
            													echo "";
            												}
            											} elseif (!empty($po_tgl_approved_dl)) {
            												if ($po_approve_by_dl==0) {
            													echo "readonly";
            												}else{
            													echo"";
            												}	
            											} else {
            												echo "";
            											}
            										}
            										?>

            									><?php if(!empty($po_comment_rt)){echo $po_comment_rt;} ?></textarea>
            									<!-- hp on hp -->
            									Harijanto Pribadi
												<!-- <input type="" name="po_tgl_approved_hp[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_hp;?>" readonly="readonly">
												<input type="" name="po_approve_by_hp[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_hp;?>" readonly="readonly"> -->
												<?php
													if (!empty($po_tgl_approved_hp)) {
														if ($po_approve_by_hp==1) {
															echo '<input type="checkbox" class="checked" disabled checked>';
														} else {
															echo '<span class="glyphicon glyphicon-remove reject"></span>';
														}
													} else {
														// echo "ini HP blm proses ";
														if (!empty($po_tgl_approved_rt)) {
															if ($po_approve_by_rt==1) {
																echo '<input type="checkbox" class="checked" disabled>';
															}else{
																echo '<input type="checkbox" class="checked" disabled>';
															}
														} else if (!empty($po_tgl_approved_dl)){
															if ($po_approve_by_dl==1) {
												?>
												<input  type="checkbox" class="tanggal cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" <?php if($user==BOD_RT || $user==BOD_DL){echo " disabled";}?>>
												<?php
															} else {
																echo '<input type="checkbox" class="checked" disabled>';
															}
														} else {
												?>
												<input  type="checkbox" class="tanggal cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" <?php if($user==BOD_RT || $user==BOD_DL){echo " disabled";}?>>
												<?php
														}
												?>
												<!-- tambah variabel RT DAN DL di login HP -->
												<input  type="hidden"  name="po_app_rt[]"     value="<?php echo $po_approve_by_rt;?>" >
												<input  type="hidden"  name="po_tgl_app_rt[]" value="<?php echo $po_tgl_approved_rt;?>" >
												<input  type="hidden"  name="po_app_dl[]"     value="<?php echo $po_approve_by_dl;?>" >
												<input  type="hidden"  name="po_tgl_app_dl[]" value="<?php echo $po_tgl_approved_dl;?>" >
												<!-- variabel HP -->
												<input type="" name="user" id="<?php echo $user.$no; ?>" value="<?php echo $user; ?>">
												<input type="" name="po_tgl_approved_hp[]" class="datetime" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_hp;?>" readonly>
												<input type="" name="po_approve_by_hp[]" class="approved" id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_hp;?>" readonly>
												<input type="" name="no_po[]"  value="<?php echo $row['no_po'];?>">
												<input type="" name="total[]"  value="<?php echo $row['total'];?>">
												<input type="" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
												<input type="" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
												<input type="" name="x[]" class="xy" id="x<?php echo $no;?>">
												<input type="" name="no_ppo" value="<?php echo $PPO_Number;?>">
												<input type="" name="sub_by" value="<?php echo $by;?>">
												<?php
													}
												?>
												<textarea class="form-control" rows="2" name="po_comment_hp[]" <?php if($user == BOD_RT || $user == BOD_DL){echo "readonly";} ?> 
													<?php 
													if (!empty($po_tgl_approved_rt)) {
														echo "readonly";
													}else {
														if (!empty($po_tgl_approved_hp)) {
															if ($po_approve_by_hp==0) {
																echo "readonly";
															} else {
																echo "readonly";
															}
														} else if(!empty($po_tgl_approved_dl)){
															if ($po_approve_by_dl==0) {
																echo "readonly";
															} else {
																echo "";
															}
														} else {
															echo "";
														}
													}
													?> 
												><?php if(!empty($po_comment_hp)){echo $po_comment_hp;} ?></textarea>
												<!-- dl on hp -->
												Dicky Lisal
												<?php
													if (!empty($po_tgl_approved_dl)) {
														if ($po_approve_by_dl==1) {
															echo '<input type="checkbox" class="checked" disabled checked>';
														} else {
															echo '<span class="glyphicon glyphicon-remove reject"></span>';
														}
													} else {
														// echo "ini HP blm proses ";
														if (!empty($po_tgl_approved_rt)) {
															if ($po_approve_by_rt==1) {
																echo '<input type="checkbox" class="checked" disabled>';
															}else{
																echo '<input type="checkbox" class="checked" disabled>';
															}
														} else if (!empty($po_tgl_approved_hp)){
															if ($po_approve_by_hp==1) {
												?>
												<input type="checkbox" class="checked" disabled>
												<?php
															} else {
																echo '<input type="checkbox" class="checked" disabled>';
															}
														} else {
												?>
												<input type="checkbox" class="checked" disabled>
												<?php
														}
													}
												?>
												<textarea class="form-control" rows="2" name="po_comment_dl[]" <?php if($user == BOD_RT || $user == BOD_HP){echo "readonly";} ?> 
													<?php 
													if (!empty($po_tgl_approved_rt)) {
														echo "readonly";
													}else {
														if (!empty($po_tgl_approved_dl)) {
															if ($po_approve_by_dl==0) {
																echo "readonly";
															} else {
																echo "readonly";
															}
														} else if(!empty($po_tgl_approved_hp)){
															if ($po_approve_by_hp==0) {
																echo "readonly";
															} else {
																echo "";
															}
														} else {
															echo "";
														}
													}
													?>
													><?php if(!empty($po_comment_dl)){echo $po_comment_dl;} ?></textarea>
            									<?php
            										} else if ($user == BOD_DL){
												?>
													<!-- rt on DL -->
	            									Richardus Teddy
	            									<input type="hidden" name="po_tgl_approved_hp[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_hp;?>" readonly="readonly">
													<input type="hidden" name="po_approve_by_hp[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_hp;?>" readonly="readonly">
	            									<?php 
	            									if (!empty($po_tgl_approved_rt)) {
	            										if ($po_approve_by_rt==1) {
															echo '<input type="checkbox" class="checked" disabled checked>';//checked disabled
	            										} else {
															echo '<span class="glyphicon glyphicon-remove reject"></span>';
	            										}
	            									} else {
	            										// echo "ini empty dan belum di proses RT ";
	            										if (!empty($po_tgl_approved_hp) && !empty($po_tgl_approved_dl)) {
	            											if ($po_approve_by_hp==1 && $po_approve_by_dl==1) {
	            												echo '<input type="checkbox" class="checked" disabled>';
	            											} else if ($po_approve_by_hp==1 && $po_approve_by_dl==0){
	            												echo '<input type="checkbox" class="checked" disabled>';
	            											} else {
	            												echo '<input type="checkbox" class="checked" disabled>';
	            											}
	            										} else if (!empty($po_tgl_approved_hp)){
	            											if ($po_approve_by_hp==0) {
	            												echo '<input type="checkbox" class="checked" disabled>';
	            											} else {
	            									?>
	            									<input type="checkbox" class="checked" disabled>
	            									<?php 
	            											}
	            										} else if (!empty($po_tgl_approved_dl)){
	            											if ($po_approve_by_dl==0) {
	            												echo '<input type="checkbox" class="checked" disabled>';
	            											} else {
	            									?>
	            									<input type="checkbox" class="checked" disabled>
	            									<?php
	            											}
	            										} else {
	            									?>
	            									<input type="checkbox" class="checked" disabled>
	            									<?php
	            										}
	            									}
	            									?>
	            									<textarea class="form-control" rows="2" name="po_comment_rt[]" id="ta<?php echo $no; ?>" <?php if($user == BOD_HP || $user == BOD_DL){echo "readonly";} ?>
	            										<?php 
	            										if (!empty($po_tgl_approved_rt)) {
	            											echo "readonly";
	            										} else {
	            											if (!empty($po_tgl_approved_hp)&&!empty($po_tgl_approved_dl)) {
	            												if ($po_approve_by_hp==1 && $po_approve_by_dl==1) {
	            													echo "readonly";
	            												}else if ($po_approve_by_hp==0 && $po_approve_by_dl==0){
	            													echo "readonly";
	            												}else{
	            													echo "";
	            												}
	            											} elseif (!empty($po_tgl_approved_hp)) {
	            												if ($po_approve_by_hp==0) {
	            													echo "readonly";
	            												}else{
	            													echo "";
	            												}
	            											} elseif (!empty($po_tgl_approved_dl)) {
	            												if ($po_approve_by_dl==0) {
	            													echo "readonly";
	            												}else{
	            													echo"";
	            												}	
	            											} else {
	            												echo "";
	            											}
	            										}
	            										?>

	            									><?php if(!empty($po_comment_rt)){echo $po_comment_rt;} ?></textarea>
	            									<!-- hp on dl -->
            									Harijanto Pribadi
												<?php
												if (!empty($po_tgl_approved_hp)) {
													if ($po_approve_by_hp==1) {
														echo '<input type="checkbox" class="checked" disabled checked>';
													} else {
														echo '<span class="glyphicon glyphicon-remove reject"></span>';
													}
												} else {
											// echo "ini HP blm proses ";
													if (!empty($po_tgl_approved_rt)) {
														if ($po_approve_by_rt==1) {
															echo '<input type="checkbox" class="checked" disabled>';
														}else{
															echo '<input type="checkbox" class="checked" disabled>';
														}
													} else if (!empty($po_tgl_approved_dl)){
														if ($po_approve_by_dl==1) {
												?>
															<input type="checkbox" class="checked" disabled>
												<?php
														} else {
															echo '<input type="checkbox" class="checked" disabled>';
														}
													} else {
												?>
														<input type="checkbox" class="checked" disabled>
												<?php
													}
												}
												?>
												<textarea class="form-control" rows="2" name="po_comment_hp[]" <?php if($user == BOD_RT || $user == BOD_DL){echo "readonly";} ?> 
													<?php 
													if (!empty($po_tgl_approved_rt)) {
														echo "readonly";
													}else {
														if (!empty($po_tgl_approved_hp)) {
															if ($po_approve_by_hp==0) {
																echo "readonly";
															} else {
																echo "readonly";
															}
														} else if(!empty($po_tgl_approved_dl)){
															if ($po_approve_by_dl==0) {
																echo "readonly";
															} else {
																echo "";
															}
														} else {
															echo "";
														}
													}
													?> 
													><?php if(!empty($po_comment_hp)){echo $po_comment_hp;} ?></textarea>
													<!-- dl on dl -->
													Dicky Lisal
													<?php
														if (!empty($po_tgl_approved_dl)) {
															if ($po_approve_by_dl==1) {
																echo '<input type="checkbox" class="checked" disabled checked>';
															} else {
																echo '<span class="glyphicon glyphicon-remove reject"></span>';
															}
														} else {
															// echo "ini HP blm proses ";
															if (!empty($po_tgl_approved_rt)) {
																if ($po_approve_by_rt==1) {
																	echo '<input type="checkbox" class="checked" disabled>';
																}else{
																	echo '<input type="checkbox" class="checked" disabled>';
																}
															} else if (!empty($po_tgl_approved_hp)){
																if ($po_approve_by_hp==1) {
													?>
													<input  type="checkbox" class="tanggal cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" <?php if($user==BOD_RT || $user==BOD_HP){echo " disabled";}?>>
													<?php
																} else {
																	echo '<input type="checkbox" class="checked" disabled>';
																}
															} else {
													?>
													<input  type="checkbox" class="tanggal cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" <?php if($user==BOD_RT || $user==BOD_HP){echo " disabled";}?>>
													<?php
															}
													?>
													<!-- tambah variabel RT DAN HP di login DL -->
													<input  type="hidden"  name="po_app_rt[]"     value="<?php echo $po_approve_by_rt;?>" >
													<input  type="hidden"  name="po_tgl_app_rt[]" value="<?php echo $po_tgl_approved_rt;?>" >
													<input  type="hidden"  name="po_app_hp[]"     value="<?php echo $po_approve_by_hp;?>" >
													<input  type="hidden"  name="po_tgl_app_hp[]" value="<?php echo $po_tgl_approved_hp;?>" >
													<!-- variabel DL -->
													<input type="hidden" name="user" id="<?php echo $user.$no; ?>" value="<?php echo $user; ?>">
													<input type="hidden" name="po_tgl_approved_dl[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_dl;?>" readonly>
													<input type="hidden" name="po_approve_by_dl[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_dl;?>" readonly>
													<input type="hidden" name="no_po[]"  value="<?php echo $row['no_po'];?>">
													<input type="hidden" name="total[]"  value="<?php echo $row['total'];?>">
													<input type="hidden" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
													<input type="hidden" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
													<input type="hidden" name="x[]" id="x<?php echo $no;?>">
													<input type="hidden" name="no_ppo" value="<?php echo $PPO_Number;?>">
													<input type="hidden" name="sub_by" value="<?php echo $by;?>">
													<?php 
														}
													?>
													<textarea class="form-control" rows="2" name="po_comment_dl[]" <?php if($user == BOD_RT || $user == BOD_HP){echo "readonly";} ?> 
														<?php 
														if (!empty($po_tgl_approved_rt)) {
															echo "readonly";
														}else {
															if (!empty($po_tgl_approved_dl)) {
																if ($po_approve_by_dl==0) {
																	echo "readonly";
																} else {
																	echo "readonly";
																}
															} else if(!empty($po_tgl_approved_hp)){
																if ($po_approve_by_hp==0) {
																	echo "readonly";
																} else {
																	echo "";
																}
															} else {
																echo "";
															}
														}
														?>
														><?php if(!empty($po_comment_dl)){echo $po_comment_dl;} ?></textarea>
												<?php
													}
            									?>
											</b>
											<br>
										</div>
									</div>
            					</div>
            					<?php 
            						$no++;
            					}
            					?>
            					<input type="hidden" name="grand" value="<?php echo $grand; ?>">
            					<input type="hidden"  name="hp_tgl" value="<?php echo $po_tgl_approved_hp;?>" >				
            					<input type="hidden"  name="dl_tgl" value="<?php echo $po_tgl_approved_dl;?>" >				
            					<input type="hidden"  name="rt_tgl" value="<?php echo $po_tgl_approved_rt;?>" >

 								<!-- validation if BOD processed or not -->
            					<?php 
            					if ( isset($_GET['notif']) )
            					{	
            						if (!empty($po_tgl_approved_rt) || !empty($po_tgl_approved_hp) || !empty($po_tgl_approved_dl)) {
            							echo "";
            						} else {
            					 		echo"<div class='alerts'> $_GET[notif] </div>";
            						}
									if ($user==BOD_RT) 
            					 	{
            					 		if (!is_null( $po_tgl_approved_rt ) || !empty( $po_tgl_approved_rt ))
            					 		{
            					 			if ( !is_null( $po_tgl_approved_hp ) || !empty( $po_tgl_approved_hp )   )
            					 			{
            					 				$appA= BOD_RT;
				              					$appB= BOD_HP;
				              					$end_appA = ucwords($appA);
				              					$end_appB = ucwords($appB);
				              					echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'> Process By</div> 
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;>$end_appA dan $end_appB</div><br>
				              					<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
				              					<div style=float:left;> $po_tgl_approved_rt dan $po_tgl_approved_hp</div><br>                            
				              					</div>";	
				              				} 
				              				else if ( !is_null( $po_tgl_approved_dl ) || !empty( $po_tgl_approved_dl )   )
				              				{
				              					$appA= BOD_RT;
				              					$appB= BOD_DL;
				              					$end_appA = ucwords($appA);
				              					$end_appB = ucwords($appB);
				              					echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'> Process By</div> 
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;>$end_appA dan $end_appB</div><br>
				              					<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
				              					<div style=float:left;> $po_tgl_approved_rt dan $po_tgl_approved_dl</div><br>                            
				              					</div>";	
				              				} 
				              				else { 
				              					$yang_approv=BOD_RT;
				              					$end_yang_app = ucwords($yang_approv);
				              					echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'>Process By</div> <div style='float:left;margin-left:10px;margin-right:10px;'> : </div> <div style=float:left;>$end_yang_app</div> <br>
				              					<div style='float:left; width:75px;'> Tanggal</div> <div style='float:left; margin-left:10px;margin-right:10px;'>:</div> <div style=float:left;>$po_tgl_approved_rt</div> </div>";
				              				}
				              			}   else  { 
				              				if ( (!is_null( $po_tgl_approved_hp ) || !empty( $po_tgl_approved_hp )) && (!is_null( $po_tgl_approved_dl ) || !empty( $po_tgl_approved_dl ))  )
				              				{
				              					$appA= BOD_HP;
				              					$appB= BOD_DL;
				              					$end_appA = ucwords($appA);
				              					$end_appB = ucwords($appB);
				              					echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'> Process By</div> 
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;>$end_appA dan $end_appB</div><br>
				              					<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
				              					<div style=float:left;> $po_tgl_approved_hp dan $po_tgl_approved_dl</div><br>                            
				              					</div>";	
				              				} else { 
				              	?>

				              	<?php 
				              				}
				              			} 
				              		} 
				              		else if ($user==BOD_HP) 
				              		{
				              			if (!is_null( $po_tgl_approved_hp ) || !empty( $po_tgl_approved_hp ))
				              			{
				              				if (!is_null( $po_tgl_approved_dl ) || !empty( $po_tgl_approved_dl ))
				              				{
				              					$appA= BOD_HP;
				              					$appB= BOD_DL;
				              					$end_appA = ucwords($appA);
				              					$end_appB = ucwords($appB);
				              					echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'> Process By</div> 
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;>$end_appA dan $end_appB</div><br>
				              					<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
				              					<div style=float:left;> $po_tgl_approved_hp dan $po_tgl_approved_dl</div><br>                            
				              					</div>";	
				              				}  else {  
				              					echo'<div class="alerts"><b>Data pengajuan PO ini telah selesai di proses</b></div>';
				              				}
				              			} else { 
				              				if (!is_null( $po_tgl_approved_rt ) || !empty( $po_tgl_approved_rt ))
				              				{
				              					$yang_approv=BOD_RT;
				              					$end_yang_app = ucwords($yang_approv);
				              					echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'>Process By</div>
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;> $end_yang_app</div> <br>
				              					<div style='float:left; width:75px;'> Tanggal</div> 
				              					<div style='float:left; margin-left:10px;margin-right:10px;'>:</div>
				              					<div style=float:left;> $po_tgl_approved_rt</div> </div>";
				              				} else if (!is_null($po_tgl_approved_dl) || !empty($po_tgl_approved_dl)) {

				              					$yang_approv=BOD_DL;
				              					$end_yang_app = ucwords($yang_approv);
				              					echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'>Process By</div>
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;> $end_yang_app</div> <br>
				              					<div style='float:left; width:75px;'> Tanggal</div> 
				              					<div style='float:left; margin-left:10px;margin-right:10px;'>:</div>
				              					<div style=float:left;> $po_tgl_approved_dl</div> </div>";
				              	?>
				              					
								<?php

				              				} else {
				              	?>

                 				<?php   
                 							} 
                 						}
		            				} 
		            				else if ($user==BOD_DL)  
		            				{
		            					if (!is_null( $po_tgl_approved_dl ) || !empty( $po_tgl_approved_dl ))
		            					{
		            						if ( !is_null( $po_tgl_approved_hp ) || !empty( $po_tgl_approved_hp ) )
		            						{		            							
		            							$appA= BOD_HP;
		            							$appB= BOD_DL;
		            							$end_appA = ucwords($appA);
		            							$end_appB = ucwords($appB);
		            							echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
		            							<div style='float:left; width:75px;'> Process By</div> 
		            							<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
		            							<div style=float:left;>$end_appA dan $end_appB</div><br>
		            							<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
		            							<div style=float:left;> $po_tgl_approved_hp dan $po_tgl_approved_dl</div><br>          
		            							</div>";	
		            						} else {
		            							echo'<div class="alerts"><b>Data pengajuan PO ini telah selesai di proses</b></div>';
		            						}
		            					} else { 
		            						if (!is_null( $po_tgl_approved_rt ) || !empty( $po_tgl_approved_rt ))
		            						{
		            							$yang_approv=BOD_RT;
		            							$end_yang_app = ucwords($yang_approv);
		            							echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai di proses</b><br>
		            							<div style='float:left; width:75px;'>Process By</div> 
		            							<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
		            							<div style=float:left;>$end_yang_app</div> <br>
		            							<div style='float:left; width:75px;'> Tanggal</div> 
		            							<div style='float:left; margin-left:10px;margin-right:10px;'>:</div> 
		            							<div style=float:left;>$po_tgl_approved_rt</div> </div>";
		            						} else if (!is_null($po_tgl_approved_hp) || !empty($po_tgl_approved_hp)) {

		            							$yang_approv=BOD_HP;
		            							$end_yang_app = ucwords($yang_approv);
		            							echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai di proses</b><br>
		            							<div style='float:left; width:75px;'>Process By</div> 
		            							<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
		            							<div style=float:left;>$end_yang_app</div> <br>
		            							<div style='float:left; width:75px;'> Tanggal</div> 
		            							<div style='float:left; margin-left:10px;margin-right:10px;'>:</div> 
		            							<div style=float:left;>$po_tgl_approved_hp</div> </div>";
		            			?>
	                        					<tr>
	                        						<td colspan="11">
	                        							<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')">
	                        						</td> 
	                        					</tr>
		            			<?php
		            						}
		              					}
								?>
												<tr>
									                <td colspan="11">
	                        							<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')"
														<?php 
															if (!empty($po_tgl_approved_dl)) {
																echo 'style="display:none;"';
															}
														?>
	                        							>
	                        						</td> 
												</tr>
								<?php
										
									}
            					} 
								else 
								{
            					 	if ($user==BOD_RT) 
            					 	{
            					 		if (!is_null( $po_tgl_approved_rt ) || !empty( $po_tgl_approved_rt ))
            					 		{
            					 			if ( !is_null( $po_tgl_approved_hp ) || !empty( $po_tgl_approved_hp )   )
            					 			{
            					 				$appA= BOD_RT;
				              					$appB= BOD_HP;
				              					$end_appA = ucwords($appA);
				              					$end_appB = ucwords($appB);
				              					echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'> Process By</div> 
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;>$end_appA dan $end_appB</div><br>
				              					<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
				              					<div style=float:left;> $po_tgl_approved_rt dan $po_tgl_approved_hp</div><br>                            
				              					</div>";	
				              				} 
				              				else if ( !is_null( $po_tgl_approved_dl ) || !empty( $po_tgl_approved_dl )   )
				              				{
				              					$appA= BOD_RT;
				              					$appB= BOD_DL;
				              					$end_appA = ucwords($appA);
				              					$end_appB = ucwords($appB);
				              					echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'> Process By</div> 
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;>$end_appA dan $end_appB</div><br>
				              					<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
				              					<div style=float:left;> $po_tgl_approved_rt dan $po_tgl_approved_dl</div><br>                            
				              					</div>";	
				              				} 
				              				else { 
				              					$yang_approv=BOD_RT;
				              					$end_yang_app = ucwords($yang_approv);
				              					echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'>Process By</div> <div style='float:left;margin-left:10px;margin-right:10px;'> : </div> <div style=float:left;>$end_yang_app</div> <br>
				              					<div style='float:left; width:75px;'> Tanggal</div> <div style='float:left; margin-left:10px;margin-right:10px;'>:</div> <div style=float:left;>$po_tgl_approved_rt</div> </div>";
				              				}
				              			}   else  { 
				              				if ( (!is_null( $po_tgl_approved_hp ) || !empty( $po_tgl_approved_hp )) && (!is_null( $po_tgl_approved_dl ) || !empty( $po_tgl_approved_dl ))  )
				              				{
				              					$appA= BOD_HP;
				              					$appB= BOD_DL;
				              					$end_appA = ucwords($appA);
				              					$end_appB = ucwords($appB);
				              					echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'> Process By</div> 
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;>$end_appA dan $end_appB</div><br>
				              					<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
				              					<div style=float:left;> $po_tgl_approved_hp dan $po_tgl_approved_dl</div><br>                            
				              					</div>";	
				              				} else { 
				              	?>
				              	<?php 
				              				} 
				              			} 
				              		} 
				              		else if ($user==BOD_HP) 
				              		{
				              			if (!is_null( $po_tgl_approved_hp ) || !empty( $po_tgl_approved_hp ))
				              			{
				              				if (!is_null( $po_tgl_approved_dl ) || !empty( $po_tgl_approved_dl ))
				              				{
				              					$appA= BOD_HP;
				              					$appB= BOD_DL;
				              					$end_appA = ucwords($appA);
				              					$end_appB = ucwords($appB);
				              					echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'> Process By</div> 
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;>$end_appA dan $end_appB</div><br>
				              					<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
				              					<div style=float:left;> $po_tgl_approved_hp dan $po_tgl_approved_dl</div><br>                            
				              					</div>";	
				              				}  else {  
				              					// echo'<div class="alerts"><b>Data pengajuan PO ini telah selesai di proses</b></div>';
				              					echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai anda proses</b><br>
				              					<div style='float:left; width:75px;'> Tanggal</div> 
				              					<div style='float:left; margin-left:10px;margin-right:10px;'>:</div> 
				              					<div style=float:left;>$po_tgl_approved_hp</div></div>";
				              				}
				              			} else { 
				              				if (!is_null( $po_tgl_approved_rt ) || !empty( $po_tgl_approved_rt ))
				              				{
				              					$yang_approv=BOD_RT;
				              					$end_yang_app = ucwords($yang_approv);
				              					echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'>Process By</div>
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;> $end_yang_app</div> <br>
				              					<div style='float:left; width:75px;'> Tanggal</div> 
				              					<div style='float:left; margin-left:10px;margin-right:10px;'>:</div>
				              					<div style=float:left;> $po_tgl_approved_rt</div> </div>";
				              				} else if (!is_null($po_tgl_approved_dl) || !empty($po_tgl_approved_dl)) {

				              					$yang_approv=BOD_DL;
				              					$end_yang_app = ucwords($yang_approv);
				              					echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'>Process By</div>
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;> $end_yang_app</div> <br>
				              					<div style='float:left; width:75px;'> Tanggal</div> 
				              					<div style='float:left; margin-left:10px;margin-right:10px;'>:</div>
				              					<div style=float:left;> $po_tgl_approved_dl</div> </div>";
				              	?>

				              	<?php
				              				} else {
				              	?>

                 				<?php   
                 							} 
                 						}
		            				} 
		            				else if ($user==BOD_DL)  
		            				{
		            					if (!is_null( $po_tgl_approved_dl ) || !empty( $po_tgl_approved_dl ))
		            					{
		            						if ( !is_null( $po_tgl_approved_hp ) || !empty( $po_tgl_approved_hp ) )
		            						{		            							
		            							$appA= BOD_HP;
		            							$appB= BOD_DL;
		            							$end_appA = ucwords($appA);
		            							$end_appB = ucwords($appB);
		            							echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
		            							<div style='float:left; width:75px;'> Process By</div> 
		            							<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
		            							<div style=float:left;>$end_appA dan $end_appB</div><br>
		            							<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
		            							<div style=float:left;> $po_tgl_approved_hp dan $po_tgl_approved_dl</div><br>          
		            							</div>";	
		            						} else {
		            							echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai anda proses</b><br>
		            							<div style='float:left; width:75px;'> Tanggal</div> 
		            							<div style='float:left; margin-left:10px;margin-right:10px;'>:</div> 
		            							<div style=float:left;>$po_tgl_approved_dl</div> </div>";
		            						}
		            					} else { 
		            						if (!is_null( $po_tgl_approved_rt ) || !empty( $po_tgl_approved_rt ))
		            						{
		            							$yang_approv=BOD_RT;
		            							$end_yang_app = ucwords($yang_approv);
		            							echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai di proses</b><br>
		            							<div style='float:left; width:75px;'>Process By</div> 
		            							<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
		            							<div style=float:left;>$end_yang_app</div> <br>
		            							<div style='float:left; width:75px;'> Tanggal</div> 
		            							<div style='float:left; margin-left:10px;margin-right:10px;'>:</div> 
		            							<div style=float:left;>$po_tgl_approved_rt</div> </div>";
		            						} else if (!is_null($po_tgl_approved_hp) || !empty($po_tgl_approved_hp)) {

		            							$yang_approv=BOD_HP;
		            							$end_yang_app = ucwords($yang_approv);
		            							echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai di proses</b><br>
		            							<div style='float:left; width:75px;'>Process By</div> 
		            							<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
		            							<div style=float:left;>$end_yang_app</div> <br>
		            							<div style='float:left; width:75px;'> Tanggal</div> 
		            							<div style='float:left; margin-left:10px;margin-right:10px;'>:</div> 
		            							<div style=float:left;>$po_tgl_approved_hp</div> </div>";
		            			?>

		            			<?php
		            						} else {
								?>
								<?php
											}
										} 
									} 
								}
								?>
								<!-- end of validation processed or not -->
							</tbody>
						</table>
					</div>
						<!-- tombol sumbit gadget -->
						<?php 
						if ($user == BOD_RT) {
							if (!empty($po_tgl_approved_rt)) {
								echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
							} else {
								if (!empty($po_tgl_approved_hp)) {
									if ($po_approve_by_hp==0) {
										echo '<label class="" style="margin:20px;"><input type="checkbox" id="check_all">Approve All</label>';
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
									}
								}else if (!empty($po_tgl_approved_dl)){
									if ($po_approve_by_hp==0) {
										echo '<label class="" style="margin:20px;"><input type="checkbox" id="check_all">Approve All</label>';
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
									}
								} else {
										echo '<label class="" style="margin:20px;"><input type="checkbox" id="check_all">Approve All</label>';
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit">';
									}
							}
						} else if ($user == BOD_HP){
							if (!empty($po_tgl_approved_rt)) {
								echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
							} else {
								if (!empty($po_tgl_approved_hp)) {
									if ($po_approve_by_hp==0) {
										echo '<label class="" style="margin:20px;"><input type="checkbox" id="check_all">Approve All</label>';
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
									}
								}else if (!empty($po_tgl_approved_dl)){
									if ($po_approve_by_dl==0) {
										echo '<label class="" style="margin:20px;"><input type="checkbox" id="check_all">Approve All</label>';
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
									}else{
										echo '<label class="" style="margin:20px;"><input type="checkbox" id="check_all">Approve All</label>';
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit">';
									}
								} else {
									echo '<label class="" style="margin:20px;"><input type="checkbox" id="check_all">Approve All</label>';
									echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit">';
								}
							}
						} else if ($user == BOD_DL){
							if (!empty($po_tgl_approved_rt)) {
								echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
							} else {
								if (!empty($po_tgl_approved_dl)) {
									if ($po_approve_by_dl==0) {
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
										echo '<label class="" style="margin:20px;"><input type="checkbox" id="check_all">Approve All</label>';
									}
								}else if (!empty($po_tgl_approved_hp)){
									if ($po_approve_by_hp==0) {
										echo '<label class="" style="margin:20px;"><input type="checkbox" id="check_all">Approve All</label>';
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
									} else {
										echo '<label class="" style="margin:20px;"><input type="checkbox" id="check_all">Approve All</label>';
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit">';
									}
								} else {
									echo '<label class="" style="margin:20px;"><input type="checkbox" id="check_all">Approve All</label>';
									echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit">';
								}
							}
						}
						?>
						<!-- <input type="submit" name="submit" value="submit"  onClick="return confirm('Anda sudah yakin?')"/> -->
				</form>
			</div>
		</div>
	</div>
	<?php 
	// echo $user;
		} 
	}
	?>
	<!-- <input type="checkbox" id="select_all"> -->
	<footer class="footer" box-shadow: 1px 1px 6px rgb(150, 148, 148);>
      <div class="container" style="background-color:white;text-align:center;">
		<a href="<?php echo "http://report.hts.net.id/approval/ppo_approval_desktop.php?u=$u&p=$p&k=$k"; ?>">Desktop Version</a>
        <p class="text-muted">Copyright &copy; 2016 PT. Hawk Teknologi Solusi, All Rights Reserved.</p>
      </div>
    </footer>
</body>
</html>
<script>
function startTime()
{
	var today=new Date();
		 h=today.getHours()
		 m=today.getMinutes()
		 s=today.getSeconds()
		 ap="AM";

	//to add AM or PM after time
	if(h>11) ap="PM";
	if(h>12) h=h-12;
	if(h==0) h=12;

	//to add a zero in front of numbers<10
	m=checkTime(m)
	s=checkTime(s)

	document.getElementById('clock').innerHTML=h+":"+m+":"+s+" "+ap
	t=setTimeout('startTime()', 500)
}
function checkTime(i)
{
	if (i<10)
	{ i="0" + i}
	return i
}

window.onload=startTime;
function formatDate(date) {
	var year = date.getFullYear(),
		month = date.getMonth() + 1, // months are zero indexed
		month = month < 10 ? "0" + month : month,
		day = date.getDate(),
		hour = date.getHours(),
		minute = date.getMinutes(),
		second = date.getSeconds(),
		hourFormatted = hour % 12 || 12, // hour returned in 24 hour format
		minuteFormatted = minute < 10 ? "0" + minute : minute,
		morning = hour < 12 ? " am" : " pm";

		return year + "-" + month + "-" + day + " " + hourFormatted + ":" +
		minuteFormatted + ":" + second;
	}
	function check(cb, test)
	{
		if (cb.checked) {
			document.getElementById( test ).value = formatDate(new Date ());
		}
		else
			document.getElementById( test ).value = "";
	}
	function check2(cb2, test2)
	{
		if (cb2.checked) {
			document.getElementById( test2 ).value = '1';	
		}
		else
			document.getElementById( test2 ).value = "0";
	}
	function check3(cb3, test3)
	{
		if (cb3.checked) {
			document.getElementById( test3 ).value = '1';				
		}
		else
			document.getElementById( test3 ).value = "0";
	}
	function check4(cb4, test4)
	{
		if (cb4.checked) {
			document.getElementById( test4 ).value = '1';		
		}
		else
			document.getElementById( test4 ).value = "0";
	}
	function check33(cb33, test33)
	{
		if (cb33.checked) {
			document.getElementById( test33 ).value = 'y';
		}
		else
			document.getElementById( test33 ).value = "";
	}
	function check44(cb44, test44)
	{
		if (cb44.checked) {
			document.getElementById( test44 ).value = 'y';
		}
		else
			document.getElementById( test44 ).value = "";
	}
	function check22(cb22, test22)
	{
		if (cb22.checked) {
			document.getElementById( test22 ).value = 'y';
		}
		else
			document.getElementById( test22 ).value = "";
	}
	$(document).ready(function()
	{
          //hide all contents
          $('p[id^=sb]').hide();

          $('input[id^=chk]').change(function(){

          // get checkbox index
          var index = $(this).attr('id').replace('chk','');
          //show respective contents
          if($(this).is(':checked'))
          	$('#sb'+index).slideToggle();
          else
          	$('#sb'+index).slideToggle();
      });

      });

//select all function
$("#check_all").change(function(){  //"select all" change 
    var status = this.checked; // "select all" checked status
    $('.cb-gadget').each(function(){ //iterate all listed checkbox items
        this.checked = status; //change ".checkbox" checked status
        var d = formatDate (new Date());
        $("#CBX").val(d);
        $(".datetime").val(d);
        $(".approved").val('1');
        $(".xy").val('y');
    });
});
$("#check_all").change(function(){ //".checkbox" change 
	if(this.checked == false){ //if this item is unchecked
		var o='';
        $("#check_all")[0].checked = false; //change "select all" checked status to false
        $('#CBX').val(o);
        $(".datetime").val(o);
        $(".approved").val(0);
        $(".xy").val(o);
    }
});
//uncheck "select all", if one of the listed checkbox item is unchecked
$('.cb-gadget').change(function(){ //".checkbox" change 
	var o='';
    $('#CBX').val('');
    if(this.checked == false){ //if this item is unchecked
        $("#check_all")[0].checked = false; //change "select all" checked status to false
        $('#CBX').val('');
    }
});
// $(document).ready(function(){
// 	$(window).on('load', function(){
// 	    var win = $(this);
// 	    if (win.width() < 940) { 
// 	    	$(':input .tgl').addClass('gad');
// 	    } else {
// 	        $(':input .tgl').removeClass('gad');
// 	    }
// 	});
// });
// $(window).on('resize', function(){
// 	var win = $(this);
// 	if (win.width() < 940) { 
// 		$('input .tgl').addClass('.gad');
// 	}
// 	else
// 	{
// 		$('input').remove('gad');
// 	}

// });
$('.checkbox-md').on('click',function(){
    $("#myModal").modal("show");
    $("#ppo_number").val($(this).closest('tr').children()[1].textContent);
    $("#tanggal_po").val($(this).closest('tr').children()[3].textContent);
    $("#nama_vendor").val($(this).closest('tr').children()[2].textContent);
});

$(document).ready(function(){
	$(window).on('load', function(){
	    var win = $(this);
	    if (win.width() > 939) { 
	    	$('#tabel').addClass('table-responsive');
	    } else {
	        $('#tabel').removeClass('table-responsive');
	        $('#tabel').addClass('asd');
	    }
	});
});
if (fakewaffle === undefined) {
    var fakewaffle = {};
}

fakewaffle.responsiveTabs = function (collapseDisplayed) {
    "use strict";
    fakewaffle.currentPosition = 'tabs';

    var tabGroups = $('.nav-tabs.responsive'),
    hidden    = '',
    visible   = '';

    if (collapseDisplayed === undefined) {
        collapseDisplayed = ['xs', 'sm'];
    }

    $.each(collapseDisplayed, function () {
        hidden  += ' hidden-' + this;
        visible += ' visible-' + this;
    });

    $.each(tabGroups, function () {
        var $tabGroup   = $(this),
        tabs        = $tabGroup.find('li a'),
        collapseDiv = $("<div></div>", {
            "class" : "panel-group responsive" + visible,
            "id"    : 'collapse-' + $tabGroup.attr('id')
        });

        $.each(tabs, function () {
            var $this          = $(this),
            active         = '',
            oldLinkClass   = $this.attr('class') === undefined ? '' : $this.attr('class'),
            newLinkClass   = 'accordion-toggle',
            oldParentClass = $this.parent().attr('class') === undefined ? '' : $this.parent().attr('class'),
            newParentClass = 'panel panel-default';

            if (oldLinkClass.length > 0) {
                newLinkClass += ' ' + oldLinkClass;
            };

            if (oldParentClass.length > 0) {
                oldParentClass = oldParentClass.replace(/\bactive\b/g, '');
                newParentClass += ' ' + oldParentClass;
                newParentClass = newParentClass.replace(/\s{2,}/g, ' ');
                newParentClass = newParentClass.replace(/^\s+|\s+$/g, '');
            };

            if ($this.parent().hasClass('active')) {
                active = ' in';
            }

            collapseDiv.append(
                $('<div>').attr('class', newParentClass).html(
                    $('<div>').attr('class', 'panel-heading').html(
                        $('<h4>').attr('class', 'panel-title').html(
                            $('<a>', {
                                'class' : newLinkClass,
                                'data-toggle': 'collapse',
                                'data-parent' : '#collapse-' + $tabGroup.attr('id'),
                                'href' : '#collapse-' + $this.attr('href').replace(/#/g, ''),
                                'html': $this.html()
                            })
                            )
                        )
                    ).append(
                    $('<div>', {
                        'id' : 'collapse-' + $this.attr('href').replace(/#/g, ''),
                        'class' : 'panel-collapse collapse' + active
                    }).html(
                    $('<div>').attr('class', 'panel-body').html('')
                    )
                    )
                    );
});

$tabGroup.next().after(collapseDiv);
$tabGroup.addClass(hidden);
$('.tab-content.responsive').addClass(hidden);
});

fakewaffle.checkResize();
fakewaffle.bindTabToCollapse();
};

fakewaffle.checkResize = function () {
    "use strict";
    if ($(".panel-group.responsive").is(":visible") === true && fakewaffle.currentPosition === 'tabs') {
        fakewaffle.toggleResponsiveTabContent();
        fakewaffle.currentPosition = 'panel';
    } else if ($(".panel-group.responsive").is(":visible") === false && fakewaffle.currentPosition === 'panel') {
        fakewaffle.toggleResponsiveTabContent();
        fakewaffle.currentPosition = 'tabs';
    }

};

fakewaffle.toggleResponsiveTabContent = function () {
    "use strict";
    var tabGroups = $('.nav-tabs.responsive');

    $.each(tabGroups, function () {
        var tabs = $(this).find('li a');

        $.each(tabs, function () {
            var href         = $(this).attr('href').replace(/#/g, ''),
            tabId        = "#" + href,
            panelId      = "#collapse-" + href,
            tabContent   = $(tabId).html(),
            panelContent = $(panelId + " div:first-child").html();

            $(tabId).html(panelContent);
            $(panelId + " div:first-child").html(tabContent);
        });

    });
};

fakewaffle.bindTabToCollapse = function () {
    "use strict";
    var tabs     = $('.nav-tabs.responsive').find('li a'),
    collapse = $(".panel-group.responsive").find('.panel-collapse');

    tabs.on('shown.bs.tab', function (e) {
        var $current  = $($(e.target)[0].hash.replace(/#/, '#collapse-'));
        $current.collapse('show');

        if(e.relatedTarget){
            var $previous = $($(e.relatedTarget)[0].hash.replace(/#/, '#collapse-'));
            $previous.collapse('hide');
        }
    });

    collapse.on('show.bs.collapse', function (e) {
        var current = $(e.target).context.id.replace(/collapse-/g, '#');

        $('a[href="' + current + '"]').tab('show');
    });
}

$(window).resize(function () {
    "use strict";
    fakewaffle.checkResize();
});
(function($) {
    fakewaffle.responsiveTabs(['xs', 'sm']);
})(jQuery);


// window.onload=responsive;
</script>