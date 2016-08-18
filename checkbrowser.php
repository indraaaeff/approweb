<!DOCTYPE html>
<html>
<head>
	<title>BrowserCheck</title>
</head>

<body>
	<?php
		include './myfunctions.php';
		$browser = strtolower( getBrowserAgent() );
		$isAndroid = (strpos( $browser, "android" ) !== false);
		$isIphone = (strpos( $browser, "safari" ) !== false);
		$isBB = (strpos( $browser, "blackberry" ) !== false);
		if ( $isAndroid || $isIphone || $isBB){
			echo "You access the site using Smartphones or Any Mobile devices . You may use Android/IOS/Blackberry.";
		} else {
			echo "PC browser: " . $browser;
		}
	?>
</body>
</html>
