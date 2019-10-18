<?php
require_once "class/password.php";
$pwObj = new password();

if ("\$5\$rounds=1000\$MySaltyStringz\$MHUmMsMD6S9GuChvYbGDKcRnbas23oy6d2Mcs/1Y4bD" == $pwObj->_getHash("Museum714"))
{
	print_r("Hash is the same");
}


//echo 'SHA-256 (no rounds): ' . crypt('password-to-encrypt', '$5$YourSaltyStringz$');
//echo "<br>";
//echo 'SHA-512 (with rounds): ' ;
//echo "<br>";

/*
https://www.php.net/manual/en/faq.passwords.php#faq.passwords.fasthash

https://joshtronic.com/2013/06/06/encrypting-passwords-in-php/

SHA-256 and SHA-512
Due to the fact that Blowfish has vulnerabilities prior to PHP 5.3.7 you maybe interested in using SHA-256 or SHA-512 instead. Both have a similar salt format as Blowfish (use a prefix of $5$ for SHA-256 and $6$ for SHA-512) but also contains an optional rounds parameter to force multiple hashing. The salt itself is a bit shorter at only 16 characters but unlike Blowfish allows more than just alphanumerics.

echo 'SHA-256 (no rounds): ' . crypt('password-to-encrypt', '$5$YourSaltyStringz$');
echo 'SHA-512 (with rounds): ' . crypt('password-to-encrypt', '$6$rounds=1000$YourSaltyStringz$');

Like Blowfish, the resulting hashes will contain the salt as part of the result hash.
Conclusion

As we can see, not all hashing functions are created equal. Tomorrow I’m going to discuss some additional techniques that can be applied while generating your password hashes regardless of which hashing function you choose to use.
*/
//print_r(crypt('password-to-encrypt', '$6$rounds=1000$YourSaltyStringz$'));

//echo "<br>";
//echo "<br>";


$salt	 = 'salt';
$password = 'abc123';
$hash	 = sha1($salt . $password);
//echo $hash;


?>


<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</heaD>
	<body>
		<a class="btn btn-primary" href="Login.php" role="button">Login</a>
		<a class="btn btn-secondary" href="register.php" role="button">Register</a>
	</body>
</html>