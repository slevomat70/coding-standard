<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use SlevomatCodingStandard\Sniffs\TestCase;

class UseSpacingSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoUseNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingNoUse.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testOneUseNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingOneUse.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDefaultSettingsNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingWithDefaultSettingsNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDefaultSettingsWithCommentsNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingWithDefaultSettingsWithCommentsNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDefaultSettingsWithCommentsErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingWithDefaultSettingsWithCommentsErrors.php');

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError($report, 7, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);
		self::assertSniffError($report, 12, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_TYPES_OF_USE);
		self::assertSniffError($report, 18, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_SAME_TYPES_OF_USE);
		self::assertSniffError($report, 18, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDefaultSettingsWithComments2Errors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingWithDefaultSettingsWithComments2Errors.php');

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 3, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDefaultSettingsWithInlineCommentsNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingWithDefaultSettingsWithInlineCommentsNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDefaultSettingsWithInlineCommentsErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingWithDefaultSettingsWithInlineCommentsErrors.php');

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError($report, 5, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);
		self::assertSniffError($report, 8, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_TYPES_OF_USE);
		self::assertSniffError($report, 12, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_SAME_TYPES_OF_USE);
		self::assertSniffError($report, 12, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDefaultSettingsErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingWithDefaultSettingsErrors.php');

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError($report, 2, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);
		self::assertSniffError($report, 4, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAfterOpenTagNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingAfterOpenTagNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAfterOpenTagErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingAfterOpenTagErrors.php');

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 2, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testModifiedSettingsNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingWithModifiedSettingsNoErrors.php', [
			'linesCountBeforeFirstUse' => 0,
			'linesCountBetweenUseTypes' => 1,
			'linesCountAfterLastUse' => 2,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testModifiedSettingsErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingWithModifiedSettingsErrors.php', [
			'linesCountBeforeFirstUse' => 0,
			'linesCountBetweenUseTypes' => 1,
			'linesCountAfterLastUse' => 2,
		]);

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError($report, 4, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);
		self::assertSniffError($report, 5, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_TYPES_OF_USE);
		self::assertSniffError($report, 8, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_TYPES_OF_USE);
		self::assertSniffError($report, 8, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testMultipleUseTypesErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingMultipleUseTypesErrors.php', [
			'linesCountBeforeFirstUse' => 1,
			'linesCountBetweenUseTypes' => 1,
			'linesCountAfterLastUse' => 1,
		]);

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError($report, 5, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_TYPES_OF_USE);
		self::assertSniffError($report, 6, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_TYPES_OF_USE);
		self::assertSniffError($report, 8, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_TYPES_OF_USE);
		self::assertSniffError($report, 9, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_TYPES_OF_USE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNoLineBeforeUse()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingNoLineBeforeUse.php');

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 3, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);
	}

	/**
	 * @return void
	 */
	public function testNoLineAfterUse()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingNoLineAfterUse.php');

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 3, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);
	}

	/**
	 * @return void
	 */
	public function testNoCodeAfterUse()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingNoCodeAfterUse.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testSameUseTypesErrors()
	{
		$report = self::checkFile(
			__DIR__ . '/data/useSpacingSameUseTypesErrors.php',
			[],
			[UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_SAME_TYPES_OF_USE]
		);

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 6, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_SAME_TYPES_OF_USE);
		self::assertSniffError($report, 12, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_SAME_TYPES_OF_USE);
		self::assertSniffError($report, 20, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_SAME_TYPES_OF_USE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testMoreNamespaces()
	{
		$report = self::checkFile(__DIR__ . '/data/useSpacingMoreNamespacesErrors.php');

		self::assertSame(6, $report->getErrorCount());

		self::assertSniffError($report, 4, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);
		self::assertSniffError($report, 6, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);
		self::assertSniffError($report, 15, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE);
		self::assertSniffError($report, 17, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_TYPES_OF_USE);
		self::assertSniffError($report, 19, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_TYPES_OF_USE);
		self::assertSniffError($report, 19, UseSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE);

		self::assertAllFixedInFile($report);
	}

}
