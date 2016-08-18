<?php
	function getBrowserAgent() {
		$u_agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );

		if (preg_match('/msie/i', $u_agent) or preg_match('/trident/i', $u_agent))
		{ 
			$browser = "IE"; 
		} 
		elseif (preg_match('/firefox/i',$u_agent)) 
		{ 
			$browser = "Firefox"; 
		} 
		elseif (preg_match('/chrome/i',$u_agent)) 
		{ 
			$browser = "Chrome"; 
		} 
		elseif (preg_match('/safari/i',$u_agent)) 
		{ 
			$browser = "Safari"; 
		} 
		elseif (preg_match('/flock/i',$u_agent)) 
		{ 
			$browser = "Flock"; 
		} 
		elseif (preg_match('/opera/i',$u_agent)) 
		{ 
			$browser = "Opera"; 
		}
		elseif (preg_match('/blackberry/i',$u_agent)) 
		{ 
			$browser = "Blackberry"; 
		}
		else { $browser = ""; }
		
		if ($browser !== "") {
			$u_agent = $_SERVER['HTTP_USER_AGENT'];
			if (preg_match('/Android/i', $u_agent)) {
				$Device = substr( $u_agent, strpos( $u_agent, 'Android' ) );
				$Device = substr( $Device, 0, strpos( $Device, ';' ) );
				$browser .= ' on ' . $Device;
			}
		}

		return $browser;
	}

	function browser_info($agent=null) {
		// Declare known browsers to look for
		$known = array( 'msie', 'trident', 'Firefox', 'safari', 'webkit', 'opera', 'netscape',
			'konqueror', 'gecko' );

		// Clean up agent and build regex that matches phrases for known browsers
		// (e.g. "Firefox/2.0" or "MSIE 6.0" (This only matches the major and minor
		// version numbers.  E.g. "2.0.0.6" is parsed as simply "2.0"
		$agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
		$pattern = '#(?<browser>' . join('|', $known) .
			')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';

		// Find all phrases (or return empty array if none found)
		if (!preg_match_all($pattern, $agent, $matches)) return array();

		// Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase,
		// Opera 7,8 have a MSIE phrase), use the last one found (the right-most one
		// in the UA).  That's usually the most correct.
		$i = count($matches['browser'])-1;
		return array($matches['browser'][$i] => $matches['version'][$i]);
	}
?>
