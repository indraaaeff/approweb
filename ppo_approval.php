<?php 
/* Change path info depending on your file locations */
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
 
if($detect->isMobile()) {
    header("Location: http://report.hts.net.id/approval/ppo_approval_mobile.php?u=$_GET[u]&p=$_GET[p]&k=$_GET[k]");
    exit;
}else {
	header("Location: http://report.hts.net.id/approval/ppo_approval_desktop.php?u=$_GET[u]&p=$_GET[p]&k=$_GET[k]");
}
?>