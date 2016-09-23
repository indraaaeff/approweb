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
		$no_ppo 	   = $_POST['no_ppo'];
		$tgl_approval  = $_POST['tgl_approval'];
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
		$isDesktop	   = $_POST['desktop'];

		$Database->autocommit( FALSE );
		include_once "mailserver.php";
		// $message   = new COM('CDO.Message');
		// $messageCon= new COM('CDO.Configuration') ;
		$MailSubject  = "APPROVED PPO NO. $no_ppo ($tanggal $bulan $tahun)";
		$MailBody = "";
		include "mailbody1.php";

		$totalprice  = 0;
		$total_appro = 0;					   
		$total_rejek = 0;

		$totalprice_hp  = 0;
		$total_appro_hp = 0;
		$total_rejek_hp = 0;

		$totalprice_dl  = 0;
		$total_appro_dl = 0;
		$total_rejek_dl = 0;

		$final_stats = '';
		$end_user2='';
		$end_user='RT';
		$tgl_approval2='';
		$approval2='';

		// getdata untuk sinkronisasi
		$PPO_TableDetail2 = $Database->query( "Call GetPPO_Detail( '$no_ppo' )" );
		$row = $PPO_TableDetail2->fetch_assoc();
		$po_tgl_approved_hp = $row['tgl_approved_hp'];
		$po_tgl_approved_dl = $row['tgl_approved_dl'];
		
		if (!empty($po_tgl_approved_hp) && !is_null($po_tgl_approved_hp) && !empty($po_tgl_approved_dl) && !is_null($po_tgl_approved_dl)) {
			$isUpdate= false;
			$final_proses=1;

		} else if (!empty($po_tgl_approved_hp) && !is_null($po_tgl_approved_hp) || !empty($po_tgl_approved_dl) && !is_null($po_tgl_approved_dl)) {
			$isUpdate = true;
			$final_proses=1;
			$PPO_TableDetail2->data_seek(0);
			$i=0;
			while ($row = $PPO_TableDetail2->fetch_assoc()) {
				$tgl_app_hp[$i] = $row['tgl_approved_hp'];
				$po_app_hp[$i]  = $row['approve_by_hp'];
				$tgl_app_dl[$i] = $row['tgl_approved_dl'];
				$po_app_dl[$i]  = $row['approve_by_dl'];
				$i++;
			}
		} else {
			$isUpdate = true;
			$final_proses =1;
		}
		$PPO_TableDetail2->free();
		$Database->next_result();

		if ($isUpdate) 
		{
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

				if($proval_dl ==1)
				{
					$byapp_dl='A';
				} 
				else 
				{
					if ($end_tgl_dl =='') 
					{
						$byapp_dl='';
					} 
					else 
					{
						$byapp_dl='R';
					}
				} //end if 

			   if($end_keterangan_rt !=='')
			   {
			   		$usr="<div style='text-align:left; padding:4px;'>$end_keterangan_rt (By $end_user)</div>";
			   }else {
			   		$usr='';
			   }
			   //status per BOD
			   if($prove_rt ==1)
			   {
			   		if (!empty($end_tgl_hp) && $proval_hp==0 || !empty($end_tgl_dl) && $proval_dl==0) {
						$byapp='-';
						$total_rejek++;	
					} else {
						$total_appro++;
						$totalprice += $end_total;
						$grandtotal  = number_format($totalprice);
						$byapp='A';
					}
			   }
			   else if ($prove_rt ==0)
			   {	
			   		if (!empty($end_tgl_hp)) {
			   			if ($proval_hp==0) {
			   				$byapp = '-';
			   				$total_rejek++;
			   			} else {
			   				$byapp = 'R';
			   				$total_rejek++;
			   			}
			   		} else if (!empty($end_tgl_dl)) {
			   			if ($proval_dl==0) {
			   				$byapp = '-';
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
			   // isi final status
			   if ($prove_rt==1) {
			   		if (!empty($end_tgl_hp)) {
			   			if ($proval_hp==1) {
			   				$final_stats = 'Approved';
			   			} else {
			   				$final_stats = 'Rejected';
			   			}
			   		} else if (!empty($end_tgl_dl)) {
			   			if ($proval_dl==1) {
			   				$final_stats = 'Approved';
			   			} else {
			   				$final_stats = 'Rejected';
			   			}
			   		} else {
			   			$final_stats = 'Approved';	
			   		}
			   } else {
			   		$final_stats = 'Rejected';
			   }
			   // proses2
			   if (!empty($end_tgl_hp)) {
			   		$end_user = 'RT';
			   		$end_user2 = 'HP';
			   		$tgl_approval2 = $end_tgl_hp;
			   		$approval2 = "<tr><td width='160' style=' font-weight:bold;'>$end_user2 approved</td>  <td width='30' align='center'> : </td> <td> $tgl_approval2 </td></tr>";
			   } else if (!empty($end_tgl_dl)) {
			   		$end_user = 'RT';
			   		$end_user2 = 'DL';
			   		$tgl_approval2 = $end_tgl_dl;
			   		$approval2 = "<tr><td width='160' style=' font-weight:bold;'>$end_user2 approved</td>  <td width='30' align='center'> : </td> <td> $tgl_approval2 </td></tr>";
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
			   $MailBody .= "<tr>
								   <td style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$end_vendor</td>       
								   <td align='center' style='padding:8px; border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$end_po</td>
								   <td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$end_tgl_po</td>
								   <td align='center' style='padding:8px; border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '> $total_po</td>
								   <td align='center' bgcolor='#eee' style='border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp</td>
								   <td align='center' style='border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp_hp</td>
								   <td align='center' style='border-spacing: 0;border-collapse: collapse; border:solid 1px #888; '>$byapp_dl</td>
								   <td align='center' style='border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$usr $usr_hp $usr_dl</td>
								   <td align='center' style='border-spacing: 0; border-collapse: collapse; border:solid 1px #888; '>$final_stats</td>
								   </tr>
								   ";
			   //------------------------------------------------------------------------------------------------------------------------------------
			               
			  $res= $Database->query( "Call SetPPO_Detail( '$user','$ppo','$end_po',$prove_rt,'$end_tgl','$end_keterangan_rt', 0, null )" );			 

			  if (!$res) {
					// echo $Database->error . "<br />";
					$isUpdate = false;
					break;
				}
            }//end for
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
        	include "mailbody3.php";
			include_once "SendMail.php";
			$Database->commit();
			// redirect ke halaman approval
			if ($isDesktop== 1) {
				echo "<script language='javascript'>document.location.href='ppo_approval_desktop.php?u=$u&p=$ppo&k=$key&notif=Data gagal di submit';</script>";
			} else {
				echo "<script language='javascript'>document.location.href='ppo_approval_mobile.php?u=$u&p=$ppo&k=$key&notif=Data telah berhasil di submit';</script>";
			}
        } else{
			$Database->rollback();
			// redirect ke halaman approval
			if ($isDesktop== 1) {
				echo "<script language='javascript'>document.location.href='ppo_approval_desktop.php?u=$u&p=$ppo&k=$key&notif=Data gagal di submit';</script>";
			} else {
				echo "<script language='javascript'>document.location.href='ppo_approval_mobile.php?u=$u&p=$ppo&k=$key&notif=Data telah berhasil di submit';</script>";
			}
        }
        $Database->autocommit( TRUE );
   	}  //end post
} // end if rt
?>