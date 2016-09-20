<?php
	$ppo = '';
	if (isset( $_GET[ 'u' ] )) echo 'u = ' . $_GET[ 'u' ] . '<br />';
	if (isset( $_GET[ 'p' ] )) {
		echo 'p = ' . $_GET[ 'p' ] . '<br />';
		$ppo = $_GET[ 'p' ];
	}
	if (isset( $_GET[ 'k' ] )) { 
		echo 'k = ' . $_GET[ 'k' ] . '<br />';
		$key = $_GET[ 'k' ];
	}
	if (isset( $_GET[ 's' ] )) echo 's = ' . $_GET[ 's' ] . '<br />';
	include "connection.php";
	$List_PPO = $Database->query( "Select distinct a.no_ppo, a.submit_by, a.tgl_pengajuan, a.tgl_approved_rt,
			a.tgl_approved_hp, a.tgl_approved_dl
		From vendor_ppo a, vendor_invoice b
		Where (a.no_ppo = b.no_ppo) and !( b.po_approved )
		Group by a.no_ppo Order by a.no_ppo" );


	if ($List_PPO && ($List_PPO->num_rows > 0)) {
		echo "<form>";
			echo '<input type="hidden" name="u" value="01">';
			echo '<select name="p" onchange="this.form.submit();">';
				while ($row = $List_PPO->fetch_assoc()) {
					if ($row['no_ppo'] == $ppo) {
						echo '<option value="' . $row['no_ppo'] . '" selected="selected">' . $row['no_ppo'] . '</option>';
					}
					else {
						echo '<option value="' . $row['no_ppo'] . '">' . $row['no_ppo'] . '</option>';
					}
				}
			echo '</select>';
			echo "<input type='hidden' name='k' value='$key'>";
			echo '<input type="hidden" name="s" value="1">';
		echo '</form>';

		$List_PPO->free();
	}

	if ( $Database ) $Database->close();
?>
