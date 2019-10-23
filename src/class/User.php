<?php
require_once "Database.php";

class User
{
	private $_id;
	private $_username;
	private $_email;

	private $_db;

	function __construct(Database $db, int $user_id = null)
	{
		$this->_db = $db;

		if (!empty($user_id))
		{
			$this->getUserById();
		}
	}

	function getUserByName(string $username) : void
	{
		if (empty($this->_id))
		{
			/** make sure it is not an email address */
			if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
				/** valid email address */
				$sql = "SELECT * FROM users WHERE email = '{$username}'";
			} else {
				/** invalid emailaddress */
				$sql = "SELECT * FROM users WHERE username = '{$username}'";
			}
		 
			$result = $this->_db->queryDb($sql);

			$this->_id = $result[0]['id'];
			$this->_username = $result[0]['username'];
			$this->_email = $result[0]['email'];
		} 
	}

	function getUserById(int $user_id) : void
	{
		if (empty($this->_id))
		{
			$sql = "SELECT * FROM users WHERE id = '{$user_id}'";
		 
			$result = $this->_db->queryDb($sql);

			$this->_id = $result[0]['id'];
			$this->_username = $result[0]['username'];
			$this->_email = $result[0]['email'];
		} 
	}



	function create(string $username, string $email) : int
	{
		$sql = "INSERT INTO users (id, username, email, created_at) VALUES (NULL, '{$username}', '{$email}',  CURRENT_TIMESTAMP);";

		$id = $this->_db->insertRecord($sql);

		return $id;
	}

	function getEmail()
	{
		return $this->_email;
	}

	function getUsername()
	{
		return $this->_username;
	}

	function getId()
	{
		return $this->_id;
	}
}