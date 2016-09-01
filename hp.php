<?php
if($user=BOD_HP)
{
	if (
		!empty($_POST['po_tgl_approved_hp']) && !empty($_POST['po_approve_by_hp']) &&
		is_array($_POST['po_tgl_approved_hp']) && is_array($_POST['po_approve_by_hp']) &&
		count($_POST['po_tgl_approved_hp']) === count($_POST['po_approve_by_hp'])
		)
   	{
		//var RT   
		$tgl_app_rt    = $_POST['po_tgl_app_rt'];
		$po_app_rt     = $_POST['po_app_rt'];
		$rt_tgl        = $_POST['rt_tgl'];
		$keterangan_rt = $_POST['po_comment_rt'];
		//var DL
		$tgl_app_dl    = $_POST['po_tgl_app_dl'];
		$po_app_dl     = $_POST['po_app_dl'];
		$dl_tgl        = $_POST['dl_tgl'];
		$keterangan_dl = $_POST['po_comment_dl'];
		//var HP
		$tgl_approval  = $_POST['tgl_approval'];
		$tgl_pengajuan = $_POST['tgl_pengajuan'];
		$tanggal_hp    = $_POST['po_tgl_approved_hp'];
		$app_hp        = $_POST['po_approve_by_hp'];
		$po            = $_POST['no_po'];
		$vendor        = $_POST['nama_vendor'];
		$total         = $_POST['total'];
		$tgl_po        = $_POST['tgl_po'];
		$sub_by        = $_POST['sub_by'];
		$keterangan_hp = $_POST['po_comment_hp'];
		$y             = $_POST['x'];
		// $t             = $_POST['t']; 
		$total_ppo     = $_POST['total_ppo'];
		$end_user      = ucwords($user);
		$hp_tgl        = $_POST['hp_tgl'];
		$isUpdate      = false;
	
		include_once "mailserver.php";
		$message1   = new COM('CDO.Message');
		$message2   = new COM('CDO.Message');
		$message   = new COM('CDO.Message');
        $messageCon= new COM('CDO.Configuration') ;
		
		try 
		{
			$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpserver'] = HTSMAIL_SERVER;
			$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpserverport'] = HTSMAIL_PORT;
			$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpauthenticate'] = SMTP_BASICAUTHENTICATION;
			$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/sendusername'] = HTSMAIL_USERNAME;
			$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/sendpassword'] = HTSMAIL_PASSWORD;
			$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/sendusing'] = SMTP_USEPORT ;
			$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout'] = 30 ;
			$messageCon->Fields->Update();

		    $message->From    = 'indra <indraeff@hts.net.id>'; //ISP Integrated System [mailto:no-reply@hts.net.id] 
		    $message->To      = 'BOD <indraeff@hts.net.id>'; // BOD
		    $message->CC      = ''; // EMAIL HP
			$message->BCC     = '';
			$message->Subject = "Notifikasi Persetujuan pengajuan PO per $tanggal $bulan $tahun ";
	            
         	$message->HTMLBody = " <font style='font-size:16px; font-weight:bold;'>Berikut hasil persetujuan pengajuan PO :</font>
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
								<tr bgcolor='#FF5F55' style=' border-spacing: 0;border-collapse: collapse; font-weight:bold; border:solid 1px #555; color:#FFFFFF;text-transform:uppercase'>
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

			$totalprice   = 0;
			$total_appro  = 0;
			$total_rejek  = 0;

			$final_stats = '';
			$final_proses =0;
			$end_user2 = '';
			$tgl_approval2 = '';
			$end_user='HP';
			$approval2='';

			for ($i = 0; $i < count($po); $i++) 
			{

				$end_tgl_rt        = $tgl_app_rt[$i];
				$proval_rt         = $po_app_rt[$i];

				$end_tgl_dl        = $tgl_app_dl[$i];
				$proval_dl         = $po_app_dl[$i];

				$tgl_hp            = $tanggal_hp[$i];
				$prove_hp          = $app_hp[$i];
				$end_po            = $po[$i];
				$end_vendor        = $vendor[$i];
				$end_total         = $total[$i];
				$end_tgl_po        = $tgl_po[$i];
				$x                 = $y[$i];
				// $tt                = $t[$i];
				$end_keterangan_hp = $keterangan_hp[$i];
				$end_keterangan_rt = $keterangan_rt[$i];
				$end_keterangan_dl = $keterangan_dl[$i];

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

				if($proval_dl ==1)
				{
					$byapp_dl='A';
				} else {
					if ($end_tgl_dl =='') 
					{
						$byapp_dl='';
					}
					else 
					{
						$byapp_dl='R';
					}
				} //end if 
	
				if($end_keterangan_hp !=='')
				{
					$usr="<div style='text-align:left; padding:4px;'>$end_keterangan_hp (By $end_user)</div>";
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
				if($end_keterangan_dl !=='')
				{
					$appDL= BOD_DL;
					$end_appDL = ucwords($appDL);
					$usr_dl="<div style='text-align:left; padding:4px;'>$end_keterangan_dl (By $end_appDL)</div>";
				} else {
					$usr_dl='';
				}
				//status per BOD
				if($prove_hp ==1)
				{
					$total_appro++;
					$totalprice += $end_total;
					$grandtotal  = number_format($totalprice);
					$byapp='A';
				} else if ($prove_hp==0){
					if (!empty($end_tgl_dl)) {
						if ($proval_dl==0) {
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
				//final status
				if ($prove_hp==1) {
					if (!empty($end_tgl_dl)) {
						if ($proval_dl==1) {
							$final_proses = 1;
							$final_stats = 'Approved';
						} else {
							$final_proses=1;
							$final_stats = 'Rejected';
						}
					} else {
						$final_proses = 0;
						$final_stats = 'In Progress';
					}
				} else {
					if (!empty($end_tgl_dl)) {
						if ($proval_dl==1) {
							$final_proses = 1;
							$final_stats = 'Rejected';
						} else {
							$final_proses = 1;
							$final_stats = 'Rejected';
						}
					} else {
						$final_proses = 0;
						$final_stats = 'In Progress';
					}
				}
				// jika ini proses kedua
				if (!empty($end_tgl_dl)) {
					$end_user2 = 'DL';
					$tgl_approval2 = $end_tgl_dl;
					$approval2 = "<tr><td width='160' style=' font-weight:bold;'>$end_user2 approved</td>  <td width='30' align='center'> : </td> <td> $tgl_approval2 </td></tr>";
				}

				if($tgl_hp !=='')
				{
					$end_tgl=$tgl_hp;
					$tg=$tgl_hp;
				} else {
					$tg='-';
					date_default_timezone_set('Asia/Jakarta');
					$end_tgl= date('Y-m-d h:i:s');
				}

				$total_po    = number_format($end_total);

				$message->HTMLBody .= "<tr>
									<td style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$end_vendor</td>       
									<td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$end_po</td>
									<td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$end_tgl_po</td>
									<td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$total_po</td>							
									<td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp_rt</td>
									<td align='center' bgcolor='#eee' style='border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp</td>
									<td align='center' style='border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp_dl</td>
									<td align='center' style='border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$usr_rt $usr $usr_dl</td>
									<td align='center' style='border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$final_stats</td>
									</tr>
									";

			
				if (is_null( $end_tgl_dl ) || empty( $end_tgl_dl ))
				{
					$Database->autocommit( FALSE );
					$reshp= $Database->query( "Call SetPPO_Detail( '$user','$ppo','$end_po',$prove_hp,'$end_tgl','$end_keterangan_hp',$proval_dl, null )" );
				}
				else
				{
					$Database->autocommit( FALSE );
					$reshp= $Database->query( "Call SetPPO_Detail( '$user','$ppo','$end_po',$prove_hp,'$end_tgl','$end_keterangan_hp',$proval_dl,'$end_tgl_dl' )" );
				}
			
   
			} //end for hp
			if ($reshp) {
				$total_reject= $grand-$totalprice;
				$grandtotal2  = number_format($total_reject);
				if(empty($grandtotal))
		        {
					$total_app=0;
				} else {
					$total_app=$grandtotal;
				}			
				$message->HTMLBody .= "</table><br>";
				$message1->HTMLBody .= "</table><br>";
				$message2->HTMLBody .= "</table><br>";
				$message->HTMLBody .= "
									   <table style=' margin-top:10px; border:solid 1px #888; background:#f1f1f1; padding:8px;'>				      
									   <tr>
									   <td width='160' style=' font-weight:bold; '> $end_user approved</td>  <td width='30' align='center'> : </td> <td> $tgl_approval </td>
									   </tr>
									   $approval2
									   <tr>
									   <td style=' font-weight:bold;'>Total Approved </td> <td width='30' align='center'> : </td> <td> $total_appro/$total_ppo (Rp.$total_app) </td>
									   </tr>
									   </table>
									   <br>";
									   
				$message->Configuration = $messageCon;
				if ($final_proses==1) {
					$message->Send() ;
					// echo "sudah dikirim";
				} else {
					// echo "belum dikirim";
				}
				$isUpdate = true;
			}
        }
        catch (com_exception $e) {
        	// print "<hr>\n\n";
        	// print $e . "\n";
        	// print "<hr>\n\n";
        }
        
        if ( $isUpdate ) {
        	$Database->commit();
        	//redirect ke approval
        	echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data telah berhasil di submit';</script>";
        } else{
        	$Database->rollback();
        	//redirect ke approval
        	echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data gagal di submit';</script>";
        }

    } //end post
} //end if hp
?>