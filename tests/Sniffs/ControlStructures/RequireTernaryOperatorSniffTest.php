<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use SlevomatCodingStandard\Sniffs\TestCase;

class RequireTernaryOperatorSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireTernaryOperatorNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireTernaryOperatorErrors.php');

		self::assertSame(10, $report->getErrorCount());

		self::assertSniffError($report, 4, RequireTernaryOperatorSniff::CODE_TERNARY_OPERATOR_NOT_USED);
		self::assertSniffError($report, 12, RequireTernaryOperatorSniff::CODE_TERNARY_OPERATOR_NOT_USED);
		self::assertSniffError($report, 21, RequireTernaryOperatorSniff::CODE_TERNARY_OPERATOR_NOT_USED);
		self::assertSniffError($report, 29, RequireTernaryOperatorSniff::CODE_TERNARY_OPERATOR_NOT_USED);
		self::assertSniffError($report, 35, RequireTernaryOperatorSniff::CODE_TERNARY_OPERATOR_NOT_USED);
		self::assertSniffError($report, 42, RequireTernaryOperatorSniff::CODE_TERNARY_OPERATOR_NOT_USED);
		self::assertSniffError($report, 54, RequireTernaryOperatorSniff::CODE_TERNARY_OPERATOR_NOT_USED);
		self::assertSniffError($report, 63, RequireTernaryOperatorSniff::CODE_TERNARY_OPERATOR_NOT_USED);
		self::assertSniffError($report, 75, RequireTernaryOperatorSniff::CODE_TERNARY_OPERATOR_NOT_USED);
		self::assertSniffError($report, 82, RequireTernaryOperatorSniff::CODE_TERNARY_OPERATOR_NOT_USED);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWithIgnoredMultiLineNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireTernaryOperatorWithIgnoredMultiLineNoErrors.php', [
			'ignoreMultiLine' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWithIgnoredMultiLineErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireTernaryOperatorWithIgnoredMultiLineErrors.php', [
			'ignoreMultiLine' => true,
		]);

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError($report, 3, RequireTernaryOperatorSniff::CODE_TERNARY_OPERATOR_NOT_USED);
		self::assertSniffError($report, 16, RequireTernaryOperatorSniff::CODE_TERNARY_OPERATOR_NOT_USED);
	}

	/**
	 * @return void
	 */
	public function testIfWithoutCurlyBraces()
	{
		$report = self::checkFile(__DIR__ . '/data/requireTernaryOperatorIfWithoutCurlyBraces.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testElseWithoutCurlyBraces()
	{
		$report = self::checkFile(__DIR__ . '/data/requireTernaryOperatorElseWithoutCurlyBraces.php');
		self::assertNoSniffErrorInFile($report);
	}

}
