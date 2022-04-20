<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use SlevomatCodingStandard\Sniffs\TestCase;

class DisallowTrailingCommaInDeclarationSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowTrailingCommaInDeclarationNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowTrailingCommaInDeclarationErrors.php');

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError($report, 5, DisallowTrailingCommaInDeclarationSniff::CODE_DISALLOWED_TRAILING_COMMA);
		self::assertSniffError($report, 14, DisallowTrailingCommaInDeclarationSniff::CODE_DISALLOWED_TRAILING_COMMA);
		self::assertSniffError($report, 24, DisallowTrailingCommaInDeclarationSniff::CODE_DISALLOWED_TRAILING_COMMA);
		self::assertSniffError($report, 28, DisallowTrailingCommaInDeclarationSniff::CODE_DISALLOWED_TRAILING_COMMA);

		self::assertAllFixedInFile($report);
	}

}
