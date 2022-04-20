<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use SlevomatCodingStandard\Sniffs\TestCase;

class FunctionLengthSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/functionLengthNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/functionLengthErrors.php');

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 3, FunctionLengthSniff::CODE_FUNCTION_LENGTH, 'Currently using 21 lines');
	}

	/**
	 * @return void
	 */
	public function testWithComments()
	{
		$report = self::checkFile(
			__DIR__ . '/data/functionLengthErrors.php',
			[
				'includeComments' => true,
			]
		);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 3, FunctionLengthSniff::CODE_FUNCTION_LENGTH, 'Currently using 24 lines');
	}

	/**
	 * @return void
	 */
	public function testWithWhitespace()
	{
		$report = self::checkFile(
			__DIR__ . '/data/functionLengthErrors.php',
			[
				'includeWhitespace' => true,
			]
		);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 3, FunctionLengthSniff::CODE_FUNCTION_LENGTH, 'Currently using 22 lines');
	}

}
