<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use SlevomatCodingStandard\Sniffs\TestCase;

class NewWithParenthesesSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/newWithParenthesesNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/newWithParenthesesErrors.php', [], [NewWithParenthesesSniff::CODE_MISSING_PARENTHESES]);

		self::assertSame(20, $report->getErrorCount());

		foreach ([3, 9, 17, 22, 25, 29, 35, 37, 40, 43, 45, 47, 48, 50, 52, 57, 60] as $line) {
			self::assertSniffError($report, $line, NewWithParenthesesSniff::CODE_MISSING_PARENTHESES);
		}

		self::assertAllFixedInFile($report);
	}

}
