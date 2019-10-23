<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
	public function testClassAccountExists()
	{
		$this->assertTrue(class_exists("Account"));
	}

	public function testStillActiveSucceedsBecauseLastActiveIsBetweenNowAndThen()
	{
		/** The last active date is in between then and now */
		$object = new Account();
		$duration = $object->getDuration();

		$lastActive = new DateTime();
		$lastActive->sub(new DateInterval("PT" . ($duration/2) . "M"));

		$this->assertTrue($object->stillActive($lastActive));
	}

	public function testStillActiveFailsBecauseLastActiveIsBeforeThen()
	{
		/** The last active date is before the then date */
		$object = new Account();
		$duration = $object->getDuration();

		$lastActive = new DateTime();
		$lastActive->sub(new DateInterval("PT" . ($duration * 2) . "M"));

		$this->assertFalse($object->stillActive($lastActive));
	}

	public function testStillActiveFailsBecauseLastActiveIsInTheFuture()
	{
		/** The last active date is before the then date */
		$object = new Account();
		$duration = $object->getDuration();

		$lastActive = new DateTime();
		$lastActive->add(new DateInterval("PT" . ($duration * 2) . "M"));

		$this->assertFalse($object->stillActive($lastActive));
	}

	public function testStillActiveFailsBecauseNoUserLoggedIn()
	{
		$object = new Account();
		$this->assertFalse($object->stillActive());
	}
}