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
		$no_ppo 	   = $_POST['no_ppo'];
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
	
		$Database->autocommit( FALSE );
		include "mailserver.php";
		$MailSubject = "APPROVED PPO NO. $no_ppo ($tanggal $bulan $tahun)";
		$MailBody = "";
		include "mailbody1.php";
		
		$totalprice   = 0;
		$total_appro  = 0;
		$total_rejek  = 0;

		$final_stats = '';
		$final_proses =0;
		$end_user2 = '';
		$tgl_approval2 = '';
		$end_user='HP';
		$approval2='';
		$ppo_rejected=0;

		// getdata untuk sinkronisasi
		$PPO_TableDetail2 = $Database->query( "Call GetPPO_Detail( '$no_ppo' )" );
		$row = $PPO_TableDetail2->fetch_assoc();
		$po_tgl_approved_rt = $row['tgl_approved_rt'];
		$po_tgl_approved_dl = $row['tgl_approved_dl'];
		// cek proses BOD_RT
		if (!empty($po_tgl_approved_rt) && !is_null($po_tgl_approved_rt)) {
			$isUpdate = false; //proses sudah selesai tidak boleh update.
		}
		//cek proses BOD_DL
		else if (!empty($po_tgl_approved_dl) && !is_null($po_tgl_approved_dl)) {
			$isUpdate= true;
			$final_proses=1;
			$PPO_TableDetail2->data_seek(0);
			$i=0;
			while ($row = $PPO_TableDetail2->fetch_assoc()) {
				$tgl_app_dl[$i] = $row['tgl_approved_dl'];
				$po_app_dl[$i]  = $row['approve_by_dl'];
				$i++;
			}
		} else {
			$isUpdate = true;
		}
		$PPO_TableDetail2->free();
		$Database->next_result();

		if ($isUpdate) {

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
					if (!empty($end_tgl_dl) && $proval_dl==0) {
						$byapp='-';
						$total_rejek++;		
					} else {
						$total_appro++;
						$totalprice += $end_total;
						$grandtotal  = number_format($totalprice);
						$byapp='A';
					}
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
				// cek total reject untuk kirim email
				if (!empty($tgl_hp)) {
					if ($prove_hp==0) {
						$ppo_rejected++;
					}
				}
				//final status
				if ($prove_hp==1) {
					if (!empty($end_tgl_dl)) {
						if ($proval_dl==1) {
							$final_stats = 'Approved';
						} else {
							$final_stats = 'Rejected';
						}
					} else {
						$final_stats = 'In Progress';
					}
				} else {
					if (!empty($end_tgl_dl)) {
						if ($proval_dl==1) {
							$final_stats = 'Rejected';
						} else {
							$final_stats = 'Rejected';
						}
					} else {
						if ($ppo_rejected==$total_ppo) {
							$final_stats = 'Rejected';
						} else {
							$final_stats = 'In Progress';
						}
					}
				}
				// jika ini proses kedua
				if (!empty($end_tgl_dl)) {
					$end_user2 = 'DL';
					$tgl_approval2 = $tgl_app_dl[$i];
					$approval2 = "<tr><td width='160' style=' font-weight:bold;'>$end_user2 approved</td>  <td width='30' align='center'> : </td> <td> $tgl_approval2 </td></tr>";
				}
				if($tgl_hp !=='')
				{
					$end_tgl=$tgl_approval;
					$tg=$tgl_hp;
				} else {
					$tg='-';
					date_default_timezone_set('Asia/Jakarta');
					$end_tgl= date('Y-m-d h:i:s');
				}

				$total_po    = number_format($end_total);
				include "mailbody2.php";

				if (is_null( $end_tgl_dl ) || empty( $end_tgl_dl )) {
					$reshp= $Database->query( "Call SetPPO_Detail( '$user','$ppo','$end_po',$prove_hp,'$end_tgl','$end_keterangan_hp',$proval_dl, null )" );
				}
				else {
					$reshp= $Database->query( "Call SetPPO_Detail( '$user','$ppo','$end_po',$prove_hp,'$end_tgl','$end_keterangan_hp',$proval_dl,'$end_tgl_dl' )" );
				}

				if (!$reshp) {
					// echo $Database->error . "<br />";
					$isUpdate = false;
					break;
				}
			} //end for hp
		}
		// echo "isupdate : ".$isUpdate;
        if ( $isUpdate) {
			$total_reject= $grand-$totalprice;
			$grandtotal2  = number_format($total_reject);
			if(empty($grandtotal))
			{
				$total_app=0;
			} else {
				$total_app=$grandtotal;
			}			

			include "mailbody3.php";

			//cek sudah final atau belum
			if ($final_proses==1 || $ppo_rejected==$total_ppo) {
				include "SendMail.php";
			}
			if ($isUpdate) {
				$Database->commit();
				echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data telah berhasil di submit';</script>";
			} else {
				$Database->rollback();	
				echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data gagal di submit';</script>";
			}

        } else{
        	$Database->rollback();
        	//redirect ke approval
        	echo "<script language='javascript'>document.location.href='ppo_approval.php?u=$u&p=$ppo&k=$key&notif=Data gagal di submit';</script>";
        }
		$Database->autocommit( TRUE );

    } //end post
} //end if hp
?>