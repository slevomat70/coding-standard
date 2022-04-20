<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\TypeHints;

use SlevomatCodingStandard\Sniffs\TestCase;

class UnionTypeHintFormatSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testWhitespaceNotSetNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatWhitespaceNotSetNoErrors.php', [
			'enable' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWhitespaceDisallowedNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatWhitespaceDisallowedNoErrors.php', [
			'enable' => true,
			'withSpaces' => 'no',
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWhitespaceDisallowedErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatWhitespaceDisallowedErrors.php', [
			'enable' => true,
			'withSpaces' => 'no',
		]);

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 6, UnionTypeHintFormatSniff::CODE_DISALLOWED_WHITESPACE);
		self::assertSniffError(
			$report,
			8,
			UnionTypeHintFormatSniff::CODE_DISALLOWED_WHITESPACE,
			'Spaces in type hint "int| false" are disallowed.'
		);
		self::assertSniffError(
			$report,
			8,
			UnionTypeHintFormatSniff::CODE_DISALLOWED_WHITESPACE,
			'Spaces in type hint "int  | string |  bool" are disallowed.'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWhitespaceEnabledNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatWhitespaceEnabledNoErrors.php', [
			'enable' => true,
			'withSpaces' => 'yes',
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWhitespaceEnabledErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatWhitespaceEnabledErrors.php', [
			'enable' => true,
			'withSpaces' => 'yes',
		]);

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError(
			$report,
			6,
			UnionTypeHintFormatSniff::CODE_REQUIRED_WHITESPACE,
			'One space required before and after each "|" in type hint "int|string".'
		);
		self::assertSniffError(
			$report,
			8,
			UnionTypeHintFormatSniff::CODE_REQUIRED_WHITESPACE,
			'One space required before and after each "|" in type hint "int| false".'
		);
		self::assertSniffError(
			$report,
			8,
			UnionTypeHintFormatSniff::CODE_REQUIRED_WHITESPACE,
			'One space required before and after each "|" in type hint "string |false".'
		);
		self::assertSniffError(
			$report,
			8,
			UnionTypeHintFormatSniff::CODE_REQUIRED_WHITESPACE,
			'One space required before and after each "|" in type hint "int  |    string |bool".'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testShortNullableNotSetNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatShortNullableNotSetNoErrors.php', [
			'enable' => true,
		], [UnionTypeHintFormatSniff::CODE_REQUIRED_SHORT_NULLABLE, UnionTypeHintFormatSniff::CODE_DISALLOWED_SHORT_NULLABLE]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testShortNullableRequiredNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatShortNullableRequiredNoErrors.php', [
			'enable' => true,
			'shortNullable' => 'yes',
		], [UnionTypeHintFormatSniff::CODE_REQUIRED_SHORT_NULLABLE]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testShortNullableRequiredErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatShortNullableRequiredErrors.php', [
			'enable' => true,
			'shortNullable' => 'yes',
		], [UnionTypeHintFormatSniff::CODE_REQUIRED_SHORT_NULLABLE]);

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 6, UnionTypeHintFormatSniff::CODE_REQUIRED_SHORT_NULLABLE);
		self::assertSniffError(
			$report,
			8,
			UnionTypeHintFormatSniff::CODE_REQUIRED_SHORT_NULLABLE,
			'Short nullable type hint in "null|bool" is required.'
		);
		self::assertSniffError(
			$report,
			8,
			UnionTypeHintFormatSniff::CODE_REQUIRED_SHORT_NULLABLE,
			'Short nullable type hint in "string|null" is required.'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testShortNullableDisallowedNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatShortNullableDisallowedNoErrors.php', [
			'enable' => true,
			'shortNullable' => 'no',
		], [UnionTypeHintFormatSniff::CODE_DISALLOWED_SHORT_NULLABLE]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testShortNullableDisallowedErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatShortNullableDisallowedErrors.php', [
			'enable' => true,
			'shortNullable' => 'no',
		], [UnionTypeHintFormatSniff::CODE_DISALLOWED_SHORT_NULLABLE]);

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 6, UnionTypeHintFormatSniff::CODE_DISALLOWED_SHORT_NULLABLE);
		self::assertSniffError(
			$report,
			8,
			UnionTypeHintFormatSniff::CODE_DISALLOWED_SHORT_NULLABLE,
			'Usage of short nullable type hint in "?bool" is disallowed.'
		);
		self::assertSniffError(
			$report,
			8,
			UnionTypeHintFormatSniff::CODE_DISALLOWED_SHORT_NULLABLE,
			'Usage of short nullable type hint in "?string" is disallowed.'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNullPositionNotSetNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatNullPositionNotSetNoErrors.php', [
			'enable' => true,
		], [UnionTypeHintFormatSniff::CODE_NULL_TYPE_HINT_NOT_ON_FIRST_POSITION, UnionTypeHintFormatSniff::CODE_NULL_TYPE_HINT_NOT_ON_LAST_POSITION]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNullPositionFirstNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatNullPositionFirstNoErrors.php', [
			'enable' => true,
			'nullPosition' => 'first',
		], [UnionTypeHintFormatSniff::CODE_NULL_TYPE_HINT_NOT_ON_FIRST_POSITION]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNullPositionFirstErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatNullPositionFirstErrors.php', [
			'enable' => true,
			'nullPosition' => 'first',
		], [UnionTypeHintFormatSniff::CODE_NULL_TYPE_HINT_NOT_ON_FIRST_POSITION]);

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 6, UnionTypeHintFormatSniff::CODE_NULL_TYPE_HINT_NOT_ON_FIRST_POSITION);
		self::assertSniffError(
			$report,
			8,
			UnionTypeHintFormatSniff::CODE_NULL_TYPE_HINT_NOT_ON_FIRST_POSITION,
			'Null type hint should be on first position in "bool|null|int".'
		);
		self::assertSniffError(
			$report,
			8,
			UnionTypeHintFormatSniff::CODE_NULL_TYPE_HINT_NOT_ON_FIRST_POSITION,
			'Null type hint should be on first position in "string|null|\Anything".'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNullPositionLastNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatNullPositionLastNoErrors.php', [
			'enable' => true,
			'nullPosition' => 'last',
		], [UnionTypeHintFormatSniff::CODE_NULL_TYPE_HINT_NOT_ON_LAST_POSITION]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNullPositionLastErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatNullPositionLastErrors.php', [
			'enable' => true,
			'nullPosition' => 'last',
		], [UnionTypeHintFormatSniff::CODE_NULL_TYPE_HINT_NOT_ON_LAST_POSITION]);

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 6, UnionTypeHintFormatSniff::CODE_NULL_TYPE_HINT_NOT_ON_LAST_POSITION);
		self::assertSniffError(
			$report,
			8,
			UnionTypeHintFormatSniff::CODE_NULL_TYPE_HINT_NOT_ON_LAST_POSITION,
			'Null type hint should be on last position in "bool|null|int".'
		);
		self::assertSniffError(
			$report,
			8,
			UnionTypeHintFormatSniff::CODE_NULL_TYPE_HINT_NOT_ON_LAST_POSITION,
			'Null type hint should be on last position in "string|null|\Anything".'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testShouldNotReportIfSniffIsDisabled()
	{
		$report = self::checkFile(__DIR__ . '/data/unionTypeHintFormatShortNullableNotSetNoErrors.php', [
			'enable' => false,
		]);
		self::assertNoSniffErrorInFile($report);
	}

}
