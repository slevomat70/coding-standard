<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class DisallowConstructorPropertyPromotionSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowConstructorPropertyPromotionNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowConstructorPropertyPromotionErrors.php');

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError(
			$report,
			6,
			DisallowConstructorPropertyPromotionSniff::CODE_DISALLOWED_CONSTRUCTOR_PROPERTY_PROMOTION,
			'Constructor property promotion is disallowed, promotion of property $a found.'
		);
		self::assertSniffError(
			$report,
			6,
			DisallowConstructorPropertyPromotionSniff::CODE_DISALLOWED_CONSTRUCTOR_PROPERTY_PROMOTION,
			'Constructor property promotion is disallowed, promotion of property $b found.'
		);
		self::assertSniffError(
			$report,
			17,
			DisallowConstructorPropertyPromotionSniff::CODE_DISALLOWED_CONSTRUCTOR_PROPERTY_PROMOTION,
			'Constructor property promotion is disallowed, promotion of property $a found.'
		);
		self::assertSniffError(
			$report,
			18,
			DisallowConstructorPropertyPromotionSniff::CODE_DISALLOWED_CONSTRUCTOR_PROPERTY_PROMOTION,
			'Constructor property promotion is disallowed, promotion of property $b found.'
		);
	}

}
