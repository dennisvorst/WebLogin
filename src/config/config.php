<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
switch ($_SERVER['SERVER_NAME']){
	case "localhost":
		/* localhost */
		if (!defined('NMSERVER')) define('NMSERVER', 'localhost');
		if (!defined('NMUSER'))define('NMUSER', 'root');
		if (!defined('NMPASSWORD'))define('NMPASSWORD', 'Marjilde');
		if (!defined('NMDATABASE'))define('NMDATABASE', 'weblogin');
		break;
	case "www.honkbalmuseum.nl":
		if (!defined('NMSERVER')) define('NMSERVER', 'server99.hosting2go.nl');
		if (!defined('NMUSER')) define('NMUSER', 'm1_871d6a43');
		if (!defined('NMPASSWORD')) define('NMPASSWORD', 'C8sEAvgVzx');
		if (!defined('NMDATABASE')) define('NMDATABASE', 'm1_871d6a43');
		break;
}
?>