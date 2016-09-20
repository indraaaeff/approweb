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
		else {
			if (isset($_GET['s']) && ($_GET['s'] == 1)) {
				$by =$row['submit_by'];
				$tgl_pengajuan=$row['tgl_pengajuan'];
				$ex=$row['expiration'];
				$key = $row['key_param'];
			}
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
   

	echo '<div class="kotak_po select_ppo">';

		$List_PPO = $Database->query( "Select distinct a.no_ppo, a.submit_by, a.tgl_pengajuan, a.tgl_approved_rt,
			a.tgl_approved_hp, a.tgl_approved_dl
			From vendor_ppo a, vendor_invoice b
			Where (a.no_ppo = b.no_ppo) and !( b.po_approved )
			And (b.status_data not in (0, 9)) ;
			Group by a.no_ppo Order by a.no_ppo" );

		if ($List_PPO && ($List_PPO->num_rows > 0)) {
			echo '<div class="col-md-6 nopadding">';
			echo '<form class="form-inline">';
			// echo '<div class="form-group">';
			echo '<input type="hidden" name="u" value="' . $_GET['u'] . '">';
			echo '<label for="ppo">&nbsp;&nbsp;No Pengajuan : &nbsp;</label>';
			echo '<select name="p" class="form-control" onchange="this.form.submit();">';
				while ($row = $List_PPO->fetch_assoc()) {
					if ($row['no_ppo'] == $PPO_Number) {
						echo '<option value="' . $row['no_ppo'] . '" selected="selected">' . $row['no_ppo'] . '</option>';
					}
					else {
						echo '<option value="' . $row['no_ppo'] . '">' . $row['no_ppo'] . '</option>';
					}
				}
			echo '</select>';
			echo '<input type="hidden" name="k" value="$key">';
			echo '<input type="hidden" name="s" value="1">';
			// echo '</div>';
			echo '</form>';
			echo '</div>';

			$List_PPO->free();
		}
 		// 	<div class="col-md-6">
		// 	<form action="" class="form-horizontal">
		// 		<div class="form-group">
		// 			<label class="control-label col-sm-2 select_ppo" for="ppo">Nomor PPO :</label>
		// 			<div class="col-md-10">
		// 				<select name="" class="form-control" id="">
		// 					<option value="">asd1</option>
		// 					<option value="">asd2</option>
		// 					<option value="">asd3</option>
		// 					<option value="">asd4</option>
		// 				</select>
		// 			</div>
		// 		</div>
		// 	</form>
		// </div>
	echo '</div>';
    
	if (isset($_GET['p'])) {
	//$user=BOD_RT;
		$PPO_TableDetail = $Database->query( "Call GetPPO_Detail( '$PPO_Number' )" );
			if ($PPO_TableDetail && ($PPO_TableDetail->num_rows > 0)) {
	?>

	<div class="kotak_po" id="ppo_table">
		<div class="panel panel-default">
			<div class="panel-heading nopadding" style="width:100%; !important">
				<div class="po_head">
					<table class="table nopadding" style="margin-bottom:0px;height: 45px; vertical-align: middle;">
						<tbody>
							<tr>
								<!-- <td class="middling"><b>No Pengajuan : <?php echo $PPO_Number; ?></b></td> -->
								<td class="middling" style="width:40%;"><b>Tanggal : <?php echo $tgl_pengajuan;?></b></td>
								<td class="middling" style="width:40%;"><b>Submitted By : <?php echo $by;?></b></td>
								<td class="middling" style="width:20%;"><b><?php $total_ppo=$PPO_TableDetail->num_rows; echo "Total PO : $total_ppo";?></b></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="hidden_po">
				 	<b class="head-mob">No Pengajuan : <?php echo $PPO_Number; ?><br></b>
					<b class="head-mob">Tanggal :  <?php echo $tgl_pengajuan;?><br></b>
					<b class="head-mob">Submitted By <?php echo $by;?><br></b>
					<b class="head-mob">
						<?php 
							$total_ppo=$PPO_TableDetail->num_rows; echo "Total PO : $total_ppo";
						?>
					</b>
				</div>
			</div>
			<div class="panel-body">
				<form method="post" action="post.php">
					<div id="tabel" class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="tabele">
							<thead class="table_po">
								<tr style="height: 45px;vertical-align: middle;">
									<th class="tengah middling">No.</th>
									<th class="tengah middling">NO PO</th>
									<th class="tengah middling">NAMA VENDOR</th>
									<th class="tengah middling">TANGGAL PO</th>
									<th class="tengah middling">PPN</th>
									<th class="tengah middling">TOTAL</th>
									<th class="tengah middling">RT</th>
									<th class="tengah middling">HP</th>
									<th class="tengah middling">DL</th>
									<th class="tengah middling">STATUS</th>
									<th class="tengah middling">NOTE </th>
									<!-- <?php echo $user; ?> -->
								</tr>
							<?php 
								//modal for detail po
								include "modal_po.php";
							?>
							</thead>
								<?php
								$no = +1;
								$grand= 0;
								$total_ppo_rejek=0;
								while ($row = $PPO_TableDetail->fetch_assoc()) {
									$no_po = $row['no_po'];
							?>
							<tbody class="desktop">
								<!-- <div><a href="#mymodal" data-toggle="modal" id="<?php echo $no_po; ?>" data-target="#modal_detail"><?php echo "ppo ke ".$no; ?></a></div> -->
								<input type="hidden" name="key" value="<?php echo $key;?>">
								<input type="hidden" name="u" value="<?php echo $_GET['u'];?>">
								<input type="hidden" name="total_ppo" id="total_ppo" value="<?php echo $total_ppo;?>"	>
								<input type="hidden" name="tgl_pengajuan" value="<?php echo $tgl_pengajuan;?>">	
								<!-- loop for row details -->
								<?php 
									// variabel vendor_invoice
								?>

								<tr align="center" id="detail_column">
									<?php $Tanggal_po = date( 'd-m-Y', strtotime( $row['tgl_po'] )); ?>
            						<td class="table_padd"><?php echo $no;?></td>
            						<!-- <td id="<?php echo $no_po; ?>" class="nomor_po" value="<?php echo $no_po; ?>" onchange="enableTxt(this)"><?php echo $no_po;?></td> -->
            						<td class="table_padd"><a href="#mymodal" data-toggle="modal" id="<?php echo $no_po; ?>" data-target="#modal_detail" class="po_modal"><?php echo $no_po; ?></a></td>
            						<td class="table_padd"><?php echo $row['nama_vendor'];?></td>
            						<td class="table_padd"><?php echo  $Tanggal_po;?></td>
            						<!-- <td style="display:none;"><?php echo $row['comment_rt'] ?></td> -->
            						<?php 
	            						if ($row['non_ppn'] == 0)
	            							{ $ppn='ya';
	            							echo '<td align="center">Ya</td>';
	            							echo "<input type='hidden' name='ppn' value='$ppn'>";
	            						} else {
	            							$ppn='tidak';
	            							echo '<td align="center">Tidak</td>';
	            							echo "<input type='hidden' name='ppn' value='$ppn'>";
	            						}
            						?>
            						<td class="table_padd"><?php echo number_format( $row['total'] );?></td>

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
            						
            						// $po_tgl_approved_hp = "2/6/2016";
            						// $po_tgl_approved_dl = "3/6/2016";
            						// $po_approve_by_hp=1;
            						// $po_approve_by_dl=1;
            						// $po_tgl_approved_rt = "4/6/2016";
            						// $po_approve_by_rt=1;
            						?>
            						
            						<?php 
            							$grand += $row['total'];
            						?>
            						<!-- BOD_RT Session CHECKBOX  -->
            						<?php 
										// $po_approve_by_hp = 1;
            						// $po_approve_by_dl = 1;
            						// $po_tgl_approved_hp = 'rejected';
            						// $po_approve_by_rt =1;
            						// $po_tgl_approved_rt =1;
            							if ($user == BOD_RT)
            							{
            						?>
            						<td class="table_padd">
            							<?php 
            							// $po_tgl_approved_rt = "2/6/2016";
            							// $po_approve_by_rt = 1;
            							// $po_comment_rt = "ogah";

            								//dummy data for checking checkbox
            								if (empty($po_tgl_approved_rt)){
            									if (!empty($po_tgl_approved_hp) && !empty($po_tgl_approved_dl)) {
	            									if($po_approve_by_hp==1 && $po_approve_by_dl==1) {
	            										echo '<input type="checkbox" disabled>';
		            								} else if($po_approve_by_hp==1 && $po_approve_by_dl==0) {
		            									echo '<input type="checkbox" disabled>';
		            								} else if($po_approve_by_hp==0 && $po_approve_by_dl==1){
		            									echo '<input type="checkbox" disabled>';
		            								} else {
		            									echo '<input type="checkbox" disabled>';
		            								}
            									}else if (!empty($po_tgl_approved_dl)){
            										if ($po_approve_by_dl==0) {
            											echo '<input type="checkbox" disabled>';
            										} else {
            							?>
            											<input type="checkbox" class="tanggal checkbox-md" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" />
            							<?php 				
            										}
            									}else if (!empty($po_tgl_approved_hp)){
            										if ($po_approve_by_hp==0) {
            											echo '<input type="checkbox" disabled>';
            										} else {
            							?>
            											<input type="checkbox" class="tanggal checkbox-md" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" />
            							<?php 
            										}
            									} else {
	            						?>	
	            							<input type="checkbox" class="tanggal checkbox-md" name="tanggal " id="chk<?php echo $no;?>" onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" />
	            							<!-- <input type="checkbox" name="approve_by_rt" onclick="check(this, 'date1');"> -->
	            						<?php
	            								}
	            						?>
											<!-- tambah variabel HP DAN DL di login RT -->
		            						<input  type="hidden"  name="po_tgl_app_hp[]" value="<?php echo $po_tgl_approved_hp;?>" >
		            						<input  type="hidden"  name="po_app_hp[]"     value="<?php echo $po_approve_by_hp;?>" >
		            						<input  type="hidden"  name="po_tgl_app_dl[]" value="<?php echo $po_tgl_approved_dl;?>" >
		            						<input  type="hidden"  name="po_app_dl[]"     value="<?php echo $po_approve_by_dl;?>" >
		            						<!-- <input  type="hidden"  name="po_comment_hp[]" value="<?php echo $po_comment_hp; ?>">
		            						<input  type="hidden"  name="po_comment_dl[]" value="<?php echo $po_comment_dl; ?>"> -->
											<!-- variabel RT -->
											<input type="hidden" name="user" id="<?php echo $user.$no; ?>" value="<?php echo $user; ?>">
	            							<input type="hidden" name="po_tgl_approved_rt[]" class="datetime" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_rt;?>" readonly="readonly">
	            							<input type="hidden" name="po_approve_by_rt[]" class='<?php if (empty($po_tgl_approved_rt)) { if (!empty($po_tgl_approved_hp) || !empty($po_tgl_approved_dl)) { if ($po_approve_by_hp==1 || $po_approve_by_dl==1) {echo "approved";} else {echo "rejected";}}else {echo "approved";}}?>' id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_rt;?>" readonly="readonly">
	            							<input type="hidden" name="no_po[]"  value="<?php echo $row['no_po'];?>">
	            							<input type="hidden" name="total[]"  value="<?php echo $row['total'];?>">
	            							<input type="hidden" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
	            							<input type="hidden" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
	            							<input type="hidden" name="x[]" class='<?php if (empty($po_tgl_approved_rt)) { if (!empty($po_tgl_approved_hp) || !empty($po_tgl_approved_dl)) { if ($po_approve_by_hp==1 || $po_approve_by_dl==1) {echo "xy";} else {echo "rejected";}}else {echo "xy";}}?>' id="x<?php echo $no;?>">
	            							<input type="hidden" name="no_ppo" value="<?php echo $PPO_Number;?>">
	            							<input type="hidden" name="sub_by" value="<?php echo $by;?>">
            						</td>
	            						<?php 
	            							} else {
	            								if (!empty($po_approve_by_rt) || $po_approve_by_rt==1) {
	            									if (!empty($po_tgl_approved_hp) && $po_approve_by_hp==0) {
	            										echo '<input type="checkbox" disabled >';
	            									} else if (!empty($po_tgl_approved_dl) && $po_approve_by_dl==0) {
	            										echo '<input type="checkbox" disabled >';
	            									} else {
		            									echo '<input type="checkbox" disabled checked>';
	            									}
	            								} else {
	            									if (!empty($po_tgl_approved_hp) && $po_approve_by_hp==0) {
	            										echo '<input type="checkbox" disabled >';
	            									} else if (!empty($po_tgl_approved_dl) && $po_approve_by_dl==0) {
	            										echo '<input type="checkbox" disabled >';
	            									} else {

	            									echo '<span class="glyphicon glyphicon-remove merah"></span>';
		            									// echo '<input type="checkbox" disabled checked>';
	            									}
	            								}
	            							}
	            						?>
            						<td class="table_padd">
            							<?php 
            								if (!empty($po_tgl_approved_hp)) {
            									if($po_approve_by_hp==0) 
            									{
            							?>
													<span class="glyphicon glyphicon-remove merah"></span>
            							<?php 
            									} else {
            							?>
            										<input type="checkbox" disabled checked>
            							<?php
            									}
            								} else {
            							?>
            										<input type="checkbox" disabled>
            							<?php 
            								}
            							?>
            						</td>
            						<td class="table_padd">
            							<?php 
            								if (!empty($po_tgl_approved_dl)) {
            									if($po_approve_by_dl==0) 
            									{
            							?>
													<span class="glyphicon glyphicon-remove merah"></span>
            							<?php 
            									} else {
            							?>
            										<input type="checkbox" disabled checked>
            							<?php
            									}
            								} else {
            							?>
            										<input type="checkbox" disabled>
            							<?php 
            								}
            							?>
            						</td>
	            					<td class="table_padd">
	            						<?php 
	            						if(!empty($po_tgl_approved_rt)){
	            							if($po_approve_by_rt==0){
	            								echo '<p class="status" style="color:red;font-weight:bold;">Rejected</p>';
	            							} else {
	            								if (!empty($po_tgl_approved_hp)) {
	            									if ($po_approve_by_hp==1) {
	            										echo '<p class="status" style="color:green;font-weight:bold;">Approved</p>';
	            									} else {
	            										echo '<p class="status" style="color:red;font-weight:bold;">Rejected</p>';
	            									}
	            								} else if (!empty($po_tgl_approved_dl)) {
	            									if ($po_approve_by_dl==1) {
	            										echo '<p class="status" style="color:green;font-weight:bold;">Approved</p>';
	            									} else {
	            										echo '<p class="status" style="color:red;font-weight:bold;">Rejected</p>';
	            									}
	            								} else {
	            									echo '<p class="status" style="color:green;font-weight:bold;">Approved</p>';
	            								}
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
	            					</td>
	            					<?php 
	            						if (!empty($po_tgl_approved_rt)) {
	            							if ($po_approve_by_rt==0) {
	            								$total_ppo_rejek++;
	            							}
	            						} else if (!empty($po_tgl_approved_hp)) {
	            							if ($po_approve_by_hp==0) {
	            								$total_ppo_rejek++;
	            							}
	            						} else if (!empty($po_tgl_approved_dl)) {
	            							if ($po_approve_by_dl==0) {
	            								$total_ppo_rejek++;
	            							}
	            						}
	            					?>
	            					<!-- BOD_HP Session CHECKBOX -->
	            					<?php 
	            						} else if ($user == BOD_HP) 
	            						{
	            					?>
	            					<td class="table_padd">
            							<?php 
            								if (!empty($po_tgl_approved_rt)) {
            									if ($po_approve_by_rt==1) {
            										
	            									if (!empty($po_tgl_approved_hp) && $po_approve_by_hp==0) {
	            										echo '<input type="checkbox" disabled >';
	            									} else if (!empty($po_tgl_approved_dl) && $po_approve_by_dl==0) {
	            										echo '<input type="checkbox" disabled >';
	            									} else {
		            									echo '<input type="checkbox" disabled checked>';
	            									}
	            								} else {
	            									if (!empty($po_tgl_approved_hp) && $po_approve_by_hp==0) {
	            										echo '<input type="checkbox" disabled >';
	            									} else if (!empty($po_tgl_approved_dl) && $po_approve_by_dl==0) {
	            										echo '<input type="checkbox" disabled >';
	            									} else {
		            									// echo '<input type="checkbox" disabled checked>';
	            									echo '<span class="glyphicon glyphicon-remove merah"></span>';
	            									}
	            								}
            								} else {
            									echo '<input type="checkbox" disabled >';
            								}
            							?>
	            					</td>
	            					<td class="table_padd">
	            						<?php 
            								if(!empty($po_tgl_approved_hp)){
            									if ($po_approve_by_hp==0) {
            										echo '<span class="glyphicon glyphicon-remove merah"></span>';
            									} else {
            										echo '<input type="checkbox" disabled checked>';
            									}
	            							} else if (!empty($po_tgl_approved_rt)){
	            								if ($po_approve_by_rt==0){
	            									echo '<input type="checkbox" disabled>';
	            								}else{
	            									echo '<input type="checkbox" disabled>';
	            								}
	            							} else if (!empty($po_tgl_approved_dl)){
	            								if ($po_approve_by_dl==1){
	            						?>
	            									<input type="checkbox" class="tanggal checkbox-md"  name="tanggal"  id="chk<?php echo $no;?>"
                                                    onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" />
                                        <?php
	            								} else {
	            									echo '<input type="checkbox" disabled>';
	            								}
	            							}else{
	            						?>
	            							<!-- <input type="checkbox" name="approve_by_hp"> -->
	            							<input type="checkbox" class="tanggal checkbox-md"  name="tanggal"  id="chk<?php echo $no;?>"
                                                    onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" />
	            							<?php 
	            							}
	            							?>
	            							<!-- tambah variabel RT DAN DL di login HP -->
	            							<input  type="hidden"  name="po_app_rt[]"     value="<?php echo $po_approve_by_rt;?>">
	            							<input  type="hidden"  name="po_tgl_app_rt[]" value="<?php echo $po_tgl_approved_rt;?>">
	            							<input  type="hidden"  name="po_app_dl[]"     value="<?php echo $po_approve_by_dl;?>">
	            							<input  type="hidden"  name="po_tgl_app_dl[]" value="<?php echo $po_tgl_approved_dl;?>">
	            							<!-- variabel HP -->
	            							<input type="hidden" name="user" id="<?php echo $user.$no; ?>" value="<?php echo $user; ?>" readonly>
	            							<input type="hidden" name="po_tgl_approved_hp[]" class="datetime" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_hp;?>" readonly>
	            							<input type="hidden" name="po_approve_by_hp[]" class='<?php if (empty($po_tgl_approved_hp)) { if (!empty($po_tgl_approved_dl)) { if ($po_approve_by_dl==1) {echo "approved";} else {echo "rejected";}}else {echo "approved";}}?>' id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_hp;?>" readonly>
	            							<input type="hidden" name="no_po[]"  value="<?php echo $row['no_po'];?>" readonly>
	            							<input type="hidden" name="total[]"  value="<?php echo $row['total'];?>" readonly>
	            							<input type="hidden" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>" readonly>
	            							<input type="hidden" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>" readonly>
	            							<input type="hidden" name="x[]" class='<?php if (empty($po_tgl_approved_hp)) { if (!empty($po_tgl_approved_dl)) { if ($po_approve_by_dl==1) {echo "xy";} else {echo "rejected";}}else {echo "xy";}}?>' id="x<?php echo $no;?>" readonly>
	            							<input type="hidden" name="no_ppo" value="<?php echo $PPO_Number;?>" readonly>
	            							<input type="hidden" name="sub_by" value="<?php echo $by;?>" readonly>
	            					
	            					</td>
	            					<td class="table_padd">
            							<?php 
            								if (!empty($po_tgl_approved_dl)) {
            									if($po_approve_by_dl==0) 
            									{
            							?>
													<span class="glyphicon glyphicon-remove merah"></span>
            							<?php 
            									} else {
            							?>
            										<input type="checkbox" disabled checked>
            							<?php
            									}
            								} else {
            							?>
            										<input type="checkbox" disabled>
            							<?php 
            								}
            							?>
	            					</td>
	            					<td class="table_padd">
	            						<?php 
	            						if(!empty($po_tgl_approved_rt)){
	            							if($po_approve_by_rt==0){
	            								echo '<p class="status" style="color:red;font-weight:bold;">Rejected</p>';
	            							} else {
	            								if (!empty($po_tgl_approved_hp)) {
	            									if ($po_approve_by_hp==1) {
	            										echo '<p class="status" style="color:green;font-weight:bold;">Approved</p>';
	            									} else {
	            										echo '<p class="status" style="color:red;font-weight:bold;">Rejected</p>';
	            									}
	            								} else if (!empty($po_tgl_approved_dl)) {
	            									if ($po_approve_by_dl==1) {
	            										echo '<p class="status" style="color:green;font-weight:bold;">Approved</p>';
	            									} else {
	            										echo '<p class="status" style="color:red;font-weight:bold;">Rejected</p>';
	            									}
	            								} else {
	            									echo '<p class="status" style="color:green;font-weight:bold;">Approved</p>';
	            								}
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
	            					</td>
	            					
	            					<?php 
	            						if (!empty($po_tgl_approved_rt)) {
	            							if ($po_approve_by_rt==0) {
	            								$total_ppo_rejek++;
	            							}
	            						} else if (!empty($po_tgl_approved_hp)) {
	            							if ($po_approve_by_hp==0) {
	            								$total_ppo_rejek++;
	            							}
	            						} else if (!empty($po_tgl_approved_dl)) {
	            							if ($po_approve_by_dl==0) {
	            								$total_ppo_rejek++;
	            							}
	            						}
	            					?>
	            					<!-- BOD_DL CHECKBOX -->
	            					<?php 
	            						} else if ($user == BOD_DL) 
	            						{
	            							// dummy data for checking DL checkbox
	            							// $po_approve_by_rt =1 ;
	            					?>
	            					<td class="table_padd">
		            					<?php 
		            					if (!empty($po_tgl_approved_rt)) {
		            						if ($po_approve_by_rt==1) {

		            							if (!empty($po_tgl_approved_hp) && $po_approve_by_hp==0) {
		            								echo '<input type="checkbox" disabled >';
		            							} else if (!empty($po_tgl_approved_dl) && $po_approve_by_dl==0) {
		            								echo '<input type="checkbox" disabled >';
		            							} else {
		            								echo '<input type="checkbox" disabled checked>';
		            							}
		            						} else {
		            							echo '<span class="glyphicon glyphicon-remove merah"></span>';
		            						}
		            					} else {
		            						echo '<input type="checkbox" disabled >';
		            					}
            							?>
	            					</td>
	            					<td class="table_padd">
            							<?php 
            								if (!empty($po_tgl_approved_hp)) {
            									if($po_approve_by_hp==0) 
            									{
            							?>
													<span class="glyphicon glyphicon-remove merah"></span>
            							<?php 
            									} else {
            							?>
            										<input type="checkbox" disabled checked>
            							<?php
            									}
            								} else {
            							?>
            										<input type="checkbox" disabled>
            							<?php 
            								}
            							?>
	            					</td>
	            					<td class="table_padd">
										<?php 
            								if(!empty($po_tgl_approved_dl)){
            									if ($po_approve_by_dl==0) {
            										echo '<span class="glyphicon glyphicon-remove merah"></span>';
            									} else {
            										echo '<input type="checkbox" disabled checked>';
            									}
	            							} else if (!empty($po_tgl_approved_rt)){
	            								if ($po_approve_by_rt==0){
	            									echo '<input type="checkbox" disabled>';
	            								}else{
	            									echo '<input type="checkbox" disabled>';
	            								}
	            							} else  if(!empty($po_tgl_approved_hp)){
	            								if ($po_approve_by_hp==1){
	            						?>
	            									<input type="checkbox" class="tanggal checkbox-md"  name="tanggal"  id="chk<?php echo $no;?>"
                                                    onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" />
                                        <?php
	            								} else {
	            									
	            									echo '<input type="checkbox" disabled>';
	            								}
	            							} else {
	            						?>
	            							<!-- <input type="checkbox" name="approve_by_hp"> -->
	            							<input type="checkbox" class="tanggal checkbox-md"  name="tanggal"  id="chk<?php echo $no;?>"
                                                    onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>'); check33(this, 'x<?php echo $no;?>');" />
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
	            							<input type="hidden" name="po_tgl_approved_dl[]" class="datetime" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_dl;?>" readonly>
	            							<input type="hidden" name="po_approve_by_dl[]" class='<?php if (empty($po_tgl_approved_dl)) { if (!empty($po_tgl_approved_hp)) { if ($po_approve_by_hp==1) {echo "approved";} else {echo "rejected";}}else {echo "approved";}}?>'  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_dl;?>" readonly>
	            							<input type="hidden" name="no_po[]"  value="<?php echo $row['no_po'];?>">
	            							<input type="hidden" name="total[]"  value="<?php echo $row['total'];?>">
	            							<input type="hidden" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
	            							<input type="hidden" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
	            							<input type="hidden" name="x[]" class='<?php if (empty($po_tgl_approved_dl)) { if (!empty($po_tgl_approved_hp)) { if ($po_approve_by_hp==1) {echo "xy";} else {echo "rejected";}}else {echo "xy";}}?>' id="x<?php echo $no;?>">
	            							<input type="hidden" name="no_ppo" value="<?php echo $PPO_Number;?>">
	            							<input type="hidden" name="sub_by" value="<?php echo $by;?>">
            						</td>
	            					<td class="table_padd">
										
	            						<?php 
	            						if(!empty($po_tgl_approved_rt)){
	            							if($po_approve_by_rt==0){
	            								echo '<p class="status" style="color:red;font-weight:bold;">Rejected</p>';
	            							} else {
	            								if (!empty($po_tgl_approved_hp)) {
	            									if ($po_approve_by_hp==1) {
	            										echo '<p class="status" style="color:green;font-weight:bold;">Approved</p>';
	            									} else {
	            										echo '<p class="status" style="color:red;font-weight:bold;">Rejected</p>';
	            									}
	            								} else if (!empty($po_tgl_approved_dl)) {
	            									if ($po_approve_by_dl==1) {
	            										echo '<p class="status" style="color:green;font-weight:bold;">Approved</p>';
	            									} else {
	            										echo '<p class="status" style="color:red;font-weight:bold;">Rejected</p>';
	            									}
	            								} else {
	            									echo '<p class="status" style="color:green;font-weight:bold;">Approved</p>';
	            								}
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
	            					</td>
	            					
	            					<?php 
	            						if (!empty($po_tgl_approved_rt)) {
	            							if ($po_approve_by_rt==0) {
	            								$total_ppo_rejek++;
	            							}
	            						} else if (!empty($po_tgl_approved_hp)) {
	            							if ($po_approve_by_hp==0) {
	            								$total_ppo_rejek++;
	            							}
	            						} else if (!empty($po_tgl_approved_dl)) {
	            							if ($po_approve_by_dl==0) {
	            								$total_ppo_rejek++;
	            							}
	            						}
	            					?>
	            					<?php
	            						} else {

	            						}
	            					?>
	<td class="table_padd">

		
	<!-- START COMMENT BOD_RT -->
	
                                           <?php 	if ($user == BOD_RT) 
                                           {
                                           ?>
										   
	                                       <!-- start show comment BOD_RT -->
	                                       <?php if($po_comment_rt=='')
	                                       {
	                                       ?>
										            <label class="check-box">
                                                    <input type="checkbox" id="chk<?php echo $no;?>rt1"  >
                                                    <span></span>
                                                    </label>
		
		                                            <p id="sb<?php echo $no;?>rt1" style="position:absolute; margin-top:2px; margin-left:-112px;">									
			                                        <textarea name="po_comment_rt[]"  class="one" id="<?php echo $no;?>"  
							                         style="height:120px;  box-shadow: 0 0 3px rgba(0,0,0,0.4);"  ></textarea>                                
								                    </p>
                                           <?php 
										   }  
										   else
										   {
	                                       ?>
		                                             <label class="check-boxrt">
                                                     <input type="checkbox" id="chk<?php echo $no;?>rt1"  >
                                                     <span></span>
                                                     </label>
		
		                                             <p id="sb<?php echo $no;?>rt1" style="position:absolute; margin-top:2px; margin-left:-112px;"> 
			                                         <input type="hidden" name="po_comment_rt[]" class="one" id="<?php echo $no;?>">
		                                             <textarea style="height:120px;  box-shadow: 0 0 3px rgba(0,0,0,0.4);" readonly><?php echo $row['comment_rt'];?></textarea> 
                                                     </p>
	                                       <?php 
										   } 
										   ?>	
										 
										 	        <textarea name="t[]" id="t<?php echo $no;?>" 
													 style=" height:20px; width:20px; visibility:hidden;position:absolute;" ></textarea>
				                           
										   <!-- end show comment BOD_RT -->
										   
										 
<!-- start show comment BOD_HP -->

<?php if($po_comment_hp=='')
	  {
	  ?>
	  <input type="hidden" name="po_comment_hp[]" value="<?php echo $row['comment_hp'];?>">
	  <?php
	  } 
	  else{
	  ?>
           <label class="check-boxhp">
           <input type="checkbox" id="chk<?php echo $no;?>hp1"  >
           <span></span>
           </label>
		
	       <p id="sb<?php echo $no;?>hp1" style="position:absolute; margin-top:2px; margin-left:-112px;">
	       <textarea name="po_comment_hp[]" style="height:120px;  box-shadow: 0 0 3px rgba(0,0,0,0.4);" readonly="readonly" ><?php echo $row['comment_hp'];?></textarea>
           </p>
	  <?php 
	  } 
	  ?>
<!-- end show comment BOD_HP -->	  
	  
	  			
                                                           <!-- start show comment BOD_DL -->
                                                           <?php if($po_comment_dl=='')
	                                                       {
														   ?>
	  <input type="hidden" name="po_comment_dl[]" value="<?php echo $row['comment_dl'];?>">
	  <?php
	                                                       } else{
	                                                       ?>
		                                                          <label class="check-boxdl">
                                                                  <input type="checkbox" id="chk<?php echo $no;?>dl1"  >
                                                                  <span></span>
                                                                  </label>
		
		                                                          <p id="sb<?php echo $no;?>dl1" style="position:absolute; margin-top:2px; margin-left:-112px;">
		                                                          <textarea name="po_comment_dl[]" style="height:120px;  box-shadow: 0 0 3px rgba(0,0,0,0.4);" 
			                                                       readonly="readonly" ><?php echo $row['comment_dl'];?></textarea>
                                                                  </p>
	                                                       <?php 
														   } 
														   ?>
														   <!-- end show comment BOD_DL -->
														   
														   				
                                                           <!-- end comment BOD_RT -->		




<!-- start comment BOD_HP -->
<?php	
} elseif ($user == BOD_HP) {
?>	
<!-- start show comment BOD_RT -->
                   <?php if($po_comment_rt=='')
	               {
				   ?>
	  <input type="hidden" name="po_comment_rt[]" value="<?php echo $row['comment_rt'];?>">
	  <?php
	               } else{
	               ?>
		                  <label class="check-boxrt">
                          <input type="checkbox" id="chk<?php echo $no;?>rt2"  >
                          <span></span>
                          </label>
		
		                  <p id="sb<?php echo $no;?>rt2" style="position:absolute; margin-left:-112px;">
                          <textarea name="po_comment_rt[]" style="height:120px;  box-shadow: 0 0 3px rgba(0,0,0,0.4);" 
                           readonly="readonly" ><?php echo $row['comment_rt'];?></textarea>
                          </p>
	               <?php
				   } 
				   ?>	
<!-- end show comment BOD_RT -->				   
 
 
<!-- start show comment BOD_HP -->

 <?php if($po_comment_hp=='')
	{
	?>	
	      <label class="check-box">
          <input type="checkbox" id="chk<?php echo $no;?>hp2"  >
          <span></span>
          </label>
		
          <p id="sb<?php echo $no;?>hp2" style="position:absolute; margin-left:-112px;">
          <textarea name="po_comment_hp[]" class="one" id="<?php echo $no;?>" style="height:120px;  box-shadow: 0 0 3px rgba(0,0,0,0.4);"  ></textarea>
          </p>	
    <?php  
	} 
	else
	{
	?>
           <label class="check-boxhp">
           <input type="checkbox" id="chk<?php echo $no;?>hp2"  >
           <span></span>
           </label>
		
	       <p id="sb<?php echo $no;?>hp2" style="position:absolute; margin-left:-112px;">
	       <input type="hidden" name="po_comment_hp[]" class="one" id="<?php echo $no;?>">
           <textarea style="height:120px;  box-shadow: 0 0 3px rgba(0,0,0,0.4);" readonly><?php echo $row['comment_hp'];?></textarea> 
           </p>
	<?php
	} 
	?>	
	       <textarea name="t[]" id="t<?php echo $no;?>" style=" height:20px; width:20px; visibility:hidden;position:absolute;" ></textarea>

    <!-- end show comment BOD_HP -->


<!-- start show comment BOD_DL -->			
    <?php if($po_comment_dl=='')
	{
	?>
	  <input type="hidden" name="po_comment_dl[]" value="<?php echo $row['comment_dl'];?>">
	  <?php
	} else{
	?>
	       <label class="check-boxdl">
           <input type="checkbox" id="chk<?php echo $no;?>dl2"  >
           <span></span>
           </label>
		
           <p id="sb<?php echo $no;?>dl2" style="position:absolute; margin-left:-112px;">
	       <textarea name="po_comment_dl[]" style="height:120px;  box-shadow: 0 0 3px rgba(0,0,0,0.4);" readonly="readonly" ><?php echo $row['comment_dl'];?></textarea>
           </p>
	<?php 
	} 
	?>	
<!-- end show comment BOD_DL -->


<!-- start comment BOD_DL -->

<?php 
} elseif ($user == BOD_DL) {
?>

<!-- start show comment BOD_RT -->			
<?php if($po_comment_rt=='')
	{
	?>
	  <input type="hidden" name="po_comment_rt[]" value="<?php echo $row['comment_rt'];?>">
	  <?php
	} else{
	?>
	       <label class="check-boxrt">
           <input type="checkbox" id="chk<?php echo $no;?>rt3"  >
           <span></span>
           </label>
		
	       <p id="sb<?php echo $no;?>rt3" style="position:absolute; margin-left:-112px;">
	       <textarea name="po_comment_rt[]" style="height:120px;  box-shadow: 0 0 3px rgba(0,0,0,0.4);" readonly="readonly" ><?php echo $row['comment_rt'];?></textarea>
           </p>
	<?php 
	} 
	?>	
<!-- end show comment BOD_RT -->			


<!-- start show comment BOD_HP -->			
 
    <?php if($po_comment_hp=='')
	{
	?>
	  <input type="hidden" name="po_comment_hp[]" value="<?php echo $row['comment_hp'];?>">
	  <?php
	} 
	else
	{
	?>
	        <label class="check-boxhp">
            <input type="checkbox" id="chk<?php echo $no;?>hp3"  >
            <span></span>
            </label>
		
	        <p id="sb<?php echo $no;?>hp3" style="position:absolute; margin-left:-112px;">
	        <textarea name="po_comment_hp[]" style="height:120px;  box-shadow: 0 0 3px rgba(0,0,0,0.4);" readonly="readonly" ><?php echo $row['comment_hp'];?></textarea>
            </p>
	<?php
	} 
	?>			
<!-- end show comment BOD_HP -->			
 
 
 
<!-- start show comment BOD_DL -->			

 <?php if($po_comment_dl=='')
	{
	?>	
	      <label class="check-box">
          <input type="checkbox" id="chk<?php echo $no;?>dl3"  >
          <span></span>
          </label>
		
	      <p id="sb<?php echo $no;?>dl3" style="position:absolute; margin-left:-112px;">
	      <textarea name="po_comment_dl[]"   class="one" id="<?php echo $no;?>" style="height:120px;  box-shadow: 0 0 3px rgba(0,0,0,0.4);"  ></textarea>
          </p>	
		
    <?php  
	} 
	else
	{
	?>
	       <label class="check-boxdl">
           <input type="checkbox" id="chk<?php echo $no;?>dl3"  >
           <span></span>
           </label>
		
	       <p id="sb<?php echo $no;?>dl3" style="position:absolute; margin-left:-112px;">
	       <input type="hidden" name="po_comment_dl[]" class="one" id="<?php echo $no;?>">
           <textarea style="height:120px;  box-shadow: 0 0 3px rgba(0,0,0,0.4);" readonly><?php echo $row['comment_dl'];?></textarea> 
           </p>
	<?php 
	} 
	?>	
	       <textarea name="t[]" id="t<?php echo $no;?>" style=" height:20px; width:20px; visibility:hidden; position:absolute;" ></textarea>



     <!-- end show comment BOD_DL -->			




<?php	
		} else {
		
		}
		?>

		</td>
            					</tr>
            					
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
            							echo "<div class='alert alert-success'>
            							<a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
            							$_GET[notif] 
            							</div>";
            						} else {
            							echo "<div class='alert alert-danger'> $_GET[notif] </div>";
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
				              				} else if(!is_null($po_tgl_approved_dl) || !empty($po_tgl_approved_dl)) {

				              					$yang_approv=BOD_DL;
				              					$end_yang_app = ucwords($yang_approv);
				              					echo"<div class='alerts'><b>Data pengajuan PO ini telah di proses</b><br>
				              					<div style='float:left; width:75px;'>Process By</div>
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;> $end_yang_app</div> <br>
				              					<div style='float:left; width:75px;'> Tanggal</div> 
				              					<div style='float:left; margin-left:10px;margin-right:10px;'>:</div>
				              					<div style=float:left;> $po_tgl_approved_dl</div> </div>";
				              					if ($total_ppo_rejek!=$total_ppo) {
				              	?>
				              					<tr>
					              					<td colspan="11">
						              					<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')">
						              					<label class="appall">
						              						<input type="checkbox" id="select_all"><u>Approve All</u>
						              					</label>
						              					<input type="hidden" name="tgl_approval" id="tgl_approval" value="">
					              					</td>		
				              					</tr>
								<?php
												}
				              				} else if(!is_null($po_tgl_approved_hp) || !empty($po_tgl_approved_hp)){

				              					$yang_approv=BOD_HP;
		            							$end_yang_app = ucwords($yang_approv);
		            							echo"<div class='alerts'><b>Data pengajuan PO ini telah di proses</b><br>
		            							<div style='float:left; width:75px;'>Process By</div> 
		            							<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
		            							<div style=float:left;>$end_yang_app</div> <br>
		            							<div style='float:left; width:75px;'> Tanggal</div> 
		            							<div style='float:left; margin-left:10px;margin-right:10px;'>:</div> 
		            							<div style=float:left;>$po_tgl_approved_hp</div> </div>";
		            							if ($total_ppo_rejek!=$total_ppo) {
		            			?>
		            							<tr>
			            							<td colspan="11">
				            							<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')">
				            							<label class="appall">
				            								<input type="checkbox" id="select_all"><u>Approve All</u>
				            							</label>
				            							<input type="hidden" name="tgl_approval" id="tgl_approval" value="">
			            							</td>		
		            							</tr>
		            			<?php
		            							}
				              				} else { 
				              	?>
				              					<tr>
				              						<td colspan="11">
				              							<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')">
				              							<label class="appall" style="margin:20px;">
				              								<input type="checkbox" id="select_all"><u>Approve All</u>
				              							</label>
				              							<input type="hidden" name="tgl_approval" id="tgl_approval" value="">
				              						<?php 
				              						?>
				              						</td>		
				              					</tr>
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
				              					echo"<div class='alerts'><b>Data pengajuan PO ini telah di proses</b><br>
				              					<div style='float:left; width:75px;'>Process By</div>
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;> $end_yang_app</div> <br>
				              					<div style='float:left; width:75px;'> Tanggal</div> 
				              					<div style='float:left; margin-left:10px;margin-right:10px;'>:</div>
				              					<div style=float:left;> $po_tgl_approved_dl</div> </div>";
				              					if ($total_ppo_rejek!=$total_ppo) {
				              	?>
				              					<tr>
								              		<td colspan="11">
								              			<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')">
								              			<label class="appall">
								              				<input type="checkbox" id="select_all"><u>Approve All</u> 
								              			</label>
								              			<input type="hidden" name="tgl_approval" id="tgl_approval" value="">
													</td>
												</tr>
								<?php
												}
				              				} else {
				              	?>
								              	<tr>
								              		<td colspan="11">
								              			<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')">
								              			<label class="appall">
								              				<input type="checkbox" id="select_all"><u>Approve All</u> 
								              			</label>
								              			<input type="hidden" name="tgl_approval" id="tgl_approval" value="">
													</td>
												</tr>
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
		            							if ($total_ppo_rejek!=$total_ppo) {
		            			?>
	                        					<tr>
	                        						<td colspan="11">
	                        							<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')">
	                        							<label class="appall">
	                        								<input type="checkbox" id="select_all"><u>Approve All</u> 
	                        							</label>
	                        							<input type="hidden" name="tgl_approval" id="tgl_approval" value="">
	                        						</td> 
	                        					</tr>
		            			<?php
		            							}
		            						} else {
		            							
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
	                        							<label class="appall">
	                        								<input type="checkbox" id="select_all"><u>Approve All</u> 
	                        							</label>
	                        							<input type="hidden" name="tgl_approval" id="tgl_approval" value="">
	                        						</td> 
												</tr>
								<?php
		            						}
		              					}
										
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
				              				} else if(!is_null($po_tgl_approved_dl) || !empty($po_tgl_approved_dl)) {

				              					$yang_approv=BOD_DL;
				              					$end_yang_app = ucwords($yang_approv);
				              					echo"<div class='alerts'><b>Data pengajuan PO ini telah di proses</b><br>
				              					<div style='float:left; width:75px;'>Process By</div>
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;> $end_yang_app</div> <br>
				              					<div style='float:left; width:75px;'> Tanggal</div> 
				              					<div style='float:left; margin-left:10px;margin-right:10px;'>:</div>
				              					<div style=float:left;> $po_tgl_approved_dl</div> </div>";
				              					if ($total_ppo_rejek!=$total_ppo) {
				              	?>
				              					<tr>
				              						<td colspan="11">
				              							<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')">
				              							<label class="appall">
				              								<input type="checkbox" id="select_all"><u>Approve All</u> 
				              							</label>
				              							<input type="hidden" name="tgl_approval" id="tgl_approval" value="">
				              						</td>		
				              					</tr>
				              	<?php
				              					}
				              				} else if(!is_null($po_tgl_approved_hp) || !empty($po_tgl_approved_hp)){

				              					$yang_approv=BOD_HP;
		            							$end_yang_app = ucwords($yang_approv);
		            							echo"<div class='alerts'><b>Data pengajuan PO ini telah di proses</b><br>
		            							<div style='float:left; width:75px;'>Process By</div> 
		            							<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
		            							<div style=float:left;>$end_yang_app</div> <br>
		            							<div style='float:left; width:75px;'> Tanggal</div> 
		            							<div style='float:left; margin-left:10px;margin-right:10px;'>:</div> 
		            							<div style=float:left;>$po_tgl_approved_hp</div> </div>";
				              					if ($total_ppo_rejek!=$total_ppo) {
		            			?>				
		            							<tr>
				              						<td colspan="11">
				              							<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')">
				              							<label class="appall">
				              								<input type="checkbox" id="select_all"><u>Approve All</u> 
				              							</label>
				              							<input type="hidden" name="tgl_approval" id="tgl_approval" value="" onClick="">
				              						</td>		
				              					</tr>
				              	<?php
				              					}
				              				} else { 
				              	?>
				              					<tr>
				              						<td colspan="11">
				              							<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')">
				              							<label class="appall">
				              								<input type="checkbox" id="select_all"><u>Approve All</u> 
				              							</label>
				              							<input type="hidden" name="tgl_approval" id="tgl_approval" value="">
				              						</td>		
				              					</tr>
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
				              					echo"<div class='alerts'><b>Data pengajuan PO ini telah di proses</b><br>
				              					<div style='float:left; width:75px;'>Process By</div>
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;> $end_yang_app</div> <br>
				              					<div style='float:left; width:75px;'> Tanggal</div> 
				              					<div style='float:left; margin-left:10px;margin-right:10px;'>:</div>
				              					<div style=float:left;> $po_tgl_approved_dl</div> </div>";
				              					if ($total_ppo_rejek!=$total_ppo) {
				              	?>
				           						<tr>
								              		<td colspan="11">
								              			<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')">
								              			<label class="appall">
								              				<input type="checkbox" id="select_all"><u>Approve All</u> 
								              			</label>
								              			<input type="hidden" name="tgl_approval" id="tgl_approval" value="">
													</td>
												</tr>
				              	<?php
				              					}
				              				} else {
				              	?>
								              	<tr>
								              		<td colspan="11">
								              			<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')"
								              			<?php  
								              				if (!empty($po_tgl_approved_dl)) {
								              					if ($po_approve_by_dl==0) {
								              						echo 'style="display:none;"';
								              					}
								              				}
								              			?>
								              			>
								              			<label class="appall">
								              				<input type="checkbox" id="select_all"><u>Approve All</u> 
								              			</label>
								              			<input type="hidden" name="tgl_approval" id="tgl_approval" value="">
								              		</td> 
								              	</tr>
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
		            							if ($total_ppo_rejek!=$total_ppo) {
		            			?>
		            							<tr>
	                        						<td colspan="11">
	                        							<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')">
	                        							<label class="appall">
	                        								<input type="checkbox" id="select_all"><u>Approve All</u> 
	                        							</label>
	                        							<input type="hidden" name="tgl_approval" id="tgl_approval" value="">
	                        						</td> 
	                        					</tr>
		            			<?php
		            							}
		            						} else {
								?>
												<tr>
									                <td colspan="11">
													<!-- dl blm proses -->
	                        							<input type="submit" class="btn btn-success submit" value="Submit" onClick="return confirm('Anda sudah yakin?')"
								              			<?php  
								              				if (!empty($po_tgl_approved_hp)) {
								              						echo 'style="display:none;"';	
								              				}
								              			?>
	                        							>
	                        							<label class="appall">
	                        								<input type="checkbox" id="select_all"><u>Approve All</u> 
	                        							</label>
	                        							<input type="hidden" name="tgl_approval" id="tgl_approval" value="">
	                        						</td> 
												</tr>
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
		<a href="<?php echo "http://report.hts.net.id/approval/ppo_approval_mobile.php?u=$u&p=$p&k=$k"; ?>" id="mob-desk" class="rs-link" data-link-desktop="<?php  echo 'Switch to Desktop version'; ?>" data-link-responsive="Switch to Mobile version"></a>
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
		day = day < 10 ? "0" + day : day,
		hour = date.getHours(),
		hour = hour < 10 ? "0" + hour : hour,
		minute = date.getMinutes(),
		second = date.getSeconds(),
		hourFormatted = hour, //% 12 || 12, // hour returned in 24 hour format
		minuteFormatted = minute < 10 ? (minute=0 ? "00" : "0" + minute) : minute;
		// morning = hour < 12 ? " am" : " pm";

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
$("#select_all").change(function(){  //"select all" change 
    var status = this.checked; // "select all" checked status
    $('.checkbox-md').each(function(){ //iterate all listed checkbox items
        this.checked = status; //change ".checkbox" checked status
        var d = formatDate (new Date());
        $("#CBX").val(d);
        $(".datetime").val(d);
        $(".approved").val('1');
        $(".xy").val('y');
    });
});
$("#select_all").change(function(){ //".checkbox" change 
	if(this.checked == false){ //if this item is unchecked
		var o='';
        $("#select_all")[0].checked = false; //change "select all" checked status to false
        $('#CBX').val(o);
        $(".datetime").val(o);
        $(".approved").val(0);
        $(".xy").val(o);
    }
});
//uncheck "select all", if one of the listed checkbox item is unchecked
$('.checkbox-md').change(function(){ //".checkbox" change 
	var o='';
    $('#CBX').val('');
    if(this.checked == false){ //if this item is unchecked
        $("#select_all")[0].checked = false; //change "select all" checked status to false
        $('#CBX').val('');
    }
});
$('.submit').click(function(){
	var datesub=formatDate(new Date ());
	$(".datetime").val(datesub);
	$('#tgl_approval').val(datesub);
});

$(document).ready(function()
   {
   		$('td.nomor_po').on('click',function (){
		// var cellText = $(this).html();
		// alert(cellText);
		// alert(this.id);

		$("#modal_detail").modal("show");
	});

   	$('#modal_detail').on('show.bs.modal',function showUser(str) {
   		// var cellText = $('td.nomor_po').prop("id");
		// alert(cellText);
		// var idpo = var cellText;
		var $modal = $(this),
   		esseyId = str.relatedTarget.id;

//            $.ajax({
//                cache: false,
//                type: 'POST',
//                url: 'backend.php',
//                data: 'EID='+essay_id,
//                success: function(data) 
//                {
		$modal.find('#nopo_modal').html(esseyId);
//                }
//            });
   		var cellText = esseyId;
		if (cellText=="") {
			document.getElementById("txtHint").innerHTML="";
			return;
		} 
		if (window.XMLHttpRequest) {
   			// code for IE7+, Firefox, Chrome, Opera, Safari
   			xmlhttp=new XMLHttpRequest();
   		} else { 
  			// code for IE6, IE5
  			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
  		xmlhttp.onreadystatechange=function() {
  			if (this.readyState==4 && this.status==200) {
  				document.getElementById("txtHint").innerHTML=this.responseText;
  			}
  		}
  		xmlhttp.open("GET","detail_tabel.php?q="+cellText,true);
  		xmlhttp.send();
  		// $("select:nth-child(1)").val(str);
  		// $("select#list-po").val(str);
  	});
});
// $('td#nomor_po').click(function() {
//     var cellText = $(this).html();  
//     alert( cellText );
// });
// $(document).ready(function(){
// 	$(window).on('load', function(){
// 	    var win = $(this);
// 	    if (win.width() > 939) { 
// 	    	$('#tabel').addClass('table-responsive');
// 	    } else {
// 	        $('#tabel').removeClass('table-responsive');
// 	        $('#tabel').addClass('asd');
// 	    }
// 	});
// });
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