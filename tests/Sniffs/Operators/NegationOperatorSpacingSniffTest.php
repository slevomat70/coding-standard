<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Operators;

use SlevomatCodingStandard\Sniffs\TestCase;

class NegationOperatorSpacingSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/negationOperatorSpacingNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/negationOperatorSpacingErrors.php');

		self::assertSame(95, $report->getErrorCount());

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testRequireSpaceNoErrors()
	{
		self::assertNoSniffErrorInFile(
			self::checkFile(__DIR__ . '/data/negationOperatorSpacingRequireSpaceNoErrors.php', ['spacesCount' => 1])
		);
	}

	/**
	 * @return void
	 */
	public function testRequireSpaceErrors()
	{
		$report = self::checkFile(
			__DIR__ . '/data/negationOperatorSpacingRequireSpaceErrors.php',
			['spacesCount' => 1]
		);

		self::assertSame(95, $report->getErrorCount());

		self::assertAllFixedInFile($report);
	}

}
