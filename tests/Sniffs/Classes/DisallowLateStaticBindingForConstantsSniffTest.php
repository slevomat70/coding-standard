<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class DisallowLateStaticBindingForConstantsSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowLateStaticBindingForConstantsNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowLateStaticBindingForConstantsErrors.php');

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError($report, 13, DisallowLateStaticBindingForConstantsSniff::CODE_DISALLOWED_LATE_STATIC_BINDING_FOR_CONSTANT);
		self::assertSniffError($report, 13, DisallowLateStaticBindingForConstantsSniff::CODE_DISALLOWED_LATE_STATIC_BINDING_FOR_CONSTANT);

		self::assertAllFixedInFile($report);
	}

}
