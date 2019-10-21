<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
	public function testClassAccountExists()
	{
		$this->assertTrue(class_exists("Account"));
	}
}