<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class DisallowMultiConstantDefinitionSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowMultiConstantDefinitionNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowMultiConstantDefinitionErrors.php');

		self::assertSame(6, $report->getErrorCount());

		self::assertSniffError($report, 6, DisallowMultiConstantDefinitionSniff::CODE_DISALLOWED_MULTI_CONSTANT_DEFINITION);
		self::assertSniffError($report, 8, DisallowMultiConstantDefinitionSniff::CODE_DISALLOWED_MULTI_CONSTANT_DEFINITION);
		self::assertSniffError($report, 11, DisallowMultiConstantDefinitionSniff::CODE_DISALLOWED_MULTI_CONSTANT_DEFINITION);
		self::assertSniffError($report, 13, DisallowMultiConstantDefinitionSniff::CODE_DISALLOWED_MULTI_CONSTANT_DEFINITION);
		self::assertSniffError($report, 24, DisallowMultiConstantDefinitionSniff::CODE_DISALLOWED_MULTI_CONSTANT_DEFINITION);
		self::assertSniffError($report, 26, DisallowMultiConstantDefinitionSniff::CODE_DISALLOWED_MULTI_CONSTANT_DEFINITION);

		self::assertAllFixedInFile($report);
	}

}
