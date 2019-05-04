<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class TabTest extends TestCase
{
	public function testClassTabExists()
	{
		$this->assertTrue(class_exists("Password"));
	}

	public function testPasswordMustBeFilled(){
		// kuddos to https://www.lastpass.com/nl/password-generator
		$password = "";
		$object = new Password();

		$this->assertFalse($object->isValid($password));
	}

	public function testPasswordMustBeAtLeastTenCharactersLong(){
		// kuddos to https://www.lastpass.com/nl/password-generator
		$password = "WGq7&*4T";
		$object = new Password();

		$this->assertFalse($object->isValid($password));
	}

	public function testPasswordMustHaveDigit(){
		// kuddos to https://www.lastpass.com/nl/password-generator
		$password = "WGq&*TDpXWGq&*";
		$object = new Password();

		$this->assertFalse($object->isValid($password));
	}
	public function testPasswordMustHaveLowercaseCharacter(){
		$password = "WG7&*4T3DX&*4T3DX";
		$object = new Password();

		$this->assertFalse($object->isValid($password));
	}
	public function testPasswordMustHaveUppercaseCharacter(){
		$password = "q7&*43pq7&*43p";
		$object = new Password();

		$this->assertFalse($object->isValid($password));
	}
	public function testPasswordMustHaveGSpecialCharacter(){
		$password = "WGq74T3DpXWGq74T3DpX";
		$object = new Password();

		$this->assertFalse($object->isValid($password));
	}

	public function testPasswordIsCorrect(){
		// kuddos to https://www.lastpass.com/nl/password-generator
		$password = "WGq7&*4T3DpX";
		$object = new Password();

		$this->assertTrue($object->isValid($password));
	}

}
?>