<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use SlevomatCodingStandard\Sniffs\TestCase;

class FullyQualifiedGlobalFunctionsSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/fullyQualifiedGlobalFunctionsNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFileWithoutNamespaceNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/fullyQualifiedGlobalFunctionsFileWithoutNamespaceNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/fullyQualifiedGlobalFunctionsErrors.php');

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError(
			$report,
			17,
			FullyQualifiedGlobalFunctionsSniff::CODE_NON_FULLY_QUALIFIED,
			'Function min() should be referenced via a fully qualified name.'
		);
		self::assertSniffError(
			$report,
			31,
			FullyQualifiedGlobalFunctionsSniff::CODE_NON_FULLY_QUALIFIED,
			'Function MaX() should be referenced via a fully qualified name.'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testExcludeErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/fullyQualifiedGlobalFunctionsIncludeExcludeErrors.php', [
			'exclude' => ['min'],
		]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError(
			$report,
			28,
			FullyQualifiedGlobalFunctionsSniff::CODE_NON_FULLY_QUALIFIED,
			'Function max() should be referenced via a fully qualified name.'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testIncludeErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/fullyQualifiedGlobalFunctionsIncludeExcludeErrors.php', [
			'include' => ['max'],
		]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError(
			$report,
			28,
			FullyQualifiedGlobalFunctionsSniff::CODE_NON_FULLY_QUALIFIED,
			'Function max() should be referenced via a fully qualified name.'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testIncludeSpecialNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/fullyQualifiedGlobalFunctionsIncludeSpecialNoErrors.php', [
			'includeSpecialFunctions' => true,
			'include' => ['max'],
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testIncludeSpecialErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/fullyQualifiedGlobalFunctionsIncludeSpecialErrors.php', [
			'includeSpecialFunctions' => true,
			'include' => ['max'],
		]);

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError(
			$report,
			5,
			FullyQualifiedGlobalFunctionsSniff::CODE_NON_FULLY_QUALIFIED,
			'Function array_key_exists() should be referenced via a fully qualified name.'
		);
		self::assertSniffError(
			$report,
			9,
			FullyQualifiedGlobalFunctionsSniff::CODE_NON_FULLY_QUALIFIED,
			'Function max() should be referenced via a fully qualified name.'
		);

		self::assertAllFixedInFile($report);
	}

}
