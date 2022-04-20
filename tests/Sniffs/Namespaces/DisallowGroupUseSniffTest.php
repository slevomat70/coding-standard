<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use SlevomatCodingStandard\Sniffs\TestCase;

class DisallowGroupUseSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowGroupUseNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowGroupUseErrors.php');

		self::assertSniffError($report, 5, DisallowGroupUseSniff::CODE_DISALLOWED_GROUP_USE);
		self::assertSniffError($report, 9, DisallowGroupUseSniff::CODE_DISALLOWED_GROUP_USE);
	}

}
