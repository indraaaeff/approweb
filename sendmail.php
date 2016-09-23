<?php
	try 
	{
		// $message1   = new COM('CDO.Message');
		// $message2   = new COM('CDO.Message');
		$message   = new COM('CDO.Message');
		$messageCon= new COM('CDO.Configuration') ;

		$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpserver'] = HTSMAIL_SERVER;
		$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpserverport'] = HTSMAIL_PORT;
		$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpauthenticate'] = SMTP_BASICAUTHENTICATION;
		$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/sendusername'] = HTSMAIL_USERNAME;
		$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/sendpassword'] = HTSMAIL_PASSWORD;
		$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/sendusing'] = SMTP_USEPORT ;
		$messageCon->Fields['http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout'] = 30 ;
		$messageCon->Fields->Update();

		$message->From    = IIS_MAIL;
		$message->To      = BOD;
		$message->CC      = ''; // EMAIL HP
		$message->BCC     = '';
		$message->Subject = $MailSubject;
		$message->HTMLBody = $MailBody;
		$message->Configuration = $messageCon;
		$message->Send() ;
	}
	catch (com_exception $e) {
		// print "<hr>\n\n";
		// print $e . "\n";
		// print "<hr>\n\n";
		$isUpdate = false;
	}

?>
