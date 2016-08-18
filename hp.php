<?php
if($user=BOD_HP){
	if (
		!empty($_POST['po_tgl_approved_hp']) && !empty($_POST['po_approve_by_hp']) &&
		is_array($_POST['po_tgl_approved_hp']) && is_array($_POST['po_approve_by_hp']) &&
		count($_POST['po_tgl_approved_hp']) === count($_POST['po_approve_by_hp'])
	   )
   	{
   
    $tgl_app_rt    = $_POST['po_tgl_app_rt'];
	$po_app_rt     = $_POST['po_app_rt'];
	$keterangan_rt = $_POST['po_comment_rt'];
	$rt_tgl        = $_POST['rt_tgl'];
	
    $tgl_app_dl    = $_POST['po_tgl_app_dl'];
	$po_app_dl     = $_POST['po_app_dl'];
    $keterangan_dl = $_POST['po_comment_dl'];
    $dl_tgl        = $_POST['dl_tgl'];
	

/*1*/   $tanggal_hp    = $_POST['po_tgl_approved_hp'];
/*2*/   $app_hp        = $_POST['po_approve_by_hp'];
/*3*/	$po            = $_POST['no_po'];
/*4*/	$vendor        = $_POST['nama_vendor'];
/*5*/	$total         = $_POST['total'];
/*6*/	$tgl_po        = $_POST['tgl_po'];
/*7*/	$sub_by        = $_POST['sub_by'];
/*8*/	$keterangan_hp = $_POST['po_comment_hp'];
/*9*/	$y             = $_POST['x'];
/*10*/	$t             = $_POST['t']; 
/*11*/	$total_ppo     = $_POST['total_ppo'];
/*12*/	$end_user      = ucwords($user);
	    $hp_tgl        = $_POST['hp_tgl'];
	
	
	
                  include_once "mailserver.php";
                  $message1   = new COM('CDO.Message');
                  $message2   = new COM('CDO.Message');
                  $message   = new COM('CDO.Message');
                  $messageCon= new COM('CDO.Configuration') ;
			 try {
				$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpserver'] = HTSMAIL_SERVER;
				$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpserverport'] = HTSMAIL_PORT;
				$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpauthenticate'] = SMTP_BASICAUTHENTICATION;
				$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/sendusername'] = HTSMAIL_USERNAME;
				$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/sendpassword'] = HTSMAIL_PASSWORD;
				$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/sendusing'] = SMTP_USEPORT ;
				$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout'] = 60 ;
				$messageCon->Fields->Update();

			    $message->From    = 'rivan <rivan.pahlawan@hts.net.id>'; //ISP Integrated System [mailto:no-reply@hts.net.id] 
			    $message->To      = 'rivan <rivan.pahlawan@hts.net.id>'; // BOD
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
									<td style=' font-weight:bold;' width='160'>Total PO </td>  <td width='30' align='center'> : </td> <td> $total_ppo (<i>Rp.$end_grand</i>)</td>
									</tr> 
									<tr>
									<td style=' font-weight:bold;'>Submitted By </td> <td width='30' align='center'> : </td> <td> $sub_by </td>
									</tr>				 
									</table>
									<br>
											   
<table  style=' border-spacing: 0; margin-top:2px; margin-bottom:2px; border-collapse: collapse; border:solid 1px #555; '>	                  
<tr bgcolor='#FF5F55' style=' border-spacing: 0;border-collapse: collapse; font-weight:bold; border:solid 1px #555; color:#FFFFFF;text-transform:uppercase'>
<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> NAMA VENDOR </td>
<td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #555; '> NO PO </td>
<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> TGL PO </td>
<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> TOTAL </td>
<td align='center' width='76' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> RT </td>
<td align='center' width='76' style='padding:8px;   border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> HP </td>
<td align='center' width='76' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> DL </td>
<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> NOTE </td>
</tr>";


//---------------------------------------------------------------------------------------------------------------------------------
$message1->From                 = 'rivan <rivan.pahlawan@hts.net.id>';
		    $message1->To       = 'rivan <rivan.pahlawan@hts.net.id>'; 
		    $message1->CC       = ''; 
			$message1->BCC      = '';
		    $message1->Subject  = "Notifikasi  hasil persetujuan PO ";
			
	        $message1->HTMLBody = " <font style='font-size:16px; font-weight:bold;'>Data pengajuan PO ini telah selesai di proses. <br />
                                    Berikut hasilnya :</font>
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
<tr bgcolor='#888' style=' border-spacing: 0;border-collapse: collapse; font-weight:bold; border:solid 1px #555; color:#FFFFFF;text-transform:uppercase'>
<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> NAMA VENDOR </td>
<td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #555; '> NO PO </td>
<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> TGL PO </td>
<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> PPN </td>

<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> TOTAL </td>
<td align='center' width='76' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> RT </td>
<td align='center' width='76' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> HP </td>
<td align='center' width='76' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> DL </td>

<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> NOTE </td>
</tr>";
//--------------------------------------------------------------------------------------------------------------------------------------------------------------


//---------------------------------------------------------------------------------------------------------------------------------
            $message2->From     = 'rivan <rivan.pahlawan@hts.net.id>';
		    $message2->To       = 'rivan <rivan.pahlawan@hts.net.id>'; 
		    $message2->CC       = ''; 
			$message2->BCC      = '';
		    $message2->Subject  = "Notifikasi hasil persetujuan PO ";
			
	        $message2->HTMLBody = "    <font style='font-size:16px; font-weight:bold;'>Data pengajuan PO ini telah selesai di proses. <br />
                                     Berikut hasilnya :</font>
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
<tr bgcolor='#888' style=' border-spacing: 0;border-collapse: collapse; font-weight:bold; border:solid 1px #555; color:#FFFFFF;text-transform:uppercase'>
<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> NAMA VENDOR </td>
<td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #555; '> NO PO </td>
<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> TGL PO </td>
<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> PPN </td>

<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> TOTAL </td>
<td align='center' width='76' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> RT </td>
<td align='center' width='76' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> HP </td>
<td align='center' width='76' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> DL </td>

<td align='center' style='padding:8px;  border-spacing: 0; border-collapse: collapse; border:solid 1px #555;'> NOTE </td>
</tr>";
//--------------------------------------------------------------------------------------------------------------------------------------------------------------





$totalprice   = 0;
$total_appro  = 0;
$total_rejek  = 0;

for ($i = 0; $i < count($po); $i++) {

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
	   $tt                = $t[$i];
	   $end_keterangan_hp = $keterangan_hp[$i];
	   $end_keterangan_rt = $keterangan_rt[$i];
	   $end_keterangan_dl = $keterangan_dl[$i];
	
	
	                     if($proval_rt ==1)
	                      {
						   $byapp_rt='Approved';
						  } 
						  else {
						
						      if ($end_tgl_rt =='') 
						         {
						          $byapp_rt='';
						         } else {
							      $byapp_rt='Reject';
							  }
							   
						   } //end if 
						
						
						
						if($proval_dl ==1)
	                      {
						   $byapp_dl='Approved';
						  } 
						else {
						
						          if ($end_tgl_dl =='') 
						             {
						               $byapp_dl='';
						             }
								     else 
								     {
							           $byapp_dl='Reject';
							         }
							   
							   } //end if 
						
						
	
	
	
	 if($end_keterangan_hp !=='')
	                      {
	                      $usr="<div style='text-align:left; padding:4px;'>$end_keterangan_hp (By $end_user)</div>";
	                      }else {
	                      $usr='';
	                      }
	if($end_keterangan_rt !=='')
	                      {
						   $appRT= BOD_RT;
				           $end_appRT = ucwords($appRT);
	                       $usr_rt="<div style='text-align:left; padding:4px;'>$end_keterangan_rt (By $end_appRT)</div>";
	                      }else {
	                      $usr_rt='';
	                      }
						  if($end_keterangan_dl !=='')
	                      {
						   $appDL= BOD_DL;
				           $end_appDL = ucwords($appDL);
	                       $usr_dl="<div style='text-align:left; padding:4px;'>$end_keterangan_dl (By $end_appDL)</div>";
	                      }else {
	                      $usr_dl='';
	                      }
	
	
	
	if($prove_hp ==1)
	{
	  $total_appro++;
	  $totalprice += $end_total;
	  $grandtotal  = number_format($totalprice);
	  $byapp='Approved';
	}else {
	  $byapp='Reject';
	  $total_rejek++;
	}
	if($tgl_hp !=='')
	{
	  $end_tgl=$tgl_hp;
	  $tg=$tgl_hp;
	}else {
	  $tg='-';
	  date_default_timezone_set('Asia/Jakarta');
	  $end_tgl= date('Y-m-d h:i:s');
	}
	
	 $total_po    = number_format($end_total);
	 
	 $message->HTMLBody .= "<tr>
                            <td  style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$end_vendor</td>       
						    <td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$end_po</td>
						    <td  align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$end_tgl_po</td>
                            <td  align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$total_po</td>							
						    <td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp_rt</td>
						    <td align='center' bgcolor='#eee' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp</td>
					        <td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp_dl</td>
                            <td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$usr_rt $usr $usr_dl</td>
                            </tr>
							";
	                        ?>			 
	

	<?php
	/*
		if (is_null( $end_tgl_dl ) || empty( $end_tgl_dl ))
			$reshp= $Database->query( "Call SetPPO_Detail( '$user','$ppo','$end_po',$prove_hp,'$end_tgl',
				'$end_keterangan_hp',$proval_dl, null )" );
		else
			$reshp= $Database->query( "Call SetPPO_Detail( '$user','$ppo','$end_po',$prove_hp,'$end_tgl',
			'$end_keterangan_hp',$proval_dl,'$end_tgl_dl' )" );
	*/ ?>
		
	
    <?php
   
    } //for hp
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
						   <td width='160' style=' font-weight:bold; '>Approved By </td>  <td width='30' align='center'> : </td> <td> $end_user </td>
						   </tr>
						   <tr>
						   <td style=' font-weight:bold;'>Tanggal Approval </td> <td width='30' align='center'> : </td> <td> $tanggal $bulan $tahun </td>
						   </tr>
						   <tr>
						   <td style=' font-weight:bold;'>Total Approved </td> <td width='30' align='center'> : </td> <td> $total_appro (Rp.$total_app) </td>
						   </tr>
						   <tr>
						   <td style=' font-weight:bold;'>Total Reject </td>  <td width='30' align='center'> : </td> <td> $total_rejek (Rp.$grandtotal2) </td>
						   </tr>									    
						   </table>
						   <br>";
						     
						                   
						   
												 
	 $message->Configuration = $messageCon;
	 $message->Send() ;
		
		
		
		
		
		 if ($tgl_hp!=='')
		               {
                         

                          if ($dl_tgl!=='')
				             {
				                 $appA= BOD_HP;
				                 $appB= BOD_DL;
				                 $end_appA = ucwords($appA);
				                 $end_appB = ucwords($appB);  
	//--------------------------------------------------------------------------------------------------------------------------------------------------					   
			$message1->HTMLBody .= "
						  		   <table style=' margin-top:10px; border:solid 1px #888; background:#f1f1f1; padding:8px;'>											      
								   <tr>
								   <td width='160' style=' font-weight:bold;'>Approved By </td>  <td width='30' align='center'> : </td> <td>  $end_appA  $end_appB</td>
								   </tr>
								   <tr>
								   <td style=' font-weight:bold;'>Tanggal Approval </td> <td width='30' align='center'> : </td> <td> $hp_tgl dan $dl_tgl</td>
								   </tr>
								  		 											    
								   </table>
								   <br>";					   
						//--------------------------------------------------------------------------------------------------------------------------------------------------					    
							   $message1->Configuration = $messageCon;
				          $message1->Send() ;
		                      }  else {  
							           
						 
		                                  
							            }
												
												
		                    
				          
				            
						   
						
					}	 else { 
					 if ($rt_tgl!=='')
				        {
		                 $yang_approv=BOD_RT;
					     $end_yang_app = ucwords($yang_approv);
						//--------------------------------------------------------------------------------------------------------------------------------------------------					   
			             $message2->HTMLBody .= "
						  		   <table style=' margin-top:10px; border:solid 1px #888; background:#f1f1f1; padding:8px;'>											      
								   <tr>
								   <td width='160' style=' font-weight:bold;'>Approved By </td>  <td width='30' align='center'> : </td> <td> $end_yang_approv </td>
								   </tr>
								   <tr>
								   <td style=' font-weight:bold;'>Tanggal Approval </td> <td width='30' align='center'> : </td> <td> $rt_tgl </td>
								   </tr>			 											    
								   </table>
								   <br>";					   
						//--------------------------------------------------------------------------------------------------------------------------------------------------					   					   
										    $message2->Configuration = $messageCon;
				                            $message2->Send() ;
				                             } else {
                           ?>
                          
						 
                 <?php   } }
 
		
		
		
		
				
	}
	catch (com_exception $e) {
				print "<hr>\n\n";
				print $e . "\n";
				print "<hr>\n\n";
			    }
				
   if (!$reshp) {
	
	echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data gagal di submit';</script>";
	
	} else{
	echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data telah berhasil di submit';</script>";
	}
	?>
	
	<?php
    } //end post
    } //end if hp
    ?> 
 
 
 