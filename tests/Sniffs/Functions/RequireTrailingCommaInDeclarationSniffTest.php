<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use SlevomatCodingStandard\Sniffs\TestCase;

class RequireTrailingCommaInDeclarationSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireTrailingCommaInDeclarationNoErrors.php', [
			'enable' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireTrailingCommaInDeclarationErrors.php', [
			'enable' => true,
		]);

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError($report, 5, RequireTrailingCommaInDeclarationSniff::CODE_MISSING_TRAILING_COMMA);
		self::assertSniffError($report, 14, RequireTrailingCommaInDeclarationSniff::CODE_MISSING_TRAILING_COMMA);
		self::assertSniffError($report, 24, RequireTrailingCommaInDeclarationSniff::CODE_MISSING_TRAILING_COMMA);
		self::assertSniffError($report, 28, RequireTrailingCommaInDeclarationSniff::CODE_MISSING_TRAILING_COMMA);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testShouldNotReportIfSniffIsDisabled()
	{
		$report = self::checkFile(__DIR__ . '/data/requireTrailingCommaInDeclarationErrors.php', [
			'enable' => false,
		]);

		self::assertNoSniffErrorInFile($report);
	}

}
