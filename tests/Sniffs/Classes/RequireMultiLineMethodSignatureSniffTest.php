<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;
use Throwable;

final class RequireMultiLineMethodSignatureSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testThrowExceptionForInvalidPattern()
	{
		$this->expectException(Throwable::class);

		self::checkFile(
			__DIR__ . '/data/requireMultiLineMethodSignatureNoErrors.php',
			['includedMethodPatterns' => ['invalidPattern']]
		);

		self::checkFile(
			__DIR__ . '/data/requireMultiLineMethodSignatureNoErrors.php',
			['excludedMethodPatterns' => ['invalidPattern']]
		);
	}

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineMethodSignatureNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineMethodSignatureErrors.php');
		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError($report, 5, RequireMultiLineMethodSignatureSniff::CODE_REQUIRED_MULTI_LINE_SIGNATURE);
		self::assertSniffError($report, 8, RequireMultiLineMethodSignatureSniff::CODE_REQUIRED_MULTI_LINE_SIGNATURE);
		self::assertSniffError($report, 14, RequireMultiLineMethodSignatureSniff::CODE_REQUIRED_MULTI_LINE_SIGNATURE);
		self::assertSniffError($report, 16, RequireMultiLineMethodSignatureSniff::CODE_REQUIRED_MULTI_LINE_SIGNATURE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testForAllMethods()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineMethodSignatureAllMethodsErrors.php', ['minLineLength' => 0]);
		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 5, RequireMultiLineMethodSignatureSniff::CODE_REQUIRED_MULTI_LINE_SIGNATURE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testIncludedMethodPatterns()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineMethodSignatureIncludedMethodsErrors.php', [
			'maxLineLength' => 0,
			'includedMethodPatterns' => ['/__construct/'],
		], [RequireMultiLineMethodSignatureSniff::CODE_REQUIRED_MULTI_LINE_SIGNATURE]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 5, RequireMultiLineMethodSignatureSniff::CODE_REQUIRED_MULTI_LINE_SIGNATURE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testExcludedMethodPatterns()
	{
		$report = self::checkFile(__DIR__ . '/data/requireMultiLineMethodSignatureExcludedMethodsErrors.php', [
			'maxLineLength' => 0,
			'excludedMethodPatterns' => ['/__construct/'],
		], [RequireMultiLineMethodSignatureSniff::CODE_REQUIRED_MULTI_LINE_SIGNATURE]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 7, RequireMultiLineMethodSignatureSniff::CODE_REQUIRED_MULTI_LINE_SIGNATURE);

		self::assertAllFixedInFile($report);
	}

}
