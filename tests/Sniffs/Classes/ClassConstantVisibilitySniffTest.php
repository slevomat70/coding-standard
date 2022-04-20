<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class ClassConstantVisibilitySniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/classWithConstants.php');

		self::assertSame(3, $report->getErrorCount());

		self::assertNoSniffError($report, 7);
		self::assertNoSniffError($report, 9);
		self::assertNoSniffError($report, 10);

		self::assertSniffError(
			$report,
			6,
			ClassConstantVisibilitySniff::CODE_MISSING_CONSTANT_VISIBILITY,
			'Constant \ClassWithConstants::PUBLIC_FOO visibility missing.'
		);

		self::assertSniffError(
			$report,
			23,
			ClassConstantVisibilitySniff::CODE_MISSING_CONSTANT_VISIBILITY,
			'Constant class@anonymous::PUBLIC_FOO visibility missing.'
		);

		self::assertSniffError(
			$report,
			25,
			ClassConstantVisibilitySniff::CODE_MISSING_CONSTANT_VISIBILITY,
			'Constant class@anonymous::FINAL_WITHOUT_VISIBILITY visibility missing.'
		);

		self::assertNoSniffError($report, 26);
		self::assertNoSniffError($report, 27);
	}

	/**
	 * @return void
	 */
	public function testNoClassConstants()
	{
		$report = self::checkFile(__DIR__ . '/data/noClassConstants.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNoClassConstantsWithNamespace()
	{
		$report = self::checkFile(__DIR__ . '/data/noClassConstantsWithNamespace.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableEnabled()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fixableMissingClassConstantVisibility.php',
			['fixable' => true],
			[ClassConstantVisibilitySniff::CODE_MISSING_CONSTANT_VISIBILITY]
		);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableDisabled()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fixableMissingClassConstantVisibilityFixableDisabled.php',
			['fixable' => false],
			[ClassConstantVisibilitySniff::CODE_MISSING_CONSTANT_VISIBILITY]
		);
		self::assertAllFixedInFile($report);
	}

}
