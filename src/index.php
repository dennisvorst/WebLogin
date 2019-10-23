<?php
require_once "class/password.php";
require_once "class/user.php";
//require_once "class/login.php";
require_once "class/account.php";

session_start();
print_r($_SESSION);

/** create the account object */
$object = new Account((key_exists('userId', $_SESSION) ? $_SESSION['userId'] : null));

/** check last active status */
if (isset($_SESSION['lastActive']))
{
	if ($object->stillActive($_SESSION['lastActive']))
	{
		/** still active */
		$_SESSION['lastActive'] = new DateTime();
	} else {
		/** no longer active */
		if ($object->doLogout($_SESSION['userId']))
		{
			$_SESSION['message'] = "Logout successful";
			$_SESSION['isLoggedIn'] = False;
			unset($_SESSION['userId']);
			unset($_SESSION['lastActive']);
		}
	}
}

$action = "";
if(isset($_GET['action']))
{
	$action = $_GET['action'];
}

?>
<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</heaD>
	<body>
		<?php
		/** if there is a message */
		if (isset($_SESSION['message'])) 
		{
			?>
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<?php echo $_SESSION['message'] ?>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?php
		}
		$object->showButtons((key_exists('isLoggedIn', $_SESSION) ? $_SESSION['isLoggedIn'] : 0));

		switch ($action)
		{
			case "showForgotPassword" :
			case "showLogin" :
			case "showRegister" :
				echo $object->{$action}();
				break;
			case "forgot_password" :
				echo $object->showForgotPassword();
				break;
			case "showProperties" :
				print_r($_SESSION);
				echo $object->showProperties($_SESSION['userId']);
				break;
			default :
				if (key_exists("message", $_SESSION))
				{
					echo $_SESSION['message'];
				}
				break;
		}
		?>

		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	</body>
</html>

<?php
	/** unset the Session variables */
	unset($_SESSION['message']);
	unset($_SESSION['errors']);
?>