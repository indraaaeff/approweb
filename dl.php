<?php  
if($user=BOD_DL)
{
	if (
		!empty($_POST['po_tgl_approved_dl']) && !empty($_POST['po_approve_by_dl']) &&
		is_array($_POST['po_tgl_approved_dl']) && is_array($_POST['po_approve_by_dl']) &&
		count($_POST['po_tgl_approved_dl']) === count($_POST['po_approve_by_dl'])
		) 
	{
		// var hp
		$tgl_app_hp    = $_POST['po_tgl_app_hp'];
		$po_app_hp     = $_POST['po_app_hp'];
		$hp_tgl        = $_POST['hp_tgl'];
		$keterangan_hp = $_POST['po_comment_hp'];
		// var rt
		$tgl_app_rt    = $_POST['po_tgl_app_rt'];
		$po_app_rt     = $_POST['po_app_rt'];
		$rt_tgl        = $_POST['rt_tgl'];
		$keterangan_rt = $_POST['po_comment_rt'];
		// var dl
		$no_ppo        = $_POST['no_ppo'];
		$tgl_approval  = $_POST['tgl_approval'];
		$tgl_pengajuan = $_POST['tgl_pengajuan'];
		$tanggal_dl    = $_POST['po_tgl_approved_dl'];
		$app_dl        = $_POST['po_approve_by_dl'];
		$po            = $_POST['no_po'];
		$vendor        = $_POST['nama_vendor'];
		$total         = $_POST['total'];
		$tgl_po        = $_POST['tgl_po'];
		$sub_by        = $_POST['sub_by'];
		$keterangan_dl = $_POST['po_comment_dl'];
		$y             = $_POST['x'];
		// $t             = $_POST['t']; 
		$total_ppo     = $_POST['total_ppo'];	                                         	                                         
		$end_user      = ucwords($user);
		$dl_tgl        = $_POST['dl_tgl'];
		$isUpdate      = false;

		$Database->autocommit( FALSE );
		include_once "mailserver.php";
		$MailSubject = "APPROVED PPO NO. $no_ppo ($tanggal $bulan $tahun)";
		$MailBody = " <font style='font-size:16px; font-weight:bold;'>Berikut hasil persetujuan pengajuan PO :</font>
							<br>
							<table style=' margin-top:10px;'>
							<tr>
							<td style=' font-weight:bold;' width='160'>No PPO </td>  <td width='30' align='center'> : </td> <td> $ppo </td>
							</tr>
							<tr>
						    <td style=' font-weight:bold;' width='160'>Tanggal Pengajuan </td>  <td width='30' align='center'> : </td> <td> $tgl_pengajuan </td>
						    </tr>
							<tr>
							<td style=' font-weight:bold;' width='160'>Total PO </td>  <td width='30' align='center'> : </td> <td> $total_ppo (<i>Rp.$end_grand</i>)</td>
							</tr> 
							<tr>
							<td style=' font-weight:bold;'>Submitted By </td> <td width='30' align='center'> : </td> <td> $sub_by </td>
							</tr>		 
							</table>
							<br>
							<table  style=' border-spacing: 0; margin-top:2px; margin-bottom:2px; border-collapse: collapse; border:solid 1px #555; '>	                  
							<tr bgcolor='#00DF55' style=' border-spacing: 0;border-collapse: collapse; font-weight:bold; border:solid 1px #555; color:#FFFFFF;text-transform:uppercase'>
							<td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> NAMA VENDOR </td>
							<td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #555; '> NO PO </td>
							<td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> TGL PO </td>
							<td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> TOTAL </td>
							<td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> RT </td>
							<td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> HP </td>
							<td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> DL </td>
							<td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> NOTE </td>
							<td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> STATUS </td>
							</tr>";


		$totalprice  = 0; 
		$total_appro = 0;
		$total_rejek = 0;
		
		$final_stats = '';
		$final_proses = 0;
		$end_user2 = '';
		$tgl_approval2 = '';
		$end_user='DL';
		$approval2='';
		$ppo_rejected=0;

		// getdata untuk sinkronisasi
		$PPO_TableDetail2 = $Database->query( "Call GetPPO_Detail( '$no_ppo' )" );
		$row = $PPO_TableDetail2->fetch_assoc();
		$po_tgl_approved_rt = $row['tgl_approved_rt'];
		$po_tgl_approved_hp = $row['tgl_approved_hp'];
		// cek proses BOD_RT
		if (!empty($po_tgl_approved_rt) && !is_null($po_tgl_approved_rt)) {
			$isUpdate = false;
			$final_proses=1;
		}
		//cek proses BOD_DL
		else if (!empty($po_tgl_approved_hp) && !is_null($po_tgl_approved_hp)) {
			$isUpdate= true;
			$final_proses=1;
			$PPO_TableDetail2->data_seek(0);
			$i=0;
			while ($row = $PPO_TableDetail2->fetch_assoc()) {
				$tgl_app_hp[$i] = $row['tgl_approved_hp'];
				$po_app_hp[$i]  = $row['approve_by_hp'];
				$i++;
			}
		} else {
			$isUpdate = true;
		}
		$PPO_TableDetail2->free();
		$Database->next_result();

		if ($isUpdate) 
		{
			for ($i = 0; $i < count($po); $i++) 
			{
				$end_tgl_rt         = $tgl_app_rt[$i];
				$proval_rt          = $po_app_rt[$i];

				$end_tgl_hp         = $tgl_app_hp[$i];
				$proval_hp          = $po_app_hp[$i];

				$tgl_dl             = $tanggal_dl[$i];
				$prove_dl           = $app_dl[$i];  	                                              
				$end_po             = $po[$i];
				$end_vendor         = $vendor[$i];
				$end_total          = $total[$i];
				$end_tgl_po         = $tgl_po[$i];
				$x                  = $y[$i];
				// $tt                    = $t[$i];
				$end_keterangan_dl  = $keterangan_dl[$i];
				$end_keterangan_rt  = $keterangan_rt[$i];
				$end_keterangan_hp  = $keterangan_hp[$i];

				if($proval_hp ==1)
				{
					$byapp_hp='A';
				} 
				else 
				{
					if ($end_tgl_hp =='') 
					{
						$byapp_hp='';
					} else {
						$byapp_hp='R';
					}
				} //end if 

				if($proval_rt ==1)
				{
					$byapp_rt='A';
				} else {

					if ($end_tgl_rt =='') 
					{
						$byapp_rt='';
					} else {
						$byapp_rt='R';
					}
				} //end if

				if($end_keterangan_dl !=='')
				{
					$usr="<div style='text-align:left; padding:4px;'>$end_keterangan_dl (By $end_user)</div>";
				} else {
					$usr='';
				}

				if($end_keterangan_rt !=='')
				{
					$appRT= BOD_RT;
					$end_appRT = ucwords($appRT);
					$usr_rt="<div style='text-align:left; padding:4px;'>$end_keterangan_rt (By $end_appRT)</div>";
				} else {
					$usr_rt='';
				}
				if($end_keterangan_hp !=='')
				{
					$appHP= BOD_HP;
					$end_appHP = ucwords($appHP);
					$usr_hp="<div style='text-align:left; padding:4px;'>$end_keterangan_hp (By $end_appHP)</div>";
				} else {
					$usr_hp='';
				}
				//status per BOD
				if($prove_dl ==1)
				{
					if (!empty($end_tgl_hp) && $proval_hp==0) {
						$byapp='-';
						$total_rejek++;	
					} else {
						$total_appro++;
						$totalprice += $end_total;
						$grandtotal  = number_format($totalprice);
						$byapp='A';
					}
				} else if ($prove_dl==0){
					if (!empty($end_tgl_hp)) {
						if ($proval_hp==0) {
							$byapp='-';
							$total_rejek++;		
						} else {
							$byapp = 'R';
			   				$total_rejek++;
						}
					} else {
						$byapp='R';
						$total_rejek++;
					}
				}
				// cek total reject untuk kirim email
				if (!empty($tgl_dl)) {
					if ($prove_dl==0) {
						$ppo_rejected++;
					}
				}
				//final status
				if ($prove_dl==1) {
					if (!empty($end_tgl_hp)) {
						if ($proval_hp==1) {
							$final_stats = 'Approved';
						} else {
							$final_stats = 'Rejected';
						}
					} else {
						$final_stats = 'In Progress';
					}
				} else {
					if (!empty($end_tgl_hp)) {
						if ($proval_hp==1) {
							$final_stats = 'Rejected';
						} else {
							$final_stats = 'Rejected';
						}
					} else {
						$final_stats = 'In Progress';
					}
				}
				// jika ini proses kedua
				if (!empty($end_tgl_hp)) {
					$end_user2 = 'HP';
					$tgl_approval2 = $end_tgl_hp;
					$approval2 = "<tr><td width='160' style=' font-weight:bold;'>$end_user2 approved</td>  <td width='30' align='center'> : </td> <td> $tgl_approval2 </td></tr>";
				}
				if($tgl_dl !=='')
				{
					$end_tgl=$tgl_dl;
					$tg=$tgl_dl;
				} else {
					$tg='-';
					date_default_timezone_set('Asia/Jakarta');
					$end_tgl= date('Y-m-d h:i:s');
				}//end

				$total_po    = number_format($end_total);
				$MailBody .= "<tr>
										<td style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$end_vendor</td>       
										<td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$end_po</td>
										<td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$end_tgl_po</td>
										<td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$total_po</td>
										<td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp_rt</td>
										<td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp_hp</td>
										<td align='center' bgcolor='#eee' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp</td>
										<td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$usr_rt $usr_hp $usr</td>
										<td align='center' style='border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$final_stats</td>
										</tr>
										";

				if (is_null( $end_tgl_hp ) || empty( $end_tgl_hp ))
				{	
					$resdl= $Database->query( "Call SetPPO_Detail( '$user','$ppo','$end_po',$prove_dl,'$end_tgl','$end_keterangan_dl',$proval_hp, null )" );
				}
				else
				{
	             	$resdl= $Database->query( "Call SetPPO_Detail( '$user','$ppo','$end_po',$prove_dl,'$end_tgl','$end_keterangan_dl',$proval_hp,'$end_tgl_hp' )" );
				}

			} //end for dl
		}

		if ( $isUpdate ) {
			$total_reject= $grand-$totalprice;
			$grandtotal2  = number_format($total_reject);
			if(empty($grandtotal))
			{
				$total_app=0;
			} else {
				$total_app=$grandtotal;
			}			
			$MailBody .= "</table><br>
						<table style=' margin-top:10px; border:solid 1px #888; background:#f1f1f1; padding:8px;'>
						<tr>
						<td width='160' style=' font-weight:bold; '> $end_user approved</td>  <td width='30' align='center'> : </td> <td> $tgl_approval </td>
						</tr>
						$approval2
						<tr>
						<td style=' font-weight:bold;'>Total Approved </td> <td width='30' align='center'> : </td> <td> $total_appro/$total_ppo (Rp.$total_app)</td>
						</tr>
						</table>
						<br>";

			//cek sudah final atau belum
			if ($final_proses==1 || $ppo_rejected==$total_ppo) {
				include_once "SendMail.php";
			} 
			if ($isUpdate) {
				$Database->commit();
				echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data telah berhasil di submit';</script>";
			} else {
				$Database->rollback();	
				echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data gagal di submit';</script>";
			}

		} else {
			$Database->rollback();
			echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data gagal di submit';</script>";
		} 
		$Database->autocommit( TRUE );
    }  //end post
} //end if dl
?>