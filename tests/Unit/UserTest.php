<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
	public function testClassUserExists()
	{
		$this->assertTrue(class_exists("User"));
	}
}