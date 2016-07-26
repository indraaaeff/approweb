<html>
<head>
	<title>HTS APP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="css/sticky-footer-navbar.css">
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

  	<link rel="stylesheet" href="js/bootstrap.js">
    <link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
    <script type="text/javascript" src="//code.jquery.com/jquery-1.10.1.js"></script>
    <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
</head>
<body>
	<?php

	if ( isset($_GET['u']) && isset($_GET['p']) && isset($_GET['k']) ) {
		include "connection.php";
		include "user.php";
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
	?>

	<div class="header-nav">
		<nav class="navbar navbar-fixed-top navbar-style">
			<div class="container">
	          <a class="navbar-brand logo" href="#">Approval</a>
	          <div>
	          	<img class="logo-image" src="img/logo_hts_glowing.png" alt="">
	          </div>
			</div>
			<div class="header-date">
				<div class="container">
					<div class="date">
						<?php echo date("l"); echo('&nbsp;'); echo date("d/m/Y"); echo('&nbsp;'); ?>
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
	<div class="spasi"></div>
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
			<div class="panel-heading nopadding" style="background:#f26904;">
				<div class="po_head">
					<table class="table nopadding" style="margin-bottom:0px;">
						<tbody style="color:white;">
							<tr>
								<td><b>No Pengajuan : <?php echo $PPO_Number; ?></b></td>
								<td><b>Tanggal : <?php echo $tgl_pengajuan;?></b></td>
								<td><b>Submitted By : <?php echo $by;?></b></td>
								<td><b><?php $total_ppo=$PPO_TableDetail->num_rows; echo "Total PO : $total_ppo";?></b></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="hidden_po" style="color:white;"> 
				 	<b>
				 		No Pengajuan : <?php echo $PPO_Number; ?><br>
							Tanggal :  <?php echo $tgl_pengajuan;?><br>
						Submitted By <?php echo $by;?><br>
						<?php 
							$total_ppo=$PPO_TableDetail->num_rows;
							echo "Total PO : $total_ppo";
						?>
					</b>
				</div>
			</div>
			<div class="panel-body">
				<form method="post" action="post.php">
					<div id="tabel" class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead class="table_po">
								<tr>
									<th></th>
									<th class="tengah">NO PO</th>
									<th class="tengah">NAMA VENDOR</th>
									<th class="tengah">TANGGAL PO</th>
									<th class="tengah">PPN</th>
									<th class="tengah">TOTAL</th>
									<th class="tengah">RT</th>
									<th class="tengah">HP</th>
									<th class="tengah">DL</th>
									<th class="tengah">STATUS</th>
								</tr>

							</thead>
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

							// echo $po_comment_rt;
							// $po_tgl_approved_rt="2/6/2016";
							// $po_approve_by_rt=1;
							// $po_tgl_approved_hp=1;
							// $po_approve_by_hp=0;
							// $po_tgl_approved_dl=1;
							?>
								<?php
								$no = +1;
								$grand= 0;
								while ($row = $PPO_TableDetail->fetch_assoc()) {

								?>
							<tbody class="desktop">
								<!-- loop for row details -->
								<tr align="center" id="detail_column">
									<?php $Tanggal_po = date( 'd-m-Y', strtotime( $row['tgl_po'] )); ?>
            						<td><?php echo $no;?></td>
            						<td><?php echo $row['no_po'];?></td>
            						<td><?php echo $row['nama_vendor'];?></td>
            						<td><?php echo  $Tanggal_po;?></td>
            						<!-- <td style="display:none;"><?php echo $row['comment_rt'] ?></td> -->
            						<?php 
	            						if ($row['non_ppn'] == 0)
	            							{ $ppn='ya';
	            							echo '<td align="center">Ya</td>';
	            							echo "<input type='hidden' name='ppn' value='$ppn'>";
	            						}else {
	            							$ppn='tidak';
	            							echo '<td align="center">Tidak</td>';
	            							echo "<input type='hidden' name='ppn' value='$ppn'>";
	            						}
            						?>
            						<td><?php echo number_format( $row['total'] );?></td>
            						<?php 
            							$grand += $row['total'];
            						?>

            						<input  type="hidden"  name="po_tgl_app_hp[]" value="<?php echo $po_tgl_approved_hp;?>" >
            						<input  type="hidden"  name="po_app_hp[]"     value="<?php echo $po_approve_by_hp;?>" >
            						<input  type="hidden"  name="po_tgl_app_dl[]" value="<?php echo $po_tgl_approved_dl;?>" >
            						<input  type="hidden"  name="po_app_dl[]"     value="<?php echo $po_approve_by_dl;?>" >
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
            						<td id="check-box">
            							<?php 
            								//dummy data for checking checkbox
            								// $po_tgl_approved_rt=0;
            								// $po_approve_by_hp=1;
            								// $po_approve_by_dl=1;
            								// $po_tgl_approved_dl = 1;
            								// $po_tgl_approved_hp = 1;
            								// $po_approve_by_hp =1 ;
            								// $po_approve_by_dl =1 ;
            							// $row['comment_rt'] = "okeee";
            							// $po_comment_rt = 1;
            							// $row['nama_vendor'] = "HTS";

            								if ($po_tgl_approved_rt==''){
            									if(!empty($po_approve_by_hp) && !empty($po_approve_by_dl)) {
            										echo '<input type="checkbox" disabled>';
	            								}else{
	            						?>
	            							<input type="checkbox" class="tanggal" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" />
	            							<!-- <input type="checkbox" name="approve_by_rt" onclick="check(this, 'date1');"> -->
	            						<?php
	            								}
	            						?>
	            							<input type="hidden" name="po_tgl_approved_rt[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_rt;?>" readonly="readonly">
	            							<input type="hidden" name="po_approve_by_rt[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_rt;?>" readonly="readonly">
	            							<input type="hidden" name="no_po[]"  value="<?php echo $row['no_po'];?>">
	            							<input type="hidden" name="total[]"  value="<?php echo $row['total'];?>">
	            							<input type="hidden" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
	            							<input type="hidden" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
	            							<input type="hidden" name="no_ppo" value="<?php echo $PPO_Number;?>">
	            							<input type="hidden" name="sub_by" value="<?php echo $by;?>">
	            							<input type="hidden" name="comment_rt[]" value="<?php echo $po_comment_rt ?>">
            						</td>
	            						<?php 
	            							} else {
	            								if (!empty($po_approve_by_rt)) {
	            									echo '<input type="checkbox" disabled checked>';
	            								} else {
	            									echo '<span class="glyphicon glyphicon-remove"></span>';
	            								}
	            							}
	            						?>
            						<td id="check-box">
            							<?php 
            								if($po_approve_by_hp==1) 
            								{ 
            							?>
            							<input type="checkbox" disabled checked>
            							<?php 
            								} else if (empty($po_tgl_approved_hp)) {
            							?>
            							<input type="checkbox" disabled>
            							<?php
            								} else {
            							?>
										<span class="glyphicon glyphicon-remove"></span>
										<?php
            								}
            							?>
            						</td>
            						<td id="check-box">
            							<?php 
            								if($po_approve_by_dl==1) 
            								{ 
            							?>
										<input type="checkbox" disabled checked>
            							<?php 
            								} else if(empty($po_tgl_approved_dl)) {
            							?>
            							<input type="checkbox" disabled>
            							<?php
            								} else {
            							?>
            							<span title="" class="glyphicon glyphicon-remove"></span>
            							<?php		
            								}
            							?>
            						</td>
	            					<td>
	            						<!-- <p>Waiting</p> -->
	            						<?php 
	            							if(!empty($po_tgl_approved_rt)){
	            								if($po_approve_by_rt==1){
	            									echo '<p style="color:green;font-weight:bold;">Approved</p>';
	            								} else {
	            									echo '<p style="color:red;font-weight:bold;">Rejected</p>';
	            								}
	            							} else if(empty($po_tgl_approved_rt)){
	            								if (!empty($po_approve_by_hp) && !empty($po_approve_by_dl)) {
	            									echo '<p style="color:green;font-weight:bold;">Approved</p>';
	            								} else {
	            									echo '<p style="color:orange;font-weight:bold;">Waiting</p>';
	            								}
	            							}
	            						?>
	            					</td>
	            					<!-- BOD_HP Session CHECKBOX -->
	            					<?php 
	            						} else if ($user == BOD_HP) 
	            						{
	            					// $po_approve_by_rt = 1;
	            					// $po_tgl_approved_rt=1;
	            					// $po_approve_by_dl=1;
	            					// $po_tgl_approved_dl = 1;
	            					// $po_approve_by_hp= 1;
	            					// $po_tgl_approved_hp = 1;

	            					?>
	            					<td id="check-box">
										<?php 
            								if($po_approve_by_rt==1) 
            								{ 
            							?>
            							<input type="checkbox" disabled checked>
            							<?php 
            								} else if(empty($po_tgl_approved_rt)){
            							?>
            							<input type="checkbox" disabled>
            							<?php 
            								} else {
            							?>
            							<span class="glyphicon glyphicon-remove"></span>
            							<?php
            								}
            							?>
	            					</td>
	            					<td id="check-box">
	            						<?php 
            								if(!empty($po_tgl_approved_hp)){
            									if (!empty($po_approve_by_hp)) {
            										echo '<input type="checkbox" disabled checked>';
            									} else {
            										echo '<span class="glyphicon glyphicon-remove"></span>';
            									}
	            							} else if (!empty($po_tgl_approved_rt)){
	            								if (empty($po_approve_by_rt)){
	            									echo '<input type="checkbox" disabled>';
	            								}
	            							} else {
	            						?>
	            							<!-- <input type="checkbox" name="approve_by_hp"> -->
	            							<input type="checkbox" class="tanggal"  name="tanggal"  id="chk<?php echo $no;?>"
                                                    onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" />
	            							<?php 
	            							}
	            							?>
	            							<input type="" name="po_tgl_approved_hp[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_hp;?>" readonly>
	            							<input type="" name="po_approve_by_hp[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_hp;?>" readonly>
	            							<input type="" name="no_po[]"  value="<?php echo $row['no_po'];?>">
	            							<input type="" name="total[]"  value="<?php echo $row['total'];?>">
	            							<input type="" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
	            							<input type="" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
	            							<input type="" name="no_ppo" value="<?php echo $PPO_Number;?>">
	            							<input type="" name="sub_by" value="<?php echo $by;?>">
	            							<input type="" name="comment_hp[]" value="<?php echo $po_comment_hp; ?>">
	            					
	            					</td>
	            					 <td id="check-box">
										<?php 
            								if($po_approve_by_dl==1) 
            								{ 
            							?>
            							<input type="checkbox" disabled checked>
            							<?php 
            								} else if(empty($po_tgl_approved_dl)){
            							?>
            							<input type="checkbox" disabled>
            							<?php 
            								} else {
            							?>
            							<span class="glyphicon glyphicon-remove"></span>
            							<?php
            								}
            							?>
	            					</td>
	            					<td>
	            						<!-- <p>Waiting</p> -->
	            						<?php 
	            							if(!empty($po_tgl_approved_rt)){
	            								if($po_approve_by_rt==1){
	            									echo '<p style="color:green;font-weight:bold;">Approved</p>';
	            								} else {
	            									echo '<p style="color:red;font-weight:bold;">Rejected</p>';
	            								}
	            							} else if(empty($po_tgl_approved_rt)){
	            								if (!empty($po_approve_by_hp) && !empty($po_approve_by_dl)) {
	            									echo '<p style="color:green;font-weight:bold;">Approved</p>';
	            								} else {
	            									echo '<p style="color:orange;font-weight:bold;">Waiting</p>';
	            								}
	            							}
	            						?>
	            					</td>
	            					<!-- BOD_DL CHECKBOX -->
	            					<?php 
	            						} else {
	            							// dummy data for checking DL checkbox
	            							// $po_approve_by_rt =1 ;
	            					?>
	            					<td id="check-box">
										<?php 
            								if($po_approve_by_rt==1) 
            								{ 
            							?>
            							<input type="checkbox" disabled checked>
            							<?php 
            								} else if(empty($po_tgl_approved_rt)){
            							?>
            							<input type="checkbox" disabled>
            							<?php 
            								} else {
            							?>
            							<span class="glyphicon glyphicon-remove"></span>
            							<?php
            								}
            							?>
	            					</td>
	            					<td id="check-box">
            							<?php 
            								if($po_approve_by_hp==1) 
            								{ 
            							?>
            							<input type="checkbox" disabled checked>
            							<?php 
            								} else if (empty($po_tgl_approved_hp)) {
            							?>
            							<input type="checkbox" disabled>
            							<?php
            								} else {
            							?>
										<span class="glyphicon glyphicon-remove"></span>
										<?php
            								}
            							?>
	            					</td>
	            					<td id="check-box">
										<?php 
            								if(!empty($po_tgl_approved_hp)){
            									if (!empty($po_approve_by_hp)) {
            										echo '<input type="checkbox" disabled checked>';
            									} else {
            										echo '<span class="glyphicon glyphicon-remove"></span>';
            									}
	            							} else if (!empty($po_tgl_approved_rt)){
	            								if (empty($po_approve_by_rt)){
	            									echo '<input type="checkbox" disabled>';
	            								}
	            							} else {
	            						?>
	            							<!-- <input type="checkbox" name="approve_by_hp"> -->
	            							<input type="checkbox" class="tanggal"  name="tanggal"  id="chk<?php echo $no;?>"
                                                    onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" />
	            							<?php 
	            							}
	            							?>
	            							<input type="hidden" name="po_tgl_approved_hp[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_hp;?>" readonly>
	            							<input type="hidden" name="po_approve_by_hp[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_hp;?>" readonly>
	            							<input type="hidden" name="no_po[]"  value="<?php echo $row['no_po'];?>">
	            							<input type="hidden" name="total[]"  value="<?php echo $row['total'];?>">
	            							<input type="hidden" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
	            							<input type="hidden" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
	            							<input type="hidden" name="no_ppo" value="<?php echo $PPO_Number;?>">
	            							<input type="hidden" name="sub_by" value="<?php echo $by;?>">
	            							<input type="hidden" name="comment_dl[]" value="<?php echo $po_comment_dl; ?>">
            						</td>
	            					<td>
										<!-- <p>Waiting</p> -->
										<?php 
	            							if(!empty($po_tgl_approved_rt)){
	            								if($po_approve_by_rt==1){
	            									echo '<p style="color:green;font-weight:bold;">Approved</p>';
	            								} else {
	            									echo '<p style="color:red;font-weight:bold;">Rejected</p>';
	            								}
	            							} else if(empty($po_tgl_approved_rt)){
	            								if (!empty($po_approve_by_hp) && !empty($po_approve_by_dl)) {
	            									echo '<p style="color:green;font-weight:bold;">Approved</p>';
	            								} else {
	            									echo '<p style="color:orange;font-weight:bold;">Waiting</p>';
	            								}
	            							}
	            						?>
	            					</td>
	            					<?php
	            						} 
	            					?>
            					</tr>
            					<div class="gadget">
            						<ul class="nav nav-tabs responsive" id="myTab">
            							<li class="">
            								<a href="#<?php echo 'po_details'.$no; ?>">
            									<?php $Tanggal_po = date( 'd-m-Y', strtotime( $row['tgl_po'] )); ?>
            									<b>No PO :</b> <?php echo $row['no_po']; ?><br>
            									<b>Tanggal PO :</b> <?php echo $Tanggal_po; ?><br>
            									<b>Nama Vendor :</b> <?php echo $row['nama_vendor']; ?><br>

            									<?php 
            									if(!empty($po_tgl_approved_rt)){
            										if($po_approve_by_rt==1){
            											echo '<p style="color:green;font-weight:bold;">Approved</p>';
            										} else {
            											echo '<p style="color:red;font-weight:bold;">Rejected</p>';
            										}
            									} else if(empty($po_tgl_approved_rt)){
            										if (!empty($po_approve_by_hp) && !empty($po_approve_by_dl)) {
            											echo '<p style="color:green;font-weight:bold;">Approved</p>';
            										} else {
            											echo '<p style="color:orange;font-weight:bold;">Waiting</p>';
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
            									Total : <?php echo number_format($row['total']); ?><br>
            									PPN : <?php echo $ppn; ?><br><br>
            									<u>NOTE</u> : <br>
            									Richardus Teddy
            									
            									<input type="checkbox" class="cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" <?php if($user!=BOD_RT){echo 'disabled';} ?>>
            									<textarea class="form-control" rows="2" name="comment_rt[]" <?php if($user == BOD_HP || $user == BOD_DL){echo "readonly";} ?>><?php if(!empty($po_comment_rt)){echo $po_comment_rt;} ?></textarea>
            									Harijanto Pribadi

												<!-- <input type="" name="po_tgl_approved_rt[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_rt;?>" readonly="readonly">
												<input type="" name="po_approve_by_rt[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_rt;?>" readonly="readonly"> -->
												<input  type="checkbox" class="cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" <?php if($user!=BOD_HP){echo 'disabled';} ?>>
												<textarea class="form-control" rows="2" name="comment_hp[]" <?php if($user == BOD_RT || $user == BOD_DL){echo "readonly";} ?>><?php if(!empty($po_comment_hp)){echo $po_comment_hp;} ?></textarea>
												Dicky Lisal
												<input type="checkbox" class="cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" <?php if($user!=BOD_DL){echo 'disabled';} ?>>
												<textarea class="form-control" rows="2" name="comment_dl[]" <?php if($user == BOD_RT || $user == BOD_HP){echo "readonly";} ?>><?php if(!empty($po_comment_dl)){echo $po_comment_dl;} ?></textarea>
											</b>
											<br>
										</div>
									</div>
            					</div>
            					<?php 
            						$no++;
            					}
            					?>
 								<!-- validation if BOD processed or not -->
            					<?php 
            					 if ( isset($_GET['notif']) )
            					 {
            					 	echo"<div class='alerts'> $_GET[notif] </div>";
            					 } else {
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
				              					<tr>
				              						<td colspan="10">
				              							<input type="submit" class="btn btn-success submit" value="Submit">
				              							<!-- <input type="submit" name="submit" value="submit"  onClick="return confirm('Anda sudah yakin?')"/> -->
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
				              				} else {
				              	?>
								              	<tr>
								              		<td colspan="10">
								              			<input type="submit" class="btn btn-success submit" value="Submit">
								              			<!-- <input type="submit" name="submit" value="submit"  onClick="return confirm('Anda sudah yakin?')"/></td> -->
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
		            						} else {
	                        	?>
	                        					<tr>
	                        						<td colspan="10">
	                        							<input type="submit" class="btn btn-success submit" value="Submit">
	                        							<!-- <input type="submit" name="submit" value="submit"  onClick="return confirm('Anda sudah yakin?')"/>-->
	                        						</td> 
	                        					</tr>
		              			<?php  
		              						} 
		              					}
									} else {
										//do something	
									} 
						 		?> 
								<?php 
								} 
								?>
								<!-- end of validation processed or not -->
							</tbody>
						</table>
					</div>
					<td colspan="10">
						<?php 
						if ($user == BOD_RT) {
							if (empty($po_tgl_approved_rt)) {
								echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit">';
							} else {
								echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
							}
						} else if ($user == BOD_HP){
							if (empty($po_tgl_approved_rt)){
								echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit">';
							} else {
								echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
							}
						} else if ($user == BOD_DL){
							if (empty($po_tgl_approved_rt)){
								echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit">';
							} else {
								echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
							}
						}
						?>
						<!-- <input type="submit" name="submit" value="submit"  onClick="return confirm('Anda sudah yakin?')"/> -->
					</td>
				</form>
			</div>
		</div>
	</div>
	<?php 
	// echo $user;
		} 
	}
	?>
	<footer class="footer">
      <div class="container">
        <p class="text-muted">Copyright &copy; 2016 PT. Hawk Teknologi Solusi, All Rights Reserved.</p>
      </div>
    </footer>
</body>
</html>
<script>
$(document).ready(function(){
	$(window).on('load', function(){
	    var win = $(this);
	    if (win.width() > 897) { 
	    	$('#tabel').addClass('table-responsive');
	    } else {
	        $('#tabel').removeClass('table-responsive');
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

function startTime()
{
	var today=new Date()
	var h=today.getHours()
	var m=today.getMinutes()
	var s=today.getSeconds()
	var ap="AM";

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
window.onload=responsive;

$('tr #check-box').on('click',function(){
    $("#myModal").modal("show");
    $("#ppo_number").val($(this).closest('tr').children()[1].textContent);
    $("#tanggal_po").val($(this).closest('tr').children()[3].textContent);
    $("#nama_vendor").val($(this).closest('tr').children()[2].textContent);
});

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


</script>