<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class RequireConstructorPropertyPromotionSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireConstructorPropertyPromotionNoErrors.php', [
			'enable' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireConstructorPropertyPromotionErrors.php', [
			'enable' => true,
		]);

		self::assertSame(5, $report->getErrorCount());

		self::assertSniffError(
			$report,
			6,
			RequireConstructorPropertyPromotionSniff::CODE_REQUIRED_CONSTRUCTOR_PROPERTY_PROMOTION,
			'Required promotion of property $a.'
		);
		self::assertSniffError(
			$report,
			11,
			RequireConstructorPropertyPromotionSniff::CODE_REQUIRED_CONSTRUCTOR_PROPERTY_PROMOTION,
			'Required promotion of property $b.'
		);
		self::assertSniffError(
			$report,
			13,
			RequireConstructorPropertyPromotionSniff::CODE_REQUIRED_CONSTRUCTOR_PROPERTY_PROMOTION,
			'Required promotion of property $c.'
		);
		self::assertSniffError(
			$report,
			18,
			RequireConstructorPropertyPromotionSniff::CODE_REQUIRED_CONSTRUCTOR_PROPERTY_PROMOTION,
			'Required promotion of property $e.'
		);
		self::assertSniffError(
			$report,
			36,
			RequireConstructorPropertyPromotionSniff::CODE_REQUIRED_CONSTRUCTOR_PROPERTY_PROMOTION,
			'Required promotion of property $from.'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testShouldNotReportIfSniffIsDisabled()
	{
		$report = self::checkFile(__DIR__ . '/data/requireConstructorPropertyPromotionErrors.php', [
			'enable' => false,
		]);

		self::assertNoSniffErrorInFile($report);
	}

}
