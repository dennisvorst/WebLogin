<?php
require_once "Database.php";
require_once "Password.php";

class Account
{
	private $_username;
	private $_password;

	private $_duration = 30;

	private $_db;
	private $_pw;

	function __construct()
	{
		$this->_db = new DataBase();
		$this->_pw = new Password();
//		$this->_username = $username;
//		$this->_password = $password;
	}

	function doLogin(int $user_id) : int
	{
		if (!$this->isLoggedIn($user_id))
		{
			$sql = "INSERT INTO logins (id, user_id, start, last_active, logged_out, created_at) VALUES (NULL, " . $user_id . ", CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL, CURRENT_TIMESTAMP);";
			$this->_loginid = $this->_db->executeCommand($sql);
			return $this->_loginid;
		}
		return 0;
	}

	function doLogout(number $id)
	{
		$sql = "UPDATE logins SET logged_out = CURRENT_TIMESTAMP WHERE id = $id";

		print_r($sql);
	}

	function isLoggedIn(int $user_id) : bool
	{
		/* if no records are found return false */
		/* hasLoginExpired is better */


		/* create a timestamp */
		$now = new DateTime();
		$now->format('Y-m-d H:i:s');    // MySQL datetime format

		/* get the max value id */
		$sql = "SELECT MAX(id) as max_id FROM logins WHERE logged_out IS NULL AND user_id = " . $user_id;
		$max_id = $this->_db->queryDb($sql);
		$max_id = $max_id[0]['max_id'];

		/* get the record */
		$sql = "SELECT * FROM logins WHERE id = " . $max_id ;
		$last_active = $this->_db->queryDb($sql);
		$last_active = $last_active[0]['last_active'];

		$last_active = new DateTime($last_active);
		$last_active->modify("+" . $this->_duration . " minutes");

		/* if last active plus the expiration time still exceeds the current date time? return true*/
		if ($last_active > $now)
		{
			return true;
		} else {
			return false;
		}
	}

	function usernameExists(string $username) : bool
	{
		$sql = "SELECT count(*) as total FROM users WHERE username = '" . $username . "'";
		print_r($sql . "<br>");
		$result = $this->_db->queryDb($sql);
		if ($result[0]['total'] > 0)
		{
			return true;
		}
		return false;
	}

	function doRegister(array $params)
	{
		$username = $params['username'];
		if (!$this->usernameExists($username)) 
		{
			$user_id = $this->_createUser($username);
			$password_id = $this->_createAccount($user_id, $params['password']);
		} else {
			print_r("username exists<br>");
		}
	}

	private function _createUser(string $username) : int
	{
		$sql = "INSERT INTO users (id, username, created_at) VALUES (NULL, '" . $username . "', CURRENT_TIMESTAMP);";

		$id = $this->_db->insertRecord($sql);

		return $id;
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
		?>
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
		<?php
	}

	function showLogin()
	{
		?>
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
		<?php
	}

	function showForgotPassword()
	{
	}

	function sendConfirmationEmail()
	{
	}

	function showResetPassword()
	{
	}

	function showProperties()
	{
	}

	function showButtons(int $user_id) : void
	{
		if ($this->isLoggedIn($user_id))
		{
			?>
			<a class="btn btn-primary" href="Login.php" role="button">Login</a>
			<a class="btn btn-secondary" href="register.php" role="button">Register</a>
			<?php
		} else {
			?>
			<a class="btn btn-primary" href="logout.php" role="button">Logout</a>
			<a class="btn btn-secondary" href="roperties.php" role="button">Properties</a>
			<?php
		}
	}
}