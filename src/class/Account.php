<?php
require_once "Database.php";
require_once "Password.php";
require_once "User.php";

class Account
{
	private $_password;

	private $_duration = 30;

	private $_db;
	private $_pw;
	private $_usr;

	private $_organisation = "Honkbalmuseum.nl";

	function __construct()
	{
		$this->_db = new DataBase();
		$this->_pw = new Password();
		$this->_usr = new User($this->_db);
	}

	function verify(string $id, string $password) : bool
	{
		$sql = "SELECT u.username, p.password FROM users u, passwords p WHERE u.id = p.user_id AND u.id = {$id} ORDER BY p.startdate desc LIMIT 1";
		$result = $this->_db->queryDb($sql);

		if ($password != $result[0]['password'])
		{
			return False;
		}
		return True;
	}

	function doLogin(string $username, string $password) : bool
	{
		/** get the user_id */
		$this->_usr->getData($username);
		$user_id = $this->_usr->getId();

		/** when an account is found */
		if (!empty($user_id))
		{
			/** hash the password */
			$password = $this->_pw->hashPassword($password);

			/** check if the password is valid and the user is not already logged in. */
			if (!$this->isLoggedIn($user_id) && $this->verify($user_id, $password))
			{
				$sql = "INSERT INTO logins (id, user_id, start, last_active, logged_out, created_at) VALUES (NULL, " . $user_id . ", CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL, CURRENT_TIMESTAMP);";
				$this->_loginid = $this->_db->executeCommand($sql);
				return True;
			}
		}
		return False;
	}


	function doLogout(int $user_id) : bool
	{
		$sql = "UPDATE logins SET logged_out = CURRENT_TIMESTAMP WHERE user_id = $user_id AND logged_out IS NULL";
		$this->_db->updatedb($sql);

		return true;
	}

	function isLoggedIn(int $user_id) : bool
	{
		/* if no records are found return false */
		$sql = "SELECT COUNT(*) AS total FROM logins WHERE logged_out IS NULL AND last_active > NOW() + INTERVAL {$this->_duration} MINUTE AND user_id = " . $user_id;

		/* get the record */
		$total = $this->_db->queryDb($sql);
		$total = $total[0]['total'];

		/* if last active plus the expiration time still exceeds the current date time? return true*/
		if ($total > 0)
		{
			return True;
		}
		return False;
	}

	function usernameExists(string $username) : bool
	{
		/** check if the username is unique */
		$sql = "SELECT count(*) as total FROM users WHERE username = '" . $username . "'";
		$result = $this->_db->queryDb($sql);
		if ($result[0]['total'] > 0)
		{
			return true;
		}
		return false;
	}

	function doRegister(array $params) : bool
	{
		/** init */
		$errors = [];
		$username = $params['username'];
		$email = $params['email'];
		$password = $params['password'];
		$confirm_password = $params['confirm_password'];

		/** make sure it is not an email address */
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			/** invalid emailaddress */
			$errors['email_err'] = "This is not a valid email address.";
		} else {
			/** check for uniqueness. */
		}
		if (!$this->usernameExists($username))
		{
			/** invalid emailaddress */
			$errors['username_err'] = "Username is already in use.";
		}
		if ($password != $confirm_password)
		{
			/** invalid emailaddress */
			$errors['password_err'] = "The confirmation password differs from the password.";
		}

		if (!empty($errors))
		{
			return False;
		}

		/** process */
		$user_id = $this->_usr->create($username, $email);
		$password_id = $this->_createAccount($user_id, $password);
		return true;
	}

	private function _createAccount(int $user_id, string $password) : int
	{
		$password = $this->_pw->hashPassword($password);

		$sql = "INSERT INTO passwords (id, user_id, password, startdate, created_at) VALUES (NULL, '" . $user_id . "', '" . $password . "', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);";

		$id = $this->_db->insertRecord($sql);

		return $id;
	}

	function showRegister()
	{
		$username = "";
		$email = "";
		$password = "";
		$confirm_password = "";

		$username_err = "";
		$email_err = "";
		$password_err = "";
		$confirm_password_err = "";
		?>
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="process.php" method="post">
			<!-- username -->
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
			<!-- email -->
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email address</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
			<!-- password -->
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
			<!-- confirm password -->
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
			<!-- buttons -->
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary" value="Reset">
            </div>
			<!-- hidden -->
            <div class="form-group">
				<input type="hidden" name="action" value="doRegister">
            </div>
            <p>Already have an account? <a href="index.php?action=showLogin">Login here</a>.</p>
        </form>
		<?php
	}

	function showLogin()
	{
		$username = "";
		$email = "";
		$password = "";

		$username_err = "";
		$email_err = "";
		$password_err = "";

		?>
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="process.php" method="post">
			<!-- username or email -->
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
			<!-- password -->
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
			<!-- buttons -->
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
			<!-- hidden -->
            <div class="form-group">
				<input type="hidden" name="action" value="doLogin">
            </div>

            <p>Don't have an account? <a href="index.php?action=showRegister">Sign up now?</a>.</p>
            <p><a href="index.php?action=showForgotPassword">Forgot your password?</a></p>
        </form>
		<?php
	}

	function showForgotPassword()
	{
		$username = "";
		$username_err = "";
		?>
        <h2>Forgot password</h2>
        <p>Please enter a username or a valid emailaddress.</p>
        <form action="process.php" method="post">
			<!-- username or email -->
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
			<!-- buttons -->
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Send">
            </div>
			<!-- hidden -->
            <div class="form-group">
				<input type="hidden" name="action" value="sendPassword">
            </div>
            <p>Don't have an account? <a href="index.php?action=showRegister">Sign up now?</a>.</p>
        </form>
		<?php
	}

	function sendPassword(string $username) : bool
	{
		$this->_usr->getData($username);
		$email = $this->_usr->getEmail();
		$username = $this->_usr->getUsername();

		if (!empty($email))
		{
			$message = "Dear {$username}./n/nYou have requested to change your passsword. Your new password is currently : <somepassword>. Please make sure to change it in the properties window./n/n{$this->_organisation}";
			if (mail('{$email}', 'Your new password ', $message))
			{
				return True;
			}
		}
		return False;
	}

	function sendConfirmationEmail()
	{
	}

	function showProperties()
	{
		?>
        <h2>Properties</h2>
        <p>Please enter a username or a valid emailaddress.</p>
        <form action="process.php" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Send">
            </div>
        </form>
		<?php
	}

	function showButtons(bool $isLoggedIn = false) : void
	{
		if ($isLoggedIn)
		{
			?>
			<a class="btn btn-primary" href="process.php?action=doLogout" role="button">Logout</a>
			<a class="btn btn-secondary" href="index.php?action=showProperties" role="button">Properties</a>
			<?php
		} else {
			?>
			<a class="btn btn-primary" href="index.php?action=showLogin" role="button">Login</a>
			<a class="btn btn-secondary" href="index.php?action=showRegister" role="button">Register</a>
			<?php
		}
	}

	function getUserId() : int
	{
		return $this->_usr->getId();
	}
}