<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use SlevomatCodingStandard\Sniffs\TestCase;

class RequireSingleLineCallSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireSingleLineCallNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireSingleLineCallErrors.php');

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError(
			$report,
			7,
			RequireSingleLineCallSniff::CODE_REQUIRED_SINGLE_LINE_CALL,
			'Call of method doAnything() should be placed on a single line.'
		);
		self::assertSniffError(
			$report,
			12,
			RequireSingleLineCallSniff::CODE_REQUIRED_SINGLE_LINE_CALL,
			'Call of function sprintf() should be placed on a single line.'
		);
		self::assertSniffError(
			$report,
			19,
			RequireSingleLineCallSniff::CODE_REQUIRED_SINGLE_LINE_CALL,
			'Constructor call should be placed on a single line.'
		);
		self::assertSniffError(
			$report,
			25,
			RequireSingleLineCallSniff::CODE_REQUIRED_SINGLE_LINE_CALL,
			'Call of function printf() should be placed on a single line.'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testForAllCallsErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireSingleLineCallAllCallsErrors.php', [
			'maxLineLength' => 0,
		]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 7, RequireSingleLineCallSniff::CODE_REQUIRED_SINGLE_LINE_CALL);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWithComplexParametersEnabledErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireSingleLineCallWithComplexParametersEnabledErrors.php', [
			'ignoreWithComplexParameter' => false,
		]);

		self::assertSame(5, $report->getErrorCount());

		self::assertSniffError(
			$report,
			7,
			RequireSingleLineCallSniff::CODE_REQUIRED_SINGLE_LINE_CALL,
			'Call of method doSomething() should be placed on a single line.'
		);
		self::assertSniffError(
			$report,
			16,
			RequireSingleLineCallSniff::CODE_REQUIRED_SINGLE_LINE_CALL,
			'Call of method doAnything() should be placed on a single line.'
		);
		self::assertSniffError(
			$report,
			20,
			RequireSingleLineCallSniff::CODE_REQUIRED_SINGLE_LINE_CALL,
			'Constructor call should be placed on a single line.'
		);
		self::assertSniffError(
			$report,
			25,
			RequireSingleLineCallSniff::CODE_REQUIRED_SINGLE_LINE_CALL,
			'Call of method doWhatever() should be placed on a single line.'
		);
		self::assertSniffError(
			$report,
			29,
			RequireSingleLineCallSniff::CODE_REQUIRED_SINGLE_LINE_CALL,
			'Call of method doNothing() should be placed on a single line.'
		);

		self::assertAllFixedInFile($report);
	}

}
