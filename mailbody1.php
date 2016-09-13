<?php
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
?>
