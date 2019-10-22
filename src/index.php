<?php
require_once "class/password.php";
require_once "class/user.php";
//require_once "class/login.php";
require_once "class/account.php";

session_start();

$action = "";
if(isset($_GET['action']))
{
	$action = $_GET['action'];
}

$object = new Account();
?>
<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</heaD>
	<body>
		<?php
		$object->showButtons((key_exists('userId', $_SESSION) ? $_SESSION['userId'] : 0));

		switch ($action)
		{
			case "showForgotPassword" :
			case "showLogin" :
			case "showProperties" :
			case "showRegister" :
				echo $object->{$action}();
				break;
			case "forgot_password" :
				echo $object->showForgotPassword();
				break;
			default :
				if (key_exists("message", $_SESSION))
				{
					echo $_SESSION['message'];
				}
				break;
		}
		?>
	</body>
</html>

<?php
	/** unset the Session variables */
	unset($_SESSION['message']);
	unset($_SESSION['errors']);
	unset($_SESSION['userId']);

?>