<?php 
	if ($user == BOD_RT) 
	{
?>
		Richardus Teddy
		<input type="" name="po_tgl_approved_hp[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_hp;?>" readonly="readonly">
		<input type="" name="po_approve_by_hp[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_hp;?>" readonly="readonly">
		<?php 
		if (!empty($po_tgl_approved_rt)) {
			if ($po_approve_by_rt==1) {
			echo '<input type="checkbox" class="checked" disabled checked>';//checked disabled
			} else {
			echo '<span class="glyphicon glyphicon-remove reject"></span>';
			}
		}
		else
		{
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
		}
?>

<!-- diatas validation for checkbox -->
<!-- dibawah validation for textarea BOD -->
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
	<!-- HP -->
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
		} ?>
		Richardus Teddy
		<input type="" name="po_tgl_approved_hp[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_hp;?>" readonly="readonly">
		<input type="" name="po_approve_by_hp[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_hp;?>" readonly="readonly">
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
}
?>

<!-- diatas validation for checkbox -->
<!-- dibawah validation for textarea BOD -->
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
	<!-- HP -->
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