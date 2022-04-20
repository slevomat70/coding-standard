<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class SuperfluousTraitNamingSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/superfluousTraitNamingNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/superfluousTraitNamingErrors.php');

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 3, SuperfluousTraitNamingSniff::CODE_SUPERFLUOUS_SUFFIX, 'Superfluous suffix "Trait".');
		self::assertSniffError($report, 8, SuperfluousTraitNamingSniff::CODE_SUPERFLUOUS_SUFFIX, 'Superfluous suffix "trait".');
		self::assertSniffError($report, 13, SuperfluousTraitNamingSniff::CODE_SUPERFLUOUS_SUFFIX, 'Superfluous suffix "TrAiT".');
	}

}
