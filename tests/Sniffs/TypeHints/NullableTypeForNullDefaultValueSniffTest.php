<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\TypeHints;

use SlevomatCodingStandard\Sniffs\TestCase;

class NullableTypeForNullDefaultValueSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/nullableTypeForNullDefaultValueNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/nullableTypeForNullDefaultValueErrors.php');

		self::assertSame(15, $report->getErrorCount());

		$code = NullableTypeForNullDefaultValueSniff::CODE_NULLABILITY_TYPE_MISSING;
		self::assertSniffError($report, 3, $code);
		self::assertSniffError($report, 8, $code);
		self::assertSniffError($report, 12, $code);
		self::assertSniffError($report, 17, $code);
		self::assertSniffError($report, 24, $code);
		self::assertSniffError($report, 29, $code);
		self::assertSniffError($report, 34, $code);
		self::assertSniffError($report, 39, $code);
		self::assertSniffError($report, 44, $code);
		self::assertSniffError($report, 49, $code);
		self::assertSniffError($report, 54, $code);
		self::assertSniffError($report, 59, $code);
		self::assertSniffError($report, 64, $code);
		self::assertSniffError($report, 69, $code);
		self::assertSniffError($report, 73, $code);
	}

	/**
	 * @return void
	 */
	public function testFixable()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fixableNullableTypeForNullDefaultValue.php',
			[],
			[NullableTypeForNullDefaultValueSniff::CODE_NULLABILITY_TYPE_MISSING]
		);
		self::assertAllFixedInFile($report);
	}

}
