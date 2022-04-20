<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class EmptyLinesAroundClassBracesSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testCorrectCorrectEmptyLines()
	{
		$report = self::checkFile(__DIR__ . '/data/classBracesCorrectEmptyLines.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNoEmptyLineAfterOpeningBrace()
	{
		$report = self::checkFile(
			__DIR__ . '/data/classBracesNoEmptyLineAfterOpeningBrace.php',
			[],
			[EmptyLinesAroundClassBracesSniff::CODE_NO_EMPTY_LINE_AFTER_OPENING_BRACE]
		);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 4, EmptyLinesAroundClassBracesSniff::CODE_NO_EMPTY_LINE_AFTER_OPENING_BRACE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testMultipleEmptyLinesAfterOpeningBrace()
	{
		$report = self::checkFile(
			__DIR__ . '/data/classBracesMultipleEmptyLinesAfterOpeningBrace.php',
			[],
			[EmptyLinesAroundClassBracesSniff::CODE_MULTIPLE_EMPTY_LINES_AFTER_OPENING_BRACE]
		);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 4, EmptyLinesAroundClassBracesSniff::CODE_MULTIPLE_EMPTY_LINES_AFTER_OPENING_BRACE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNoEmptyLineBeforeClosingBrace()
	{
		$report = self::checkFile(
			__DIR__ . '/data/classBracesNoEmptyLineBeforeClosingBrace.php',
			[],
			[EmptyLinesAroundClassBracesSniff::CODE_NO_EMPTY_LINE_BEFORE_CLOSING_BRACE]
		);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 10, EmptyLinesAroundClassBracesSniff::CODE_NO_EMPTY_LINE_BEFORE_CLOSING_BRACE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testMultipleEmptyLinesBeforeClosingBrace()
	{
		$report = self::checkFile(
			__DIR__ . '/data/classBracesMultipleEmptyLinesBeforeClosingBrace.php',
			[],
			[EmptyLinesAroundClassBracesSniff::CODE_MULTIPLE_EMPTY_LINES_BEFORE_CLOSING_BRACE]
		);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 13, EmptyLinesAroundClassBracesSniff::CODE_MULTIPLE_EMPTY_LINES_BEFORE_CLOSING_BRACE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testCorrectCorrectEmptyLinesWithZeroLines()
	{
		$report = self::checkFile(__DIR__ . '/data/classBracesCorrectEmptyLinesZeroLines.php', [
			'linesCountAfterOpeningBrace' => 0,
			'linesCountBeforeClosingBrace' => 0,
		]);

		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testOneLineAfterOpeningBraceWithZeroExpected()
	{
		$report = self::checkFile(
			__DIR__ . '/data/classBracesOneEmptyLineAfterOpeningBraceWithZeroExpected.php',
			['linesCountAfterOpeningBrace' => 0],
			[EmptyLinesAroundClassBracesSniff::CODE_INCORRECT_EMPTY_LINES_AFTER_OPENING_BRACE]
		);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 4, EmptyLinesAroundClassBracesSniff::CODE_INCORRECT_EMPTY_LINES_AFTER_OPENING_BRACE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testOneLineBeforeClosingBraceWithZeroExpected()
	{
		$report = self::checkFile(
			__DIR__ . '/data/classBracesOneEmptyLineBeforeClosingBraceWithZeroExpected.php',
			['linesCountBeforeClosingBrace' => 0],
			[EmptyLinesAroundClassBracesSniff::CODE_INCORRECT_EMPTY_LINES_BEFORE_CLOSING_BRACE]
		);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 10, EmptyLinesAroundClassBracesSniff::CODE_INCORRECT_EMPTY_LINES_BEFORE_CLOSING_BRACE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testCorrectCorrectEmptyLinesWithTwoLines()
	{
		$report = self::checkFile(__DIR__ . '/data/classBracesCorrectEmptyLinesTwoLines.php', [
			'linesCountAfterOpeningBrace' => 2,
			'linesCountBeforeClosingBrace' => 2,
		]);

		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testOneLineAfterOpeningBraceWithTwoExpected()
	{
		$report = self::checkFile(
			__DIR__ . '/data/classBracesOneEmptyLineAfterOpeningBraceWithTwoExpected.php',
			['linesCountAfterOpeningBrace' => 2],
			[EmptyLinesAroundClassBracesSniff::CODE_INCORRECT_EMPTY_LINES_AFTER_OPENING_BRACE]
		);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 4, EmptyLinesAroundClassBracesSniff::CODE_INCORRECT_EMPTY_LINES_AFTER_OPENING_BRACE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testOneLineBeforeClosingBraceWithTwoExpected()
	{
		$report = self::checkFile(
			__DIR__ . '/data/classBracesOneEmptyLineBeforeClosingBraceWithTwoExpected.php',
			['linesCountBeforeClosingBrace' => 2],
			[EmptyLinesAroundClassBracesSniff::CODE_INCORRECT_EMPTY_LINES_BEFORE_CLOSING_BRACE]
		);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 12, EmptyLinesAroundClassBracesSniff::CODE_INCORRECT_EMPTY_LINES_BEFORE_CLOSING_BRACE);

		self::assertAllFixedInFile($report);
	}

}
