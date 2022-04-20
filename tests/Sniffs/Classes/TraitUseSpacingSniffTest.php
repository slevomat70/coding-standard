<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class TraitUseSpacingSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoUses()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingNoUses.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNoTraitUses()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingNoTraitUses.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDefaultSettingNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingDefaultSettingsNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDefaultSettingErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingDefaultSettingsErrors.php');

		self::assertSame(5, $report->getErrorCount());

		self::assertSniffError($report, 5, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);
		self::assertSniffError($report, 7, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES);
		self::assertSniffError($report, 11, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES);
		self::assertSniffError($report, 18, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES);
		self::assertSniffError($report, 18, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testOneUseDefaultSettingNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingOneUseDefaultSettingsNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testOneUseDefaultSettingErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingOneUseDefaultSettingsErrors.php');

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError($report, 5, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);
		self::assertSniffError($report, 5, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDefaultSettingWithCommentsNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingDefaultSettingsWithCommentsNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDefaultSettingWithCommentsErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingDefaultSettingsWithCommentsErrors.php');

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError($report, 8, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);
		self::assertSniffError($report, 14, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testModifiedSettingNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingModifiedSettingsNoErrors.php', [
			'linesCountBeforeFirstUse' => 0,
			'linesCountBeforeFirstUseWhenFirstInClass' => 0,
			'linesCountBetweenUses' => 1,
			'linesCountAfterLastUse' => 2,
			'linesCountAfterLastUseWhenLastInClass' => 2,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testModifiedSettingErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingModifiedSettingsErrors.php', [
			'linesCountBeforeFirstUse' => 0,
			'linesCountBeforeFirstUseWhenFirstInClass' => 0,
			'linesCountBetweenUses' => 1,
			'linesCountAfterLastUse' => 2,
			'linesCountAfterLastUseWhenLastInClass' => 2,
		]);

		self::assertSame(5, $report->getErrorCount());

		self::assertSniffError($report, 6, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);
		self::assertSniffError($report, 7, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES);
		self::assertSniffError($report, 12, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES);
		self::assertSniffError($report, 13, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES);
		self::assertSniffError($report, 13, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testModifiedSettingWhenUseIsFirstInClassNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingModifiedSettingsWhenUseIsLastInClassNoErrors.php', [
			'linesCountBeforeFirstUse' => 2,
			'linesCountBeforeFirstUseWhenFirstInClass' => 0,
			'linesCountBetweenUses' => 1,
			'linesCountAfterLastUse' => 0,
			'linesCountAfterLastUseWhenLastInClass' => 0,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testModifiedSettingWhenUseIsFirstInClassErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingModifiedSettingsWhenUseIsLastInClassErrors.php', [
			'linesCountBeforeFirstUse' => 2,
			'linesCountBeforeFirstUseWhenFirstInClass' => 0,
			'linesCountBetweenUses' => 1,
			'linesCountAfterLastUse' => 0,
			'linesCountAfterLastUseWhenLastInClass' => 0,
		]);

		self::assertSame(5, $report->getErrorCount());

		self::assertSniffError($report, 6, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);
		self::assertSniffError($report, 7, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES);
		self::assertSniffError($report, 10, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES);
		self::assertSniffError($report, 11, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES);
		self::assertSniffError($report, 11, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testModifiedSettingWhenUseIsLastInClassNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingModifiedSettingsWhenUseIsLastInClassNoErrors.php', [
			'linesCountBeforeFirstUse' => 0,
			'linesCountBeforeFirstUseWhenFirstInClass' => 0,
			'linesCountBetweenUses' => 1,
			'linesCountAfterLastUse' => 2,
			'linesCountAfterLastUseWhenLastInClass' => 0,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testModifiedSettingWhenUseIsLastInClassErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingModifiedSettingsWhenUseIsLastInClassErrors.php', [
			'linesCountBeforeFirstUse' => 0,
			'linesCountBeforeFirstUseWhenFirstInClass' => 0,
			'linesCountBetweenUses' => 1,
			'linesCountAfterLastUse' => 2,
			'linesCountAfterLastUseWhenLastInClass' => 0,
		]);

		self::assertSame(5, $report->getErrorCount());

		self::assertSniffError($report, 6, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);
		self::assertSniffError($report, 7, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES);
		self::assertSniffError($report, 10, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES);
		self::assertSniffError($report, 11, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_USES);
		self::assertSniffError($report, 11, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testModifiedSettingWhenUseIsLastInClass2Errors()
	{
		$report = self::checkFile(__DIR__ . '/data/traitUseSpacingModifiedSettingsWhenUseIsLastInClass2Errors.php', [
			'linesCountBeforeFirstUse' => 0,
			'linesCountBeforeFirstUseWhenFirstInClass' => 1,
			'linesCountBetweenUses' => 0,
			'linesCountAfterLastUse' => 1,
			'linesCountAfterLastUseWhenLastInClass' => 0,
		]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 11, TraitUseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);

		self::assertAllFixedInFile($report);
	}

}
