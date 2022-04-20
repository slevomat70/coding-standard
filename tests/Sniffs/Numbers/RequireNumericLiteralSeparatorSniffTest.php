<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Numbers;

use SlevomatCodingStandard\Sniffs\TestCase;

class RequireNumericLiteralSeparatorSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireNumericLiteralSeparatorNoErrors.php', [
			'enable' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireNumericLiteralSeparatorErrors.php', [
			'enable' => true,
		]);

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError($report, 3, RequireNumericLiteralSeparatorSniff::CODE_REQUIRED_NUMERIC_LITERAL_SEPARATOR);
		self::assertSniffError($report, 4, RequireNumericLiteralSeparatorSniff::CODE_REQUIRED_NUMERIC_LITERAL_SEPARATOR);
		self::assertSniffError($report, 5, RequireNumericLiteralSeparatorSniff::CODE_REQUIRED_NUMERIC_LITERAL_SEPARATOR);
		self::assertSniffError($report, 6, RequireNumericLiteralSeparatorSniff::CODE_REQUIRED_NUMERIC_LITERAL_SEPARATOR);
	}

	/**
	 * @return void
	 */
	public function testModifiedSettingsNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireNumericLiteralSeparatorModifiedSettingsNoErrors.php', [
			'enable' => true,
			'minDigitsBeforeDecimalPoint' => 7,
			'minDigitsAfterDecimalPoint' => 6,
			'ignoreOctalNumbers' => false,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testModifiedSettingsErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireNumericLiteralSeparatorModifiedSettingsErrors.php', [
			'enable' => true,
			'minDigitsBeforeDecimalPoint' => 7,
			'minDigitsAfterDecimalPoint' => 6,
			'ignoreOctalNumbers' => false,
		]);

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 3, RequireNumericLiteralSeparatorSniff::CODE_REQUIRED_NUMERIC_LITERAL_SEPARATOR);
		self::assertSniffError($report, 4, RequireNumericLiteralSeparatorSniff::CODE_REQUIRED_NUMERIC_LITERAL_SEPARATOR);
		self::assertSniffError($report, 5, RequireNumericLiteralSeparatorSniff::CODE_REQUIRED_NUMERIC_LITERAL_SEPARATOR);
	}

	/**
	 * @return void
	 */
	public function testShouldNotReportIfSniffIsDisabled()
	{
		$report = self::checkFile(__DIR__ . '/data/requireNumericLiteralSeparatorErrors.php', [
			'enable' => false,
		]);

		self::assertNoSniffErrorInFile($report);
	}

}
