<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\TypeHints;

use SlevomatCodingStandard\Sniffs\TestCase;

class PropertyTypeHintSpacingSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/propertyTypeHintSpacingNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/propertyTypeHintSpacingErrors.php');

		self::assertSame(9, $report->getErrorCount());

		self::assertSniffError($report, 6, PropertyTypeHintSpacingSniff::CODE_NO_SPACE_BETWEEN_TYPE_HINT_AND_PROPERTY);
		self::assertSniffError($report, 8, PropertyTypeHintSpacingSniff::CODE_MULTIPLE_SPACES_BETWEEN_TYPE_HINT_AND_PROPERTY);
		self::assertSniffError($report, 10, PropertyTypeHintSpacingSniff::CODE_WHITESPACE_AFTER_NULLABILITY_SYMBOL);
		self::assertSniffError($report, 12, PropertyTypeHintSpacingSniff::CODE_NO_SPACE_BEFORE_NULLABILITY_SYMBOL);
		self::assertSniffError($report, 14, PropertyTypeHintSpacingSniff::CODE_MULTIPLE_SPACES_BEFORE_NULLABILITY_SYMBOL);
		self::assertSniffError($report, 16, PropertyTypeHintSpacingSniff::CODE_MULTIPLE_SPACES_BEFORE_TYPE_HINT);
		self::assertSniffError($report, 18, PropertyTypeHintSpacingSniff::CODE_MULTIPLE_SPACES_BEFORE_TYPE_HINT);
		self::assertSniffError($report, 20, PropertyTypeHintSpacingSniff::CODE_MULTIPLE_SPACES_BETWEEN_TYPE_HINT_AND_PROPERTY);
		self::assertSniffError($report, 22, PropertyTypeHintSpacingSniff::CODE_MULTIPLE_SPACES_BEFORE_TYPE_HINT);

		self::assertAllFixedInFile($report);
	}

}
