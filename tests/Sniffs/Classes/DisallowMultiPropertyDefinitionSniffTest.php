<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class DisallowMultiPropertyDefinitionSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowMultiPropertyDefinitionNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowMultiPropertyDefinitionErrors.php');

		self::assertSame(6, $report->getErrorCount());

		self::assertSniffError($report, 6, DisallowMultiPropertyDefinitionSniff::CODE_DISALLOWED_MULTI_PROPERTY_DEFINITION);
		self::assertSniffError($report, 8, DisallowMultiPropertyDefinitionSniff::CODE_DISALLOWED_MULTI_PROPERTY_DEFINITION);
		self::assertSniffError($report, 11, DisallowMultiPropertyDefinitionSniff::CODE_DISALLOWED_MULTI_PROPERTY_DEFINITION);
		self::assertSniffError($report, 13, DisallowMultiPropertyDefinitionSniff::CODE_DISALLOWED_MULTI_PROPERTY_DEFINITION);
		self::assertSniffError($report, 24, DisallowMultiPropertyDefinitionSniff::CODE_DISALLOWED_MULTI_PROPERTY_DEFINITION);
		self::assertSniffError($report, 26, DisallowMultiPropertyDefinitionSniff::CODE_DISALLOWED_MULTI_PROPERTY_DEFINITION);

		self::assertAllFixedInFile($report);
	}

}
