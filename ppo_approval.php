<html>
<head>
	<title>HTS APP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

  	<link rel="stylesheet" href="js/bootstrap.js">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/sticky-footer-navbar.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/responsive-switch.min.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</head>
<body>
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
	?>

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
						<?php echo $hari;?> <?php echo $tanggal;?> <?php echo $bulan;?> <?php echo $tahun;?> 
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
				<div class="po_head">
					<table class="table nopadding" style="margin-bottom:0px;">
						<tbody>
							<tr>
								<td><b>No Pengajuan : <?php echo $PPO_Number; ?></b></td>
								<td><b>Tanggal : <?php echo $tgl_pengajuan;?></b></td>
								<td><b>Submitted By : <?php echo $by;?></b></td>
								<td><b><?php $total_ppo=$PPO_TableDetail->num_rows; echo "Total PO : $total_ppo";?></b></td>
							</tr>
						</tbody>
					</table>
				</div>
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
								$no = +1;
								$grand= 0;
								while ($row = $PPO_TableDetail->fetch_assoc()) {

								?>
							<tbody class="desktop">
								<input type="hidden" name="key" value="<?php echo $key;?>">
								<input type="hidden" name="u" value="<?php echo $_GET['u'];?>">
								<input type="hidden" name="total_ppo" id="total_ppo" value="<?php echo $total_ppo;?>"	>
								<input type="hidden" name="tgl_pengajuan" value="<?php echo $tgl_pengajuan;?>">
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
            						<td>
            							<?php 
            							// $po_tgl_approved_rt = "2/6/2016";
            							// $po_approve_by_rt = 1;
            							// $po_comment_rt = "ogah";

            								//dummy data for checking checkbox
            								if (empty($po_tgl_approved_rt) || is_null($po_tgl_approved_rt)){
            									if (!empty($po_tgl_approved_hp) || !is_null($po_tgl_approved_hp) && !empty($po_tgl_approved_dl) || !is_null($po_tgl_approved_dl)) {
	            									if($po_approve_by_hp==1 && $po_approve_by_dl==1) {
	            										echo '<input type="checkbox" disabled>';
		            								} else if($po_approve_by_hp==1 && $po_approve_by_dl==0) {
		            									echo '<input type="checkbox" disabled>';
		            								} else if($po_approve_by_hp==0 && $po_approve_by_dl==1){
		            									echo '<input type="checkbox" disabled>';
		            								} else {
		            					?>
		            									<input type="checkbox" class="tanggal checkbox-md" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" />
		            					<?php		            								
		            								}
            									}else if (!empty($po_tgl_approved_dl)){
            										if ($po_approve_by_dl==0) {
            											echo '<input type="checkbox" disabled>';
            										} else {
            							?>
            											<input type="checkbox" class="tanggal checkbox-md" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" />
            							<?php 				
            										}
            									}else if (!empty($po_tgl_approved_hp)){
            										if ($po_approve_by_hp==0) {
            											echo '<input type="checkbox" disabled>';
            										} else {
            							?>
            											<input type="checkbox" class="tanggal checkbox-md" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" />
            							<?php 
            										}
            									} else {
	            						?>
	            							<input type="checkbox" class="tanggal checkbox-md" name="tanggal " id="chk<?php echo $no;?>" onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" />
	            							<!-- <input type="checkbox" name="approve_by_rt" onclick="check(this, 'date1');"> -->
	            						<?php
	            								}
	            						?>
	            							<input type="" name="po_tgl_approved_rt[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_rt;?>" readonly="readonly">
	            							<input type="" name="po_approve_by_rt[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_rt;?>" readonly="readonly">
	            							<input type="" name="no_po[]"  value="<?php echo $row['no_po'];?>">
	            							<input type="" name="total[]"  value="<?php echo $row['total'];?>">
	            							<input type="" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
	            							<input type="" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
	            							<input type="" name="no_ppo" value="<?php echo $PPO_Number;?>">
	            							<input type="" name="sub_by" value="<?php echo $by;?>">
	            							<input type="" name="comment_rt[]" value="<?php echo $po_comment_rt ?>">
	            							<input type="" name="comment_rt[]" value="<?php echo $po_tgl_approved_hp ?>">
	            							<input type="" name="comment_rt[]" value="<?php echo $row['tgl_approved_dl']; ?>">
            						</td>
	            						<?php 
	            							} else {
	            								if (!empty($po_approve_by_rt)) {
	            									echo '<input type="checkbox" disabled checked>';
	            								} else {
	            									echo '<span class="glyphicon glyphicon-remove merah"></span>';
	            								}
	            							}
	            						?>
            						<td>
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
										<span class="glyphicon glyphicon-remove merah"></span>
										<?php
            								}
            							?>
            						</td>
            						<td>
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
            							<span class="glyphicon glyphicon-remove merah"></span>
            							<?php		
            								}
            							?>
            						</td>
	            					<td>
										<!-- <p>Waiting</p> -->
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
	            					<td>
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
            							<span class="glyphicon glyphicon-remove merah"></span>
            							<?php
            								}
            							?>
	            					</td>
	            					<td>
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
                                                    onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" />
                                        <?php
	            								} else {
	            									
	            									echo '<input type="checkbox" disabled>';
	            								}
	            							}else{
	            						?>
	            							<!-- <input type="checkbox" name="approve_by_hp"> -->
	            							<input type="checkbox" class="tanggal checkbox-md"  name="tanggal"  id="chk<?php echo $no;?>"
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
	            							<input type="hidden" name="comment_hp[]" value="<?php echo $po_comment_hp; ?>">
	            					
	            					</td>
	            					 <td>
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
            							<span class="glyphicon glyphicon-remove merah"></span>
            							<?php
            								}
            							?>
	            					</td>
	            					<td>
										<!-- <p>Waiting</p> -->
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
	            					</td>
	            					<!-- BOD_DL CHECKBOX -->
	            					<?php 
	            						} else {
	            							// dummy data for checking DL checkbox
	            							// $po_approve_by_rt =1 ;
	            					?>
	            					<td>
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
            							<span class="glyphicon glyphicon-remove merah"></span>
            							<?php
            								}
            							?>
	            					</td>
	            					<td>
            							<?php 
            								if($po_approve_by_hp==1) 
            								{ 
            							?>
            							<input type="checkbox" disabled checked>
            							<?php 
            								} else if (empty($po_tgl_approved_hp)){
            							?>
            							<input type="checkbox" disabled>
            							<?php
            								} else {
            							?>
										<span class="glyphicon glyphicon-remove merah"></span>
										<?php
            								}
            							?>
	            					</td>
	            					<td>
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
                                                    onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" />
                                        <?php
	            								} else {
	            									
	            									echo '<input type="checkbox" disabled>';
	            								}
	            							} else {
	            						?>
	            							<!-- <input type="checkbox" name="approve_by_hp"> -->
	            							<input type="checkbox" class="tanggal checkbox-md"  name="tanggal"  id="chk<?php echo $no;?>"
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
            											# code...
	            										if ($po_approve_by_rt==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Anda</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Anda</b> -</i></u></p>';
	            										}
            										}
            										if (!empty($po_tgl_approved_hp)) {
            											# code...
	            										if ($po_approve_by_hp==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Harijanto Pribadi</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Harijanto Pribadi</b> -</i></u></p>';
	            										}
            										}
            										if (!empty($po_tgl_approved_dl)) {
            											# code...
	            										if ($po_approve_by_dl==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Dicky Lisal</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Dicky Lisal</b> -</i></u></p>';
	            										}
            										}
            									} else if ($user == BOD_HP){
            										if (!empty($po_tgl_approved_rt)) {
            											# code...
	            										if ($po_approve_by_rt==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Richardus Teddy</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Richardus Teddy</b> -</i></u></p>';
	            										}
            										}
            										if (!empty($po_tgl_approved_hp)) {
            											# code...
	            										if ($po_approve_by_hp==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Anda</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Anda</b> -</i></u></p>';
	            										}
            										}
            										if (!empty($po_tgl_approved_dl)) {
            											# code...
	            										if ($po_approve_by_dl==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Dicky Lisal</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Dicky Lisal</b> -</i></u></p>';
	            										}
            										}
            									} else {
            										if (!empty($po_tgl_approved_rt)) {
            											# code...
	            										if ($po_approve_by_rt==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Richardus Teddy</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Richardus Teddy</b> -</i></u></p>';
	            										}
            										}
            										if (!empty($po_tgl_approved_hp)) {
            											# code...
	            										if ($po_approve_by_hp==0) {
	            											echo '<p class="warn-notif"><u><i>- PO ini telah di <b>Tolak</b> oleh <b>Harijanto Pribadi</b> -</i></u></p>';
	            										} else {
	            											echo '<p class="succ-notif"><u><i>- PO ini telah di <b>Setujui</b> oleh <b>Harijanto Pribadi</b> -</i></u></p>';
	            										}
            										}
            										if (!empty($po_tgl_approved_dl)) {
            											# code...
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
            									PPN : <?php echo $ppn; ?><br>

            									
            									
            									<u>NOTE</u> : <br>
            									<!-- RT -->
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
            									<input  type="checkbox" class="cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" <?php if($user==BOD_HP || $user==BOD_DL){echo " disabled";}?> >
            									<?php 
            											}
            										} else if (!empty($po_tgl_approved_dl)){
            											if ($po_approve_by_dl==0) {
            												echo '<input type="checkbox" class="checked" disabled>';
            											} else {
            									?>
												<input  type="checkbox" class="cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" <?php if($user==BOD_HP || $user==BOD_DL){echo " disabled";}?>>
            									<?php
            											}
            										} else {
            									?>
            									<input  type="checkbox" class="cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" <?php if($user==BOD_HP || $user==BOD_DL){echo " disabled";}?>>
            									<?php
            										}
            									}
            									?>

            									<!-- diatas validation for checkbox -->
            									<!-- dibawah validation for textarea BOD -->
            									<textarea class="form-control" rows="2" name="comment_rt[]" <?php if($user == BOD_HP || $user == BOD_DL){echo "readonly";} ?>
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
            									<!-- HP -->
            									Harijanto Pribadi
