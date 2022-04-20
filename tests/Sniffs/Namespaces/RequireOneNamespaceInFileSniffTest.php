<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use SlevomatCodingStandard\Sniffs\TestCase;

class RequireOneNamespaceInFileSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireOneNamespaceInFileNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireOneNamespaceInFileErrors.php');

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError($report, 10, RequireOneNamespaceInFileSniff::CODE_MORE_NAMESPACES_IN_FILE);
		self::assertSniffError($report, 18, RequireOneNamespaceInFileSniff::CODE_MORE_NAMESPACES_IN_FILE);
	}

	/**
	 * @return void
	 */
	public function testNoNamespaceNoError()
	{
		$report = self::checkFile(__DIR__ . '/data/requireOneNamespaceInFileNoNamespaceNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

}
