<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use SlevomatCodingStandard\Sniffs\TestCase;

class RequireArrowFunctionSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testDisallowNestedNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireArrowFunctionDisallowNestedNoErrors.php', [
			'allowNested' => false,
			'enable' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDisallowNestedErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireArrowFunctionDisallowNestedErrors.php', [
			'allowNested' => false,
			'enable' => true,
		]);

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError($report, 3, RequireArrowFunctionSniff::CODE_REQUIRED_ARROW_FUNCTION);
		self::assertSniffError($report, 7, RequireArrowFunctionSniff::CODE_REQUIRED_ARROW_FUNCTION);
		self::assertSniffError($report, 12, RequireArrowFunctionSniff::CODE_REQUIRED_ARROW_FUNCTION);
		self::assertSniffError($report, 18, RequireArrowFunctionSniff::CODE_REQUIRED_ARROW_FUNCTION);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAllowNestedNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireArrowFunctionAllowNestedNoErrors.php', [
			'allowNested' => true,
			'enable' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAllowNestedErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireArrowFunctionAllowNestedErrors.php', [
			'allowNested' => true,
			'enable' => true,
		]);

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 3, RequireArrowFunctionSniff::CODE_REQUIRED_ARROW_FUNCTION);
		self::assertSniffError($report, 4, RequireArrowFunctionSniff::CODE_REQUIRED_ARROW_FUNCTION);
		self::assertSniffError($report, 4, RequireArrowFunctionSniff::CODE_REQUIRED_ARROW_FUNCTION);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testShouldNotReportIfSniffIsDisabled()
	{
		$report = self::checkFile(__DIR__ . '/data/requireArrowFunctionAllowNestedErrors.php', [
			'allowNested' => true,
			'enable' => false,
		]);

		self::assertNoSniffErrorInFile($report);
	}

}
