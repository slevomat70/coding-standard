<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class ModernClassNameReferenceSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/modernClassNameReferenceNoErrors.php', [
			'enableOnObjects' => false,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/modernClassNameReferenceErrors.php', [
			'enableOnObjects' => false,
		]);

		self::assertSame(11, $report->getErrorCount());

		self::assertSniffError($report, 12, ModernClassNameReferenceSniff::CODE_CLASS_NAME_REFERENCED_VIA_MAGIC_CONSTANT);
		self::assertSniffError(
			$report,
			17,
			ModernClassNameReferenceSniff::CODE_CLASS_NAME_REFERENCED_VIA_FUNCTION_CALL,
			'Class name referenced via call of function get_class().'
		);
		self::assertSniffError(
			$report,
			22,
			ModernClassNameReferenceSniff::CODE_CLASS_NAME_REFERENCED_VIA_FUNCTION_CALL,
			'Class name referenced via call of function get_class().'
		);
		self::assertSniffError(
			$report,
			27,
			ModernClassNameReferenceSniff::CODE_CLASS_NAME_REFERENCED_VIA_FUNCTION_CALL,
			'Class name referenced via call of function get_parent_class().'
		);
		self::assertSniffError(
			$report,
			32,
			ModernClassNameReferenceSniff::CODE_CLASS_NAME_REFERENCED_VIA_FUNCTION_CALL,
			'Class name referenced via call of function get_parent_class().'
		);
		self::assertSniffError(
			$report,
			37,
			ModernClassNameReferenceSniff::CODE_CLASS_NAME_REFERENCED_VIA_FUNCTION_CALL,
			'Class name referenced via call of function get_called_class().'
		);
		self::assertSniffError(
			$report,
			42,
			ModernClassNameReferenceSniff::CODE_CLASS_NAME_REFERENCED_VIA_FUNCTION_CALL,
			'Class name referenced via call of function get_class().'
		);
		self::assertSniffError(
			$report,
			47,
			ModernClassNameReferenceSniff::CODE_CLASS_NAME_REFERENCED_VIA_FUNCTION_CALL,
			'Class name referenced via call of function get_class().'
		);
		self::assertSniffError(
			$report,
			52,
			ModernClassNameReferenceSniff::CODE_CLASS_NAME_REFERENCED_VIA_FUNCTION_CALL,
			'Class name referenced via call of function get_parent_class().'
		);
		self::assertSniffError(
			$report,
			57,
			ModernClassNameReferenceSniff::CODE_CLASS_NAME_REFERENCED_VIA_FUNCTION_CALL,
			'Class name referenced via call of function get_parent_class().'
		);
		self::assertSniffError(
			$report,
			62,
			ModernClassNameReferenceSniff::CODE_CLASS_NAME_REFERENCED_VIA_FUNCTION_CALL,
			'Class name referenced via call of function get_called_class().'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testEnabledOnObjectsErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/modernClassNameReferenceEnabledOnObjectsErrors.php', [
			'enableOnObjects' => true,
		]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 9, ModernClassNameReferenceSniff::CODE_CLASS_NAME_REFERENCED_VIA_FUNCTION_CALL);

		self::assertAllFixedInFile($report);
	}

}
