<?php
	include "inc.php";
	$Database = new mysqli( IIS_HOST, IIS_MYSQLUSER, IIS_MYSQLPASS, IIS_DB );
	if ($Database->connect_error) {
		die( 'Connect Error (' . $Database->connect_errno . '). '
			. $Database->connect_error );
	}
?>