<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use SlevomatCodingStandard\Sniffs\TestCase;

class UselessIfConditionWithReturnSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/uselessIfConditionWithReturnNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/uselessIfConditionWithReturnErrors.php');

		self::assertSame(12, $report->getErrorCount());

		self::assertSniffError($report, 4, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);
		self::assertSniffError($report, 12, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);
		self::assertSniffError($report, 20, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);
		self::assertSniffError($report, 28, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);
		self::assertSniffError($report, 36, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);
		self::assertSniffError($report, 44, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);
		self::assertSniffError($report, 52, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);
		self::assertSniffError($report, 61, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);
		self::assertSniffError($report, 70, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);
		self::assertSniffError($report, 82, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);
		self::assertSniffError($report, 91, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);
		self::assertSniffError($report, 100, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrorsWithAssumeAllConditionExpressionsAreAlreadyBooleanEnabled()
	{
		$report = self::checkFile(
			__DIR__ . '/data/uselessIfConditionWithReturnErrorsWithAssumeAllConditionExpressionsAreAlreadyBooleanEnabled.php',
			[
				'assumeAllConditionExpressionsAreAlreadyBoolean' => true,
			]
		);

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError($report, 4, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);
		self::assertSniffError($report, 13, UselessIfConditionWithReturnSniff::CODE_USELESS_IF_CONDITION);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testIfWithoutCurlyBraces()
	{
		$report = self::checkFile(__DIR__ . '/data/uselessIfConditionWithReturnIfWithoutCurlyBraces.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testElseWithoutCurlyBraces()
	{
		$report = self::checkFile(__DIR__ . '/data/uselessIfConditionWithReturnElseWithoutCurlyBraces.php');
		self::assertNoSniffErrorInFile($report);
	}

}
