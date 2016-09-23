<div class="gadget">
                                                <input type="hidden" name="key" value="<?php echo $key;?>">
								<input type="hidden" name="u" value="<?php echo $_GET['u'];?>">
								<input type="hidden" name="total_ppo" id="total_ppo" value="<?php echo $total_ppo;?>"	>
								<input type="hidden" name="tgl_pengajuan" value="<?php echo $tgl_pengajuan;?>">	
            						<ul class="nav nav-tabs responsive" id="myTab">
            							<li class="">
            								<a href="#<?php echo 'po_details'.$no; ?>">
            									<?php $Tanggal_po = date( 'd-m-Y', strtotime( $row['tgl_po'] )); ?>
            									<b>No PO :</b> <?php echo $row['no_po']; ?>
                                                                  <span class="glyphicon glyphicon-ok notifcekall" style="color:green; display:none;"></span><br>
                                                                  <b>Tanggal PO :</b> <?php echo $Tanggal_po; ?><br>
                                                                  <b>Nama Vendor :</b> <?php echo $row['nama_vendor']; ?><br><br>
            									
            									<?php 
            									if(!empty($po_tgl_approved_rt)){
            										if($po_approve_by_rt==0){
            											echo '<p class="status" style="color:red;font-weight:bold;">==&nbsp; Rejected &nbsp;==</p>';
            										} else {
            											echo '<p class="status" style="color:green;font-weight:bold;">==&nbsp; Approved &nbsp;==</p>';
            										}
            									} else if (!empty($po_tgl_approved_hp) && !empty($po_tgl_approved_dl)) {
            										if ($po_approve_by_hp==0 && $po_approve_by_dl==0) {
            											echo '<p class="status"style="color:red;font-weight:bold;">==&nbsp; Rejected &nbsp;==</p>';
            										} else if ($po_approve_by_hp==1  && $po_approve_by_dl==1){
            											echo '<p class="status" style="color:green;font-weight:bold;">==&nbsp; Approved &nbsp;==</p>';
            										} else if ($po_approve_by_hp==0 && $po_approve_by_dl==1) {
            											echo '<p class="status"style="color:red;font-weight:bold;">==&nbsp; Rejected &nbsp;==</p>';
            										} else if ($po_approve_by_hp==1  && $po_approve_by_dl==0){
            											echo '<p class="status" style="color:red;font-weight:bold;">==&nbsp; Rejected &nbsp;==</p>';
            										}
            									} else if (!empty($po_tgl_approved_hp)){
            										if ($po_approve_by_hp==0) {
            											echo '<p class="status"style="color:red;font-weight:bold;">==&nbsp; Rejected &nbsp;==</p>';
            										} else {
            											echo '<p class="status" style="color:orange;font-weight:bold;">In Progress</p>';
            										}
            									} else if (!empty($po_tgl_approved_dl)){
            										if($po_approve_by_dl==0){
            											echo '<p class="status"style="color:red;font-weight:bold;">==&nbsp; Rejected &nbsp;==</p>';
            										} else {
            											echo '<p class="status" style="color:orange;font-weight:bold;">In Progress</p>';
            										}
            									}else {
            										echo '<p class="status" style="color:orange;font-weight:bold;">In Progress</p>';
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
            									<input type="hidden" name="ppn" value="<?php echo $ppn; ?>">
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
            											if (!empty($po_tgl_approved_hp) && $po_approve_by_hp==0) {
	            										echo '<input type="checkbox" class="checked" disabled >';
	            									} else if (!empty($po_tgl_approved_dl) && $po_approve_by_dl==0) {
	            										echo '<input type="checkbox" class="checked" disabled >';
	            									} else {
		            									echo '<input type="checkbox" class="checked" disabled checked>';
	            									}
														// echo '<input type="checkbox" class="checked" disabled checked>';//checked disabled
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
			            						<input type="hidden" name="po_approve_by_rt[]" class='<?php if (empty($po_tgl_approved_rt)) { if (!empty($po_tgl_approved_hp) || !empty($po_tgl_approved_dl)) { if ($po_approve_by_hp==1 || $po_approve_by_dl==1) {echo "approved";} else {echo "rejected";}}else {echo "approved";}}?>' id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_rt;?>" readonly="readonly">
			            						<input type="hidden" name="no_po[]"  value="<?php echo $row['no_po'];?>">
			            						<input type="hidden" name="total[]"  value="<?php echo $row['total'];?>">
			            						<input type="hidden" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
			            						<input type="hidden" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
			            						<input type="hidden" name="x[]" class='<?php if (empty($po_tgl_approved_rt)) { if (!empty($po_tgl_approved_hp) || !empty($po_tgl_approved_dl)) { if ($po_approve_by_hp==1 || $po_approve_by_dl==1) {echo "xy";} else {echo "rejected";}}else {echo "xy";}}?>' id="x<?php echo $no;?>">
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
												<input type="hidden" name="user" id="<?php echo $user.$no; ?>" value="<?php echo $user; ?>">
												<input type="hidden" name="po_tgl_approved_hp[]" class="datetime" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_hp;?>" readonly>
												<input type="hidden" name="po_approve_by_hp[]" class='<?php if (empty($po_tgl_approved_hp)) { if (!empty($po_tgl_approved_dl)) { if ($po_approve_by_dl==1) {echo "approved";} else {echo "rejected";}}else {echo "approved";}}?>' id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_hp;?>" readonly>
												<input type="hidden" name="no_po[]"  value="<?php echo $row['no_po'];?>">
												<input type="hidden" name="total[]"  value="<?php echo $row['total'];?>">
												<input type="hidden" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
												<input type="hidden" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
												<input type="hidden" name="x[]" class='<?php if (empty($po_tgl_approved_hp)) { if (!empty($po_tgl_approved_dl)) { if ($po_approve_by_dl==1) {echo "xy";} else {echo "rejected";}}else {echo "xy";}}?>' id="x<?php echo $no;?>">
												<input type="hidden" name="no_ppo" value="<?php echo $PPO_Number;?>">
												<input type="hidden" name="sub_by" value="<?php echo $by;?>">
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
                                                                              <!-- bod HP session -->
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
            										} else if ($user == BOD_DL){
												?>
													<!-- rt on DL -->
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
																echo '<span class="glyphicon glyphicon-remove reject"></span>';
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
												<!-- tambah variabel RT DAN DL di login HP -->
												<input  type="hidden"  name="po_app_rt[]"     value="<?php echo $po_approve_by_rt;?>" >
												<input  type="hidden"  name="po_tgl_app_rt[]" value="<?php echo $po_tgl_approved_rt;?>" >
												<input  type="hidden"  name="po_app_hp[]"     value="<?php echo $po_approve_by_hp;?>" >
												<input  type="hidden"  name="po_tgl_app_hp[]" value="<?php echo $po_tgl_approved_hp;?>" >
												<!-- variabel DL -->
												<input type="hidden" name="user" id="<?php echo $user.$no; ?>" value="<?php echo $user; ?>">
												<input type="hidden" name="po_tgl_approved_dl[]" class="datetime" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_dl;?>" readonly>
												<input type="hidden" name="po_approve_by_dl[]" class='<?php if (empty($po_tgl_approved_dl)) { if (!empty($po_tgl_approved_hp)) { if ($po_approve_by_hp==1) {echo "approved";} else {echo "rejected";}}else {echo "approved";}}?>' id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_dl;?>" readonly>
												<input type="hidden" name="no_po[]"  value="<?php echo $row['no_po'];?>">
												<input type="hidden" name="total[]"  value="<?php echo $row['total'];?>">
												<input type="hidden" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
												<input type="hidden" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
												<input type="hidden" name="x[]" class='<?php if (empty($po_tgl_approved_dl)) { if (!empty($po_tgl_approved_hp)) { if ($po_approve_by_hp==1) {echo "xy";} else {echo "rejected";}}else {echo "xy";}}?>' id="x<?php echo $no;?>">
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
                                                                                    <!-- bod DL session -->
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
													}
            									?>
											</b>
											<br>
										</div>
									</div>
                                                      <div class="col-xs-3 nopadding detail_po_m">
                                                            <!-- <a href="">Detail PO</a> -->
                                                            <a href="#mymodal" data-toggle="modal" id="<?php echo $no_po; ?>" data-target="#modal_detail" class="po_modal">Detail</a>
                                                      </div>
                                          </div>