<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\TypeHints;

use SlevomatCodingStandard\Sniffs\TestCase;

class DeclareStrictTypesSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testMultipleOpenTagsInFile()
	{
		$report = self::checkFile(__DIR__ . '/data/declareStrictTypesMultipleOpenTags.php', [
			'declareOnFirstLine' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return mixed[][]
	 */
	public function dataDeclareStrictTypesMissing(): array
	{
		return [
			[
				__DIR__ . '/data/declareStrictTypesMissing.php',
				1,
			],
			[
				__DIR__ . '/data/declareStrictTypesMissingEmptyFile.php',
				1,
			],
			[
				__DIR__ . '/data/declareTicks.php',
				3,
			],
			[
				__DIR__ . '/data/declareStrictTypesZero.php',
				3,
			],
		];
	}

	/**
	 * @dataProvider dataDeclareStrictTypesMissing
	 * @param string $file
	 * @param int $line
	 * @return void
	 */
	public function testDeclareStrictTypesMissing($file, $line)
	{
		$report = self::checkFile($file);
		self::assertSniffError($report, $line, DeclareStrictTypesSniff::CODE_DECLARE_STRICT_TYPES_MISSING);
	}

	/**
	 * @return string[][]
	 */
	public function dataDeclareStrictTypesIncorrectFormat(): array
	{
		return [
			[
				__DIR__ . '/data/declareStrictTypesIncorrectFormat1.php',
			],
			[
				__DIR__ . '/data/declareStrictTypesIncorrectFormat2.php',
			],
			[
				__DIR__ . '/data/declareStrictTypesIncorrectFormat3.php',
			],
		];
	}

	/**
	 * @dataProvider dataDeclareStrictTypesIncorrectFormat
	 * @param string $file
	 * @return void
	 */
	public function testDeclareStrictTypesIncorrectFormat($file)
	{
		$report = self::checkFile($file);
		self::assertSniffError($report, 1, DeclareStrictTypesSniff::CODE_INCORRECT_STRICT_TYPES_FORMAT);
	}

	/**
	 * @return void
	 */
	public function testEmptyFile()
	{
		$report = self::checkFile(__DIR__ . '/data/declareStrictTypesEmptyFile.php', [
			'declareOnFirstLine' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDeclareStrictTypesIncorrectFormatNoSpaces()
	{
		$report = self::checkFile(__DIR__ . '/data/declareStrictTypesIncorrectFormatNoSpaces.php', [
			'spacesCountAroundEqualsSign' => 0,
		]);
		self::assertSniffError($report, 1, DeclareStrictTypesSniff::CODE_INCORRECT_STRICT_TYPES_FORMAT);
	}

	/**
	 * @return void
	 */
	public function testDeclareStrictTwoNewlinesBefore()
	{
		$report = self::checkFile(__DIR__ . '/data/declareStrictTypesTwoNewlinesBefore.php', [
			'linesCountBeforeDeclare' => ' 1  ',
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDeclareStrictTwoNewlinesBeforeError()
	{
		$report = self::checkFile(__DIR__ . '/data/declareStrictTypesTwoNewlinesBeforeError.php', [
			'declareOnFirstLine' => true,
		]);
		self::assertSniffError(
			$report,
			3,
			DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_BEFORE_DECLARE,
			'There must be a single space between the PHP open tag and declare statement.'
		);
	}

	/**
	 * @return void
	 */
	public function testDeclareStrictTwoNewlinesAfter()
	{
		$report = self::checkFile(__DIR__ . '/data/declareStrictTypesTwoNewlinesAfter.php', [
			'linesCountAfterDeclare' => ' 1  ',
		], [DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_AFTER_DECLARE]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDeclareStrictTwoNewlinesAfterError()
	{
		$report = self::checkFile(__DIR__ . '/data/declareStrictTypesTwoNewlinesAfterError.php');
		self::assertSniffError(
			$report,
			3,
			DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_AFTER_DECLARE,
			'Expected 1 line after declare statement, found 0.'
		);
	}

	/**
	 * @return void
	 */
	public function testDeclareStrictOneSpaceError()
	{
		$report = self::checkFile(__DIR__ . '/data/declareStrictTypesOneSpaceError.php', [
			'linesCountBeforeDeclare' => '1',
		]);
		self::assertSniffError(
			$report,
			1,
			DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_BEFORE_DECLARE,
			'Expected 1 line before declare statement, found 0.'
		);
	}

	/**
	 * @return void
	 */
	public function testDeclareStrictOneSpace()
	{
		$report = self::checkFile(__DIR__ . '/data/declareStrictTypesOneSpace.php', [
			'declareOnFirstLine' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDeclareStrictWithFileCommentAbove()
	{
		$report = self::checkFile(__DIR__ . '/data/declareStrictTypesWithFileCommentAbove.php', [
			'linesCountBeforeDeclare' => 1,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDeclareStrictWithTicks()
	{
		$report = self::checkFile(__DIR__ . '/data/declareStrictTypesWithTicks.php', [
			'declareOnFirstLine' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableNoNewLinesBefore()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableDeclareStrictTypesNoNewLinesBefore.php', [
			'declareOnFirstLine' => true,
		], [DeclareStrictTypesSniff::CODE_DECLARE_STRICT_TYPES_MISSING, DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_BEFORE_DECLARE]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableMissingNoNewLines()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableDeclareStrictTypesMissingNoNewLines.php', [
			'declareOnFirstLine' => true,
		], [DeclareStrictTypesSniff::CODE_DECLARE_STRICT_TYPES_MISSING, DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_BEFORE_DECLARE]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableOneNewLineBefore()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableDeclareStrictTypesOneNewLineBefore.php', [
			'linesCountBeforeDeclare' => 0,
		], [DeclareStrictTypesSniff::CODE_DECLARE_STRICT_TYPES_MISSING, DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_BEFORE_DECLARE]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableOneNewLineBeforeWithDeclareOnFirstLine()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableDeclareStrictTypesOneNewLineBeforeWithDeclareOnFirstLine.php', [
			'linesCountBeforeDeclare' => 0,
		], [DeclareStrictTypesSniff::CODE_DECLARE_STRICT_TYPES_MISSING, DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_BEFORE_DECLARE]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableMissingOneNewLine()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableDeclareStrictTypesMissingOneNewLine.php', [
			'linesCountBeforeDeclare' => 0,
		], [DeclareStrictTypesSniff::CODE_DECLARE_STRICT_TYPES_MISSING, DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_BEFORE_DECLARE]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableMoreNewLinesBefore()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableDeclareStrictTypesMoreNewLinesBefore.php', [
			'linesCountBeforeDeclare' => 3,
		], [DeclareStrictTypesSniff::CODE_DECLARE_STRICT_TYPES_MISSING, DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_BEFORE_DECLARE]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableMissingMoreNewLines()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableDeclareStrictTypesMissingMoreNewLines.php', [
			'linesCountBeforeDeclare' => 3,
		], [DeclareStrictTypesSniff::CODE_DECLARE_STRICT_TYPES_MISSING, DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_BEFORE_DECLARE]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableCommentBefore()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableDeclareStrictTypesCommentBefore.php', [
			'linesCountBeforeDeclare' => 1,
		], [DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_BEFORE_DECLARE]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableMissingIncorrectFormatOneSpace()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fixableDeclareStrictTypesIncorrectFormatOneSpace.php',
			[],
			[DeclareStrictTypesSniff::CODE_INCORRECT_STRICT_TYPES_FORMAT]
		);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableMissingIncorrectFormatNoSpaces()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableDeclareStrictTypesIncorrectFormatNoSpaces.php', [
			'spacesCountAroundEqualsSign' => 0,
		], [DeclareStrictTypesSniff::CODE_INCORRECT_STRICT_TYPES_FORMAT]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableMissingIncorrectFormatMoreSpaces()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableDeclareStrictTypesIncorrectFormatMoreSpaces.php', [
			'spacesCountAroundEqualsSign' => 4,
		], [DeclareStrictTypesSniff::CODE_INCORRECT_STRICT_TYPES_FORMAT]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableMissingWithTicks()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fixableDeclareStrictTypesMissingWithTicks.php',
			[],
			[DeclareStrictTypesSniff::CODE_DECLARE_STRICT_TYPES_MISSING]
		);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableDisabled()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fixableDeclareStrictTypesDisabled.php',
			[],
			[DeclareStrictTypesSniff::CODE_DECLARE_STRICT_TYPES_MISSING]
		);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableOneNewLineAfter()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableDeclareStrictTypesOneNewLineAfter.php', [
			'linesCountAfterDeclare' => 1,
		], [DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_AFTER_DECLARE]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableMoreNewLinesAfter()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableDeclareStrictTypesMoreNewLinesAfter.php', [
			'linesCountAfterDeclare' => 3,
		], [DeclareStrictTypesSniff::CODE_INCORRECT_WHITESPACE_AFTER_DECLARE]);
		self::assertAllFixedInFile($report);
	}

}
