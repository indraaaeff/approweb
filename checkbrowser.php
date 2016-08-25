<?php 
/* Change path info depending on your file locations */
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
 
if($detect->isMobile()) {
    header('Location: http://report.hts.net.id/approval/test.php');
    exit;
}
?>
<!DOCTYPE html>

<html>
<head>
	<title>BrowserCheck</title>
</head>

<body>
	desktop
</body>
</html>
