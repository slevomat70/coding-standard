<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class ConstantSpacingSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/constantSpacingNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/constantSpacingErrors.php');

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 5, ConstantSpacingSniff::CODE_INCORRECT_COUNT_OF_BLANK_LINES_AFTER_CONSTANT);
		self::assertSniffError($report, 22, ConstantSpacingSniff::CODE_INCORRECT_COUNT_OF_BLANK_LINES_AFTER_CONSTANT);
		self::assertSniffError($report, 26, ConstantSpacingSniff::CODE_INCORRECT_COUNT_OF_BLANK_LINES_AFTER_CONSTANT);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrorsWithModifiedLinesCount()
	{
		$report = self::checkFile(__DIR__ . '/data/constantSpacingErrors.php', [
			'minLinesCountBeforeWithComment' => 2,
			'maxLinesCountBeforeWithComment' => 2,
			'maxLinesCountBeforeWithoutComment' => 2,
		]);

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError($report, 5, ConstantSpacingSniff::CODE_INCORRECT_COUNT_OF_BLANK_LINES_AFTER_CONSTANT);
		self::assertSniffError($report, 26, ConstantSpacingSniff::CODE_INCORRECT_COUNT_OF_BLANK_LINES_AFTER_CONSTANT);
	}

	/**
	 * @return void
	 */
	public function testInGlobalNamespaceNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/constantSpacingInGlobalNamespaceNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

}
