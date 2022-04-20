<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Files;

use SlevomatCodingStandard\Sniffs\TestCase;

class LineLengthSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/LineLengthSniffNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/LineLengthSniffErrors.php', ['ignoreImports' => false]);

		self::assertSame(7, $report->getErrorCount());

		self::assertSniffError($report, 5, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 7, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 10, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 12, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 15, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 19, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 20, LineLengthSniff::CODE_LINE_TOO_LONG);
	}

	/**
	 * @return void
	 */
	public function testErrorsIgnoreComments()
	{
		$report = self::checkFile(__DIR__ . '/data/LineLengthSniffErrorsIgnoreComments.php', ['ignoreComments' => true]);

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError($report, 7, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 12, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 15, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 20, LineLengthSniff::CODE_LINE_TOO_LONG);
	}

	/**
	 * @return void
	 */
	public function testErrorsIgnoreImports()
	{
		$report = self::checkFile(__DIR__ . '/data/LineLengthSniffErrorsIgnoreImports.php');

		self::assertSame(7, $report->getErrorCount());

		self::assertSniffError($report, 7, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 9, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 12, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 14, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 17, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 21, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 22, LineLengthSniff::CODE_LINE_TOO_LONG);
	}

	/**
	 * @return void
	 */
	public function testErrorsWithoutUseStatements()
	{
		$report = self::checkFile(__DIR__ . '/data/LineLengthSniffErrorsWithoutUseStatements.php');

		self::assertSame(6, $report->getErrorCount());

		self::assertSniffError($report, 5, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 8, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 10, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 13, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 17, LineLengthSniff::CODE_LINE_TOO_LONG);
		self::assertSniffError($report, 18, LineLengthSniff::CODE_LINE_TOO_LONG);
	}

}
