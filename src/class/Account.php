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

	function verify(string $username, string $password) : bool
	{
		$sql = "SELECT u.username, p.password FROM users u, passwords p WHERE u.id = p.user_id AND u.username = {$username} ORDER BY p.startdate desc LIMIT 1";
		$result = $this->_db->queryDb($sql);

		print_r($result);


	}

	function doLogin(int $user_id) : bool
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
		$sql = "SELECT COUNT(*) AS total FROM logins WHERE logged_out IS NULL AND last_active > NOW() + INTERVAL {$this->_duration} MINUTE AND user_id = " . $user_id;

		/* get the record */
		$total = $this->_db->queryDb($sql);
		$total = $total[0]['total'];

		/* if last active plus the expiration time still exceeds the current date time? return true*/
		if ($total > 0)
		{
			print_r("TRUE");
			return true;
		} else {
			print_r("FALSE");
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
		print_r($params);
		$username = $params['username'];
		$password = $params['password'];

		if (!$this->usernameExists($username)) 
		{
			$user_id = $this->_createUser($username);
			$password_id = $this->_createAccount($user_id, $password);
		} else {
			print_r("username exists<br>");
		}

		print_r($username);
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
        <form action="process.php" method="post">
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
            <div class="form-group">
				<input type="hiddden" name="action" value="doRegister">
            </div>
            <p>Already have an account? <a href="index.php?action=showLogin">Login here</a>.</p>
        </form>
		<?php
	}

	function showLogin()
	{
		?>
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="process.php" method="post">
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
            <p>Don't have an account? <a href="index.php?action=showRegister">Sign up now?</a>.</p>
            <p><a href="index.php?action=showForgotPassword">Forgot your password?</a></p>
        </form>
		<?php
	}

	function showForgotPassword()
	{
		?>
        <h2>Forgot password</h2>
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
            <p>Don't have an account? <a href="index.php?action=showRegister">Sign up now?</a>.</p>
        </form>
		<?php
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

	function showButtons(int $user_id) : void
	{
		if ($this->isLoggedIn($user_id))
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
}