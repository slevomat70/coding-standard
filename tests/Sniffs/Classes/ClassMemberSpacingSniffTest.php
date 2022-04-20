<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class ClassMemberSpacingSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/classMemberSpacingNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/classMemberSpacingErrors.php');

		self::assertSame(5, $report->getErrorCount());

		self::assertSniffError($report, 15, ClassMemberSpacingSniff::CODE_INCORRECT_COUNT_OF_BLANK_LINES_BETWEEN_MEMBERS);
		self::assertSniffError($report, 21, ClassMemberSpacingSniff::CODE_INCORRECT_COUNT_OF_BLANK_LINES_BETWEEN_MEMBERS);
		self::assertSniffError($report, 33, ClassMemberSpacingSniff::CODE_INCORRECT_COUNT_OF_BLANK_LINES_BETWEEN_MEMBERS);
		self::assertSniffError($report, 38, ClassMemberSpacingSniff::CODE_INCORRECT_COUNT_OF_BLANK_LINES_BETWEEN_MEMBERS);
		self::assertSniffError($report, 44, ClassMemberSpacingSniff::CODE_INCORRECT_COUNT_OF_BLANK_LINES_BETWEEN_MEMBERS);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrorsWithModifiedLinecCount()
	{
		$report = self::checkFile(__DIR__ . '/data/classMemberSpacingErrors.php', [
			'linesCountBetweenMembers' => 2,
		]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 21, ClassMemberSpacingSniff::CODE_INCORRECT_COUNT_OF_BLANK_LINES_BETWEEN_MEMBERS);
	}

}
