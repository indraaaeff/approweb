<?php 
/* Change path info depending on your file locations */

include "inc.php";
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
 
	if (isset($_GET['notif'])) {
		if($detect->isMobile()) {
			header("Location: " . IIS_PATH . "ppo_approval_mobile.php?u=$_GET[u]&p=$_GET[p]&k=$_GET[k]&notif=$_GET[notif]");
			exit;
		}else {
			header("Location: " . IIS_PATH . "ppo_approval_desktop.php?u=$_GET[u]&p=$_GET[p]&k=$_GET[k]&notif=$_GET[notif]");
		}		
	} else {
		if($detect->isMobile()) {
			header("Location: " . IIS_PATH . "ppo_approval_mobile.php?u=$_GET[u]&p=$_GET[p]&k=$_GET[k]");
			exit;
		}else {
			header("Location: " . IIS_PATH . "ppo_approval_desktop.php?u=$_GET[u]&p=$_GET[p]&k=$_GET[k]");
		}
	}
?>