<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Commenting;

use SlevomatCodingStandard\Sniffs\TestCase;

class DeprecatedAnnotationDeclarationSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/deprecatedAnnotationDeclarationNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/deprecatedAnnotationDeclarationErrors.php');

		self::assertSame(4, $report->getErrorCount());

		foreach ([6, 12, 17, 25] as $line) {
			self::assertSniffError($report, $line, DeprecatedAnnotationDeclarationSniff::MISSING_DESCRIPTION);
		}
	}

}
