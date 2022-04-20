<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Commenting;

use SlevomatCodingStandard\Sniffs\TestCase;

class InlineDocCommentDeclarationSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoInvalidInlineDocCommentDeclarations()
	{
		$report = self::checkFile(__DIR__ . '/data/noInvalidInlineDocCommentDeclarations.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testInvalidInlineDocCommentDeclarations()
	{
		$report = self::checkFile(__DIR__ . '/data/invalidInlineDocCommentDeclarations.php');

		self::assertSame(37, $report->getErrorCount());

		self::assertSniffError(
			$report,
			11,
			InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT,
			'Invalid inline documentation comment format "@var $a string[]", expected "@var string[] $a".'
		);
		self::assertSniffError(
			$report,
			17,
			InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT,
			'Invalid inline documentation comment format "@var $c", expected "@var type $variable".'
		);

		self::assertSniffError(
			$report,
			20,
			InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT,
			'Invalid inline documentation comment format "@var $d iterable|array|\Traversable Lorem ipsum", expected "@var iterable|array|\Traversable $d Lorem ipsum".'
		);

		self::assertSniffError(
			$report,
			23,
			InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT,
			'Invalid inline documentation comment format "@var $f string", expected "@var string $f".'
		);

		self::assertSniffError($report, 33, InlineDocCommentDeclarationSniff::CODE_INVALID_COMMENT_TYPE);
		self::assertSniffError($report, 33, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);

		self::assertSniffError($report, 36, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);

		self::assertSniffError($report, 39, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);
		self::assertSniffError($report, 42, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);
		self::assertSniffError($report, 46, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);

		self::assertSniffError($report, 64, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);
		self::assertSniffError($report, 67, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);
		self::assertSniffError($report, 70, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);
		self::assertSniffError($report, 73, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);
		self::assertSniffError($report, 76, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);
		self::assertSniffError($report, 79, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);
		self::assertSniffError($report, 82, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);
		self::assertSniffError($report, 85, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);
		self::assertSniffError($report, 128, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);
		self::assertSniffError($report, 149, InlineDocCommentDeclarationSniff::CODE_INVALID_FORMAT);

		self::assertSniffError(
			$report,
			91,
			InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE,
			'Missing variable $unknown before or after the documentation comment.'
		);

		self::assertSniffError(
			$report,
			93,
			InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE,
			'Missing variable $unknownA before or after the documentation comment.'
		);
		self::assertSniffError(
			$report,
			96,
			InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE,
			'Missing variable $unknownB before or after the documentation comment.'
		);
		self::assertSniffError(
			$report,
			99,
			InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE,
			'Missing variable $unknownC before or after the documentation comment.'
		);
		self::assertSniffError(
			$report,
			102,
			InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE,
			'Missing variable $unknownD before or after the documentation comment.'
		);
		self::assertSniffError(
			$report,
			106,
			InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE,
			'Missing variable $unknownE before or after the documentation comment.'
		);

		self::assertSniffError(
			$report,
			112,
			InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE,
			'Missing variable $unknownAA before or after the documentation comment.'
		);
		self::assertSniffError(
			$report,
			115,
			InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE,
			'Missing variable $unknownBB before or after the documentation comment.'
		);
		self::assertSniffError(
			$report,
			118,
			InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE,
			'Missing variable $unknownCC before or after the documentation comment.'
		);
		self::assertSniffError(
			$report,
			121,
			InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE,
			'Missing variable $unknownDD before or after the documentation comment.'
		);
		self::assertSniffError(
			$report,
			125,
			InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE,
			'Missing variable $unknownEE before or after the documentation comment.'
		);

		self::assertSniffError(
			$report,
			132,
			InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE,
			'Missing variable $unknownX before or after the documentation comment.'
		);

		self::assertSniffError(
			$report,
			134,
			InlineDocCommentDeclarationSniff::CODE_NO_ASSIGNMENT,
			'No assignment to $noAssignmentX variable before or after the documentation comment.'
		);
		self::assertSniffError(
			$report,
			137,
			InlineDocCommentDeclarationSniff::CODE_NO_ASSIGNMENT,
			'No assignment to $noAssignmentY variable before or after the documentation comment.'
		);
		self::assertSniffError(
			$report,
			140,
			InlineDocCommentDeclarationSniff::CODE_NO_ASSIGNMENT,
			'No assignment to $noAssignmentZ variable before or after the documentation comment.'
		);

		self::assertSniffError(
			$report,
			145,
			InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE,
			'Missing variable $unknownParameter before or after the documentation comment.'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNoErrorsWithDocCommentAboveReturnAllowed()
	{
		$report = self::checkFile(__DIR__ . '/data/inlineDocDocommentDeclarationWithDocCommentAboveReturnAllowedNoErrors.php', [
			'allowDocCommentAboveReturn' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrorsWithDocCommentAboveReturnAllowed()
	{
		$report = self::checkFile(__DIR__ . '/data/inlineDocDocommentDeclarationWithDocCommentAboveReturnAllowedErrors.php', [
			'allowDocCommentAboveReturn' => true,
		]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 4, InlineDocCommentDeclarationSniff::CODE_INVALID_COMMENT_TYPE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNoErrorsWithDocCommentAboveNonAssigmentAllowed()
	{
		$report = self::checkFile(__DIR__ . '/data/inlineDocDocommentDeclarationWithDocCommentAboveNonAssigmentAllowedNoErrors.php', [
			'allowAboveNonAssignment' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrorsWithDocCommentAboveNonAssigmentAllowed()
	{
		$report = self::checkFile(__DIR__ . '/data/inlineDocDocommentDeclarationWithDocCommentAboveNonAssigmentAllowedErrors.php', [
			'allowAboveNonAssignment' => true,
		]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 6, InlineDocCommentDeclarationSniff::CODE_MISSING_VARIABLE);
	}

}
