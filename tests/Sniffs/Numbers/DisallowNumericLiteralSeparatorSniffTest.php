<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Numbers;

use SlevomatCodingStandard\Sniffs\TestCase;

class DisallowNumericLiteralSeparatorSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowNumericLiteralSeparatorNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowNumericLiteralSeparatorErrors.php');

		self::assertSame(7, $report->getErrorCount());

		self::assertSniffError($report, 3, DisallowNumericLiteralSeparatorSniff::CODE_DISALLOWED_NUMERIC_LITERAL_SEPARATOR);
		self::assertSniffError($report, 4, DisallowNumericLiteralSeparatorSniff::CODE_DISALLOWED_NUMERIC_LITERAL_SEPARATOR);
		self::assertSniffError($report, 5, DisallowNumericLiteralSeparatorSniff::CODE_DISALLOWED_NUMERIC_LITERAL_SEPARATOR);
		self::assertSniffError($report, 6, DisallowNumericLiteralSeparatorSniff::CODE_DISALLOWED_NUMERIC_LITERAL_SEPARATOR);
		self::assertSniffError($report, 7, DisallowNumericLiteralSeparatorSniff::CODE_DISALLOWED_NUMERIC_LITERAL_SEPARATOR);
		self::assertSniffError($report, 8, DisallowNumericLiteralSeparatorSniff::CODE_DISALLOWED_NUMERIC_LITERAL_SEPARATOR);
		self::assertSniffError($report, 9, DisallowNumericLiteralSeparatorSniff::CODE_DISALLOWED_NUMERIC_LITERAL_SEPARATOR);

		self::assertAllFixedInFile($report);
	}

}
