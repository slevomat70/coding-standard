<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use SlevomatCodingStandard\Sniffs\TestCase;

class RequireMultiLineConditionSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineConditionNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineConditionErrors.php');

		self::assertSame(5, $report->getErrorCount());

		self::assertSniffError(
			$report,
			4,
			RequireMultiLineConditionSniff::CODE_REQUIRED_MULTI_LINE_CONDITION,
			'Condition of "if" should be split to more lines so each condition part is on its own line.'
		);
		self::assertSniffError(
			$report,
			6,
			RequireMultiLineConditionSniff::CODE_REQUIRED_MULTI_LINE_CONDITION,
			'Condition of "elseif" should be split to more lines so each condition part is on its own line.'
		);
		self::assertSniffError(
			$report,
			10,
			RequireMultiLineConditionSniff::CODE_REQUIRED_MULTI_LINE_CONDITION,
			'Condition of "while" should be split to more lines so each condition part is on its own line.'
		);
		self::assertSniffError(
			$report,
			18,
			RequireMultiLineConditionSniff::CODE_REQUIRED_MULTI_LINE_CONDITION,
			'Condition of "do-while" should be split to more lines so each condition part is on its own line.'
		);
		self::assertSniffError(
			$report,
			20,
			RequireMultiLineConditionSniff::CODE_REQUIRED_MULTI_LINE_CONDITION,
			'Condition of "if" should be split to more lines so each condition part is on its own line.'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testForAllConditionsErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineConditionAllConditionsErrors.php', [
			'minLineLength' => 0,
		]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 4, RequireMultiLineConditionSniff::CODE_REQUIRED_MULTI_LINE_CONDITION);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNoErrorsWhenDisabledIfControlStructure()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineConditionWhenDisabledIfControlStructureNoErrors.php', [
			'checkedControlStructures' => ['while'],
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNoErrorsWhenDisabledDoControlStructure()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineConditionWhenDisabledDoControlStructureNoErrors.php', [
			'checkedControlStructures' => ['while'],
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNoErrorsWhenDisabledWhileControlStructure()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineConditionWhenDisabledWhileControlStructureNoErrors.php', [
			'checkedControlStructures' => ['do'],
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixerWhenBooleanOperatorOnPreviousLineEnabled()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineConditionFixerWhenBooleanOperatorOnPreviousLineEnabled.php', [
			'booleanOperatorOnPreviousLine' => true,
		]);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWithAlwaysSplitAllConditionPartsEnabledNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineConditionWithAlwaysSplitAllConditionPartsEnabledNoErrors.php', [
			'alwaysSplitAllConditionParts' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWithAlwaysSplitAllConditionPartsEnabledErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineConditionWithAlwaysSplitAllConditionPartsEnabledErrors.php', [
			'alwaysSplitAllConditionParts' => true,
		]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 4, RequireMultiLineConditionSniff::CODE_REQUIRED_MULTI_LINE_CONDITION);

		self::assertAllFixedInFile($report);
	}

}
