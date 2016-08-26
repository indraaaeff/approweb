<?php 
if($user=BOD_RT)
{
	if (
		!empty($_POST['po_tgl_approved_rt']) && !empty($_POST['po_approve_by_rt']) &&
		is_array($_POST['po_tgl_approved_rt']) && is_array($_POST['po_approve_by_rt']) &&
		count($_POST['po_tgl_approved_rt']) === count($_POST['po_approve_by_rt'])
		) 
	{
		//var HP
		$tgl_app_hp    = $_POST['po_tgl_app_hp'];
		$po_app_hp     = $_POST['po_app_hp'];
		$hp_tgl        = $_POST['hp_tgl'];
		$keterangan_hp = $_POST['po_comment_hp'];
		//var DL
		$tgl_app_dl    = $_POST['po_tgl_app_dl'];
		$po_app_dl     = $_POST['po_app_dl'];
		$dl_tgl        = $_POST['dl_tgl'];
		$keterangan_dl = $_POST['po_comment_dl'];
		//var RT
		$tgl_pengajuan = $_POST['tgl_pengajuan'];
		$tanggal_rt    = $_POST['po_tgl_approved_rt'];
		$app_rt        = $_POST['po_approve_by_rt'];
		$po            = $_POST['no_po'];
		$vendor        = $_POST['nama_vendor'];
		$total         = $_POST['total'];
		$tgl_po        = $_POST['tgl_po'];
		$sub_by        = $_POST['sub_by'];
		$keterangan_rt = $_POST['po_comment_rt'];
		$y             = $_POST['x'];
		// $t             = $_POST['t']; 
		$total_ppo     = $_POST['total_ppo'];
		$ppn           = $_POST['ppn'];
		$end_user      = ucwords($user);
		$rt_tgl        = $_POST['rt_tgl'];	 
		$isUpdate      = false;

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
			$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout'] = 30 ;
			$messageCon->Fields->Update();

			$message->From     = 'indra <indraeff@hts.net.id>'; //ISP Integrated System [mailto:no-reply@hts.net.id] 
		    $message->To       = 'BOD <indraeff@hts.net.id>'; // BOD
		    $message->CC       = ''; // EMAIL RT
		    $message->BCC      = '';
		    $message->Subject  = "Notifikasi Persetujuan pengajuan PO per $tanggal $bulan $tahun ";

		    $message->HTMLBody = "  <font style='font-size:16px; font-weight:bold;'>Berikut hasil persetujuan pengajuan PO :</font>
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
							    <table style=' border-spacing: 0; margin-top:2px; margin-bottom:2px; border-collapse: collapse; border:solid 1px #555; '>	                  
							    <tr bgcolor='#0099CC' style=' border-spacing: 0;border-collapse: collapse; font-weight:bold; border:solid 1px #555; color:#FFFFFF;text-transform:uppercase'>
							    <td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> NAMA VENDOR </td>
							    <td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #555; '> NO PO </td>
							    <td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> TGL PO </td>
							    <td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> TOTAL </td>
							    <td align='center' width='76' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> RT </td>
							    <td align='center' width='76' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> HP </td>
							    <td align='center' width='76' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> DL </td>
							    <td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> NOTE </td>
							    </tr>";

		    $totalprice  = 0;
		    $total_appro = 0;					   
		    $total_rejek = 0;

		    $totalprice_hp  = 0;
		    $total_appro_hp = 0;
		    $total_rejek_hp = 0;

		    $totalprice_dl  = 0;
		    $total_appro_dl = 0;
		    $total_rejek_dl = 0;

		    for ($i = 0; $i < count($po); $i++) 
		    {
		    	$end_tgl_hp        = $tgl_app_hp[$i];
		    	$proval_hp         = $po_app_hp[$i];
		    	$end_tgl_dl        = $tgl_app_dl[$i];
		    	$proval_dl         = $po_app_dl[$i];
		    	$tgl_rt            = $tanggal_rt[$i];
		    	$prove_rt          = $app_rt[$i];
		    	$end_po            = $po[$i];
		    	$end_vendor        = $vendor[$i];
		    	$end_total         = $total[$i];
		    	$end_tgl_po        = $tgl_po[$i];	
		    	$x                 = $y[$i];
		    	// $tt                = $t[$i];
		    	$end_keterangan_rt = $keterangan_rt[$i];
		    	$end_keterangan_hp = $keterangan_hp[$i];
		    	$end_keterangan_dl = $keterangan_dl[$i];
		    	$end_ppn           = $ppn;

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

				if($proval_dl ==1)
				{
					$byapp_dl='Approved';
				} 
				else 
				{
					if ($end_tgl_dl =='') 
					{
						$byapp_dl='';
					} 
					else 
					{
						$byapp_dl='Reject';
					}
				} //end if 

			   if($end_keterangan_rt !=='')
			   {
			   		$usr="<div style='text-align:left; padding:4px;'>$end_keterangan_rt (By $end_user)</div>";
			   }else {
			   		$usr='';
			   }

			   if($prove_rt ==1)
			   {
			   		$total_appro++;
			   		$byapp='Approved';
			   		$totalprice += $end_total;
			   		$grandtotal  = number_format($totalprice);	
			   }
			   else 
			   {
			   		$byapp='Reject';
			   		$total_rejek++;
			   }

			   if($tgl_rt !=='')
			   {
			   		$tg=$tgl_rt;
			   		$end_tgl=$tgl_rt;
			   }
			   else 
			   {
			   		$tg='-';
			   		date_default_timezone_set('Asia/Jakarta');
			   		$end_tgl= date('Y-m-d h:i:s');
			   }

			   if($end_keterangan_hp !=='')
			   {
			   		$appHP= BOD_HP;
			   		$end_appHP = ucwords($appHP);
			   		$usr_hp="<div style=' text-align:left; padding:4px;'>$end_keterangan_hp (By $end_appHP)</div>";
			   }else {
			   		$usr_hp='';
			   }
			   if($end_keterangan_dl !=='')
			   {
			   		$appDL= BOD_DL;
			   		$end_appDL = ucwords($appDL);
			   		$usr_dl="<div style='text-align:left; padding:4px;'>$end_keterangan_dl (By $end_appDL)</div>";
			   }else {
			   		$usr_dl='';
			   }

			   $total_po    = number_format($end_total);
			   $message->HTMLBody .= "<tr>
								   <td style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$end_vendor</td>       
								   <td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$end_po</td>
								   <td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$end_tgl_po</td>
								   <td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '> $total_po</td>
								   <td align='center' bgcolor='#eee' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp</td>
								   <td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp_hp</td>
								   <td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp_dl</td>
								   <td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$usr $usr_hp $usr_dl</td>
								   </tr>
								   ";
			   //------------------------------------------------------------------------------------------------------------------------------------
			  
			  
/* 			  echo isset( $user ). ', ' . isset( $ppo ) . ', ' . isset( $end_po ) . ', ' . isset(  $prove_rt ) .
				', ' . isset( $end_tgl ) . ', ' ;
			if ( isset( $end_keterangan_rt ) )
				echo 'YES <br/>';
			else
				echo 'NO <br/>';
			  echo $user . ', ' . $ppo . ', ' . $end_po . ', ' . $prove_rt . ', ' . $end_tgl . ', ' . $end_keterangan_rt . '<br/>';
 */              
			  $Database->autocommit( FALSE );
			  $res= $Database->query( "Call SetPPO_Detail( '$user','$ppo','$end_po',$prove_rt,'$end_tgl','$end_keterangan_rt', 0, null )" );			 

            }//end for

			if ( $res ) {
				$total_reject= $grand-$totalprice;
				$grandtotal2  = number_format($total_reject);

				if(empty($grandtotal))
				{
					$total_app=0;
				} else {
					$total_app=$grandtotal;
				}			  

				$message->HTMLBody  .= "</table><br>";
				$message->HTMLBody .= "
										<table style=' margin-top:10px; border:solid 1px #888; background:#f1f1f1; padding:8px;'>											      
										<tr>
										<td width='160' style=' font-weight:bold;'>Approved By </td>  <td width='30' align='center'> : </td> <td> $end_user </td>
										</tr>
										<tr>
										<td style=' font-weight:bold;'>Tanggal Approval </td> <td width='30' align='center'> : </td> <td> $tgl_rt </td>
										</tr>
										<tr>
										<td style=' font-weight:bold;'>Total Approved </td> <td width='30' align='center'> : </td> <td> $total_appro (Rp.$total_app)</td>
										</tr>
										<tr>
										<td style=' font-weight:bold;'>Total Reject </td>  <td width='30' align='center'> : </td> <td> $total_rejek (Rp.$grandtotal2)</td>
										</tr>			 											    
										</table>
										<br>
										";
				$message->Configuration = $messageCon;
				$message->Send() ;
				$isUpdate = true;
			}
        }
        catch (com_exception $e) {
//        	print "<hr>\n\n";
//        	print $e . "\n";
//        	var_dump($e);
//        	print "<hr>\n\n";
        }

        if ( $isUpdate ) {
			$Database->commit();
			// redirect ke halaman approval
        	echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data telah berhasil di submit';</script>";
        } else{
			$Database->rollback();
			// redirect ke halaman approval
        	echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data gagal di submit';</script>";
        }
   	}  //end post
} // end if rt
?>