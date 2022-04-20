<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use SlevomatCodingStandard\Sniffs\TestCase;

class UselessAliasSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoUses()
	{
		$report = self::checkFile(__DIR__ . '/data/uselessAliasNoUses.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/uselessAliasNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/uselessAliasErrors.php');

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 3, UselessAliasSniff::CODE_USELESS_ALIAS);
		self::assertSniffError($report, 4, UselessAliasSniff::CODE_USELESS_ALIAS);
		self::assertSniffError($report, 6, UselessAliasSniff::CODE_USELESS_ALIAS);

		self::assertAllFixedInFile($report);
	}

}
