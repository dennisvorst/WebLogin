<?php

class Password
{
	private $_specialChars = " !\"#$%&'()*+,-./:;<=>?@[\]^_`{|}~";
	private $_lowerCase = "abcdefghijklmnopqrstuvwxyz";
	private $_upperCase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	private $_digits = "0123456789";

	private $_length = 10;

	function __construct()
	{

	}

	function generatePassword() : string 
	{

	}

	function isValid(string $password) : bool
	{
		if (empty($password))
		{
			return false;
		}

		$special = $this->_hasAtLeastOne($password, $this->_specialChars);
		$lowercase = $this->_hasAtLeastOne($password, $this->_lowerCase);
		$uppercase = $this->_hasAtLeastOne($password, $this->_upperCase);
		$digit = $this->_hasAtLeastOne($password, $this->_digits);

		if ($special && $uppercase && $lowercase && $digit && strlen($password) >= $this->_length) 
		{
			return true;
		}
		return false;
	}

	private function _hasAtLeastOne(string $password, string $list) : bool
	{
		if (empty($password) || empty($list)) 
		{
			return false;
		}

		$list = str_split($list);

		foreach ($list as $char) 
		{
			if (strpos($password, $char) !== false) 
			{
				return true;
			}
		}
		return false; 
	}
}
?>