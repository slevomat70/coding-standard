<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\PHP;

use SlevomatCodingStandard\Sniffs\TestCase;

class DisallowDirectMagicInvokeCallSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowDirectMagicInvokeCallNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowDirectMagicInvokeCallErrors.php');

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 3, DisallowDirectMagicInvokeCallSniff::CODE_DISALLOWED_DIRECT_MAGIC_INVOKE_CALL);
		self::assertSniffError($report, 7, DisallowDirectMagicInvokeCallSniff::CODE_DISALLOWED_DIRECT_MAGIC_INVOKE_CALL);
		self::assertSniffError($report, 9, DisallowDirectMagicInvokeCallSniff::CODE_DISALLOWED_DIRECT_MAGIC_INVOKE_CALL);

		self::assertAllFixedInFile($report);
	}

}