<!-- 												<input type="" name="po_tgl_approved_rt[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_rt;?>" readonly="readonly">
												<input type="" name="po_approve_by_rt[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_rt;?>" readonly="readonly"> -->
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
												<input  type="checkbox" class="cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" <?php if($user==BOD_RT || $user==BOD_DL){echo " disabled";}?>>
												<?php
															} else {
																echo '<input type="checkbox" class="checked" disabled>';
															}
														} else {
												?>
												<input  type="checkbox" class="cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" <?php if($user==BOD_RT || $user==BOD_DL){echo " disabled";}?>>
												<?php
														}
													}
												?>
												<textarea class="form-control" rows="2" name="comment_hp[]" <?php if($user == BOD_RT || $user == BOD_DL){echo "readonly";} ?> 
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
												<!-- DL -->
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
												<input  type="checkbox" class="cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" <?php if($user==BOD_RT || $user==BOD_HP){echo " disabled";}?>>
												<?php
															} else {
																echo '<input type="checkbox" class="checked" disabled>';
															}
														} else {
												?>
												<input  type="checkbox" class="cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" <?php if($user==BOD_RT || $user==BOD_HP){echo " disabled";}?>>
												<?php
														}
													}
												?>
<!-- 												<input type="checkbox" class="cb-gadget" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this,'<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" 
												<?php 
													if($user==BOD_RT || $user==BOD_HP){echo "disabled ";}
														if (!empty($po_tgl_approved_rt)){
															if($po_approve_by_rt==1){
																echo "disabled";
															}else{
																echo "disabled";
															}
														}else if(!empty($po_tgl_approved_dl)){
															if ($po_approve_by_dl==1) {
																echo "disabled checked";
															}else{
																echo "disabled";
															}
														}else if(!empty($po_tgl_approved_hp)){
															if ($po_approve_by_hp==0) {
																echo "disabled";
															}
														}
												?>
												> -->
												<textarea class="form-control" rows="2" name="comment_dl[]" <?php if($user == BOD_RT || $user == BOD_HP){echo "readonly";} ?> 
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
											</b>
											<br>
										</div>
									</div>
            					</div>
            					<!-- Modal -->
            					<div class="modal fade" id="myModal" role="dialog">
            						<div class="modal-dialog modal-lg">
            							<!-- Modal content-->
            							<div class="modal-content" style="">
            								<div class="modal-header" style="background: #f26904;border-radius:5px;">
            									<button type="button" class="close" data-dismiss="modal" style="color:white;opacity:1;">&times;</button>
            									<h2 class="modal-title">Approval Details</h2>
            								</div>
            								<div class="modal-body">
            									<div class="row">
            										<div class="col-md-6">
            											<h3><u>PO Details</u></h3>
            											<div class="form-group">
            												<label for="ppo_number">Nomor PO :</label>
            												<input class="form-control" type="text" id="ppo_number" value="<?php echo $row['no_po']; ?>" readonly>
            												<label>Tanggal PO :</label>
            												<input class="form-control" type="text" id="tanggal_po" value="<?php echo $Tanggal_po ?>" readonly>
            												<label>Nama Vendor :</label>
            												<input class="form-control" type="text" id="nama_vendor" readonly>
            											</div>
            										</div>
            										<div class="col-md-6">
            											<h3><u>BOD's Note</u></h3>
            											<?php 

            												
            											?>

            											<label for="comment_rt">Richardus Teddy</label>
            											<textarea class="form-control" name="comment_rt[]" rows="5" <?php if($user == BOD_HP || $user == BOD_DL){echo "readonly";} ?>><?php if(!empty($po_comment_rt)){echo $po_comment_rt;} ?></textarea>
            											<label for="comment_hp">Harijanto Pribadi</label>
            											<textarea class="form-control" name="comment_hp[]" rows="5" <?php if($user == BOD_RT || $user == BOD_DL){echo "readonly";} ?>><?php if(!empty($po_comment_hp)){echo $po_comment_hp;} ?></textarea>
            											<label for="comment_dl">Dicky Lisal</label>
            											<textarea class="form-control" name="comment_dl[]" rows="5" <?php if($user == BOD_RT || $user == BOD_HP){echo "readonly";} ?>><?php if(!empty($po_comment_dl)){echo $po_comment_dl;} ?></textarea>
            										<!-- 	<?php 
	            											if ( isset( $_POST[ '$po_comment_rt' ] )) {
	            												$po_comment_rt = $_POST[ '$po_comment_rt' ];
	            												foreach ($po_comment_rt as $user) {
	            													if ( !empty( $user ) )
	            														echo ( $user . "<br />" );
	            												}
	            											}
	            											else {
	            												for ($x=0; $x< $num_rows; $x++) { $po_comment_rt[ $x ] = ""; }
	            											}
            											?>
            											<?php 
            												for ($x=0 ; $x < $num_rows ; $x++ ) { 
            													// # code...
            													echo '<textarea name="$comment_rt[]">' . $po_comment_rt[$x] . '</textarea>';
            													echo '<textarea name="$comment_hp[]">' . $po_comment_hp[$x] . '</textarea>';
            													echo '<textarea name="$comment_dl[]">' . $po_comment_dl[$x] . '</textarea><br />';
            											// <textarea name="$comment_rt" rows="5"> .$po_comment_rt[$x]. </textarea>
            												}
            											?> -->
            										</div>
            									</div>
            								</div>
            								<div class="modal-footer">
            									<h5 style="float:left;font-weight:bold;">Submitted by <?php echo $by ?> </h5>
            									<button type="button" class="btn btn-default" data-dismiss="modal">Save</button>
            								</div>
            							</div>
            						</div>
            					</div>
            					<!-- end of modal -->
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
				              				if (!empty($po_tgl_approved_hp)) {
				              					# code...
				              				}
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
				              							<input type="submit" class="btn btn-success submit" value="Submit" 
				              							<?php 
				              								if (!empty($po_tgl_approved_hp)) {
				              								 	if ($po_approve_by_hp==0) {
				              								 		echo 'style="display:none;"';
				              								 	} else {
				              								 		echo "";
				              								 	}
				              								} elseif (!empty($po_tgl_approved_dl)) {
				              									if ($po_approve_by_dl==0) {
				              										echo 'style="display:none;"';
				              									} else {
				              										echo "";
				              									}
				              								} 
				              								
				              							?>
				              							>
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
				              				} else {
				              	?>
								              	<tr>
								              		<td colspan="10">
								              			<input type="submit" class="btn btn-success submit" value="Submit" 
								              			<?php  
								              				if (!empty($po_tgl_approved_dl)) {
								              					if ($po_approve_by_dl==0) {
								              						echo 'style="display:none;"';
								              					}
								              				}
								              			?>
								              			>
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
						<?php 
						if ($user == BOD_RT) {
							if (!empty($po_tgl_approved_rt)) {
								echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
							} else {
								if (!empty($po_tgl_approved_hp)) {
									if ($po_approve_by_hp==0) {
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
									}
								}else if (!empty($po_tgl_approved_dl)){
									if ($po_approve_by_hp==0) {
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
									}
								} else {
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit">';
									}
							}
						} else if ($user == BOD_HP){
							if (!empty($po_tgl_approved_rt)) {
								echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
							} else {
								if (!empty($po_tgl_approved_hp)) {
									if ($po_approve_by_hp==0) {
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
									}
								}else if (!empty($po_tgl_approved_dl)){
									if ($po_approve_by_dl==0) {
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
									}else{
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit">';
									}
								} else {
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
									}
								}else if (!empty($po_tgl_approved_hp)){
									if ($po_approve_by_hp==0) {
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit" style="display:none;">';
									} else {
										echo '<input type="submit" class="btn btn-success submit sub-gadget" value="Submit">';
									}
								} else {
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
	<footer class="footer">
      <div class="container">
		<a href="#" id="mob-desk" class="rs-link" data-link-desktop="<?php  echo 'Switch to Desktop version'; ?>" data-link-responsive="Switch to Mobile version"></a>
        <p class="text-muted">Copyright &copy; 2016 PT. Hawk Teknologi Solusi, All Rights Reserved.</p>
      </div>
    </footer>
</body>
</html>
<script>
$('.checkbox-md').on('click',function(){
    $("#myModal").modal("show");
    $("#ppo_number").val($(this).closest('tr').children()[1].textContent);
    $("#tanggal_po").val($(this).closest('tr').children()[3].textContent);
    $("#nama_vendor").val($(this).closest('tr').children()[2].textContent);
});
$(document).ready(function(){
	$(window).on('load', function(){
	    var win = $(this);
	    if (win.width() > 897) { 
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
// window.onload=responsive;

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