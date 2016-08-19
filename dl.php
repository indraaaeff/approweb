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

		include_once "mailserver.php";
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
			$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout'] = 60 ;
			$messageCon->Fields->Update();

			$message->From     = 'indra <indraeff@hts.net.id>'; //ISP Integrated System [mailto:no-reply@hts.net.id] 
			$message->To       = 'BOD <indraeff@hts.net.id>'; // BOD
			$message->CC       = ''; // EMAIL DL
			$message->BCC      = '';
			$message->Subject  = "Notifikasi Persetujuan pengajuan PO per $tanggal $bulan $tahun ";

			$message->HTMLBody = " <font style='font-size:16px; font-weight:bold;'>Berikut hasil persetujuan pengajuan PO :</font>
								<br>
								<table style=' margin-top:10px;'>
								<tr>
								<td style=' font-weight:bold;' width='160'>No PPO </td>  <td width='30' align='center'> : </td> <td> $ppo </td>
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
								<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> NAMA VENDOR </td>
								<td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #555; '> NO PO </td>
								<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> TGL PO </td>
								<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> TOTAL </td>
								<td align='center' width='76' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> RT </td>
								<td align='center' width='76' style='padding:8px;   border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> HP </td>
								<td align='center' width='76' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> DL </td>
								<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> NOTE </td>
								</tr>";

			$totalprice  = 0; 
			$total_appro = 0;
			$total_rejek = 0;
			for ($i = 0; $i < count($po); $i++) 
			{
				$end_tgl_rt = $tgl_app_rt[$i];
				$proval_rt  = $po_app_rt[$i];

				$end_tgl_hp        = $tgl_app_hp[$i];
				$proval_hp         = $po_app_hp[$i];

				$tgl_dl                = $tanggal_dl[$i];
				$prove_dl              = $app_dl[$i];  	                                              
				$end_po                = $po[$i];
				$end_vendor            = $vendor[$i];
				$end_total             = $total[$i];
				$end_tgl_po            = $tgl_po[$i];
				$x                     = $y[$i];
				// $tt                    = $t[$i];
				$end_keterangan_dl     = $keterangan_dl[$i];
				$end_keterangan_rt     = $keterangan_rt[$i];
				$end_keterangan_hp     = $keterangan_hp[$i];

				if($proval_hp ==1)
				{
					$byapp_hp='Approved';
				} 
				else 
				{
					if ($end_tgl_hp =='') 
					{
						$byapp_hp='';
					} else {
						$byapp_hp='Reject';
					}
				} //end if 

				if($proval_rt ==1)
				{
					$byapp_rt='Approved';
				} else {

					if ($end_tgl_rt =='') 
					{
						$byapp_rt='';
					} else {
						$byapp_rt='Reject';
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

				if($prove_dl ==1)
				{
					$total_appro++;
					$totalprice += $end_total;
					$grandtotal  = number_format($totalprice);
					$byapp='Approved';
				} else {
					$byapp='Reject';
					$total_rejek++;
	            } //end
	                                          
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
				$message->HTMLBody .= "<tr>
				<td  style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$end_vendor</td>       
				<td  align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$end_po</td>
				<td  align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$end_tgl_po</td>
				<td  align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$total_po</td>
				<td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp_rt</td>
				<td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp_hp</td>
				<td align='center' bgcolor='#eee' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp</td>
				<td  align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$usr_rt $usr_hp $usr</td>
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
			$total_reject= $grand-$totalprice;
			$grandtotal2  = number_format($total_reject);
			if(empty($grandtotal))
			{
				$total_app=0;
			} else {
				$total_app=$grandtotal;
			}			
			$message->HTMLBody .= "</table><br>";
			$message->HTMLBody .= "<table style=' margin-top:10px; border:solid 1px #888; background:#f1f1f1; padding:8px;'>
									<tr>
									<td width='160' style=' font-weight:bold;'>Approved By </td>  <td width='30' align='center'> : </td> <td> $end_user </td>
									</tr>
									<tr>
									<td style=' font-weight:bold;'>Tanggal Approval </td> <td width='30' align='center'> : </td> <td> $tanggal $bulan $tahun </td>
									</tr>
									<tr>
									<td style=' font-weight:bold;'>Total Approved </td> <td width='30' align='center'> : </td> <td> $total_appro (Rp.$total_app)</td>
									</tr>
									<tr>
									<td style=' font-weight:bold;'>Total Reject </td>  <td width='30' align='center'> : </td> <td> $total_rejek (Rp.$grandtotal2)</td>
									</tr>					    
									</table>
									<br>";
			$message->Configuration = $messageCon;
			$message->Send() ;

		}
		catch (com_exception $e) {
			print "<hr>\n\n";
			print $e . "\n";
			print "<hr>\n\n";
		}
		if (!$resdl) {

			echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data gagal di submit';</script>";

		} else{

			echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data telah berhasil di submit';</script>";
		} 
    }  //end post
} //end if dl
?>