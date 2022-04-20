<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use SlevomatCodingStandard\Sniffs\TestCase;

class AlphabeticallySortedUsesSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testIncorrectOrder()
	{
		self::assertSniffError(
			self::checkFile(__DIR__ . '/data/incorrectOrder.php'),
			5,
			AlphabeticallySortedUsesSniff::CODE_INCORRECT_ORDER,
			'Second\FooObject'
		);
	}

	/**
	 * @return void
	 */
	public function testIncorrectOrderIntertwinedWithClasses()
	{
		self::assertSniffError(
			self::checkFile(__DIR__ . '/data/incorrectOrderIntertwinedWithClasses.php'),
			18,
			AlphabeticallySortedUsesSniff::CODE_INCORRECT_ORDER,
			'Delta'
		);
	}

	/**
	 * @return void
	 */
	public function testCorrectOrderIgnoresUsesAfterClassesTraitsAndInterfaces()
	{
		self::assertNoSniffErrorInFile(
			self::checkFile(__DIR__ . '/data/correctOrder.php')
		);
	}

	/**
	 * @return void
	 */
	public function testCorrectOrderOfSimilarNamespaces()
	{
		self::assertNoSniffErrorInFile(
			self::checkFile(__DIR__ . '/data/correctOrderSimilarNamespaces.php')
		);
	}

	/**
	 * @return void
	 */
	public function testCorrectOrderOfSimilarNamespacesCaseSensitive()
	{
		self::assertNoSniffErrorInFile(
			self::checkFile(__DIR__ . '/data/correctOrderSimilarNamespacesCaseSensitive.php', [
				'caseSensitive' => true,
			])
		);
	}

	/**
	 * @return void
	 */
	public function testIncorrectOrderOfSimilarNamespaces()
	{
		self::assertSniffError(
			self::checkFile(__DIR__ . '/data/incorrectOrderSimilarNamespaces.php'),
			6,
			AlphabeticallySortedUsesSniff::CODE_INCORRECT_ORDER,
			'Foo\Bar'
		);
	}

	/**
	 * @return void
	 */
	public function testPatrikOrder()
	{
		$report = self::checkFile(__DIR__ . '/data/alphabeticalPatrik.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixable()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fixableAlphabeticalSortedUses.php',
			[],
			[AlphabeticallySortedUsesSniff::CODE_INCORRECT_ORDER]
		);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableWithCommentBeforeFirst()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fixableAlphabeticalSortedUsesWithCommentBeforeFirst.php',
			[],
			[AlphabeticallySortedUsesSniff::CODE_INCORRECT_ORDER]
		);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableNotPsr12Compatible()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableAlphabeticalSortedUsesNotPsr12Compatible.php', [
			'psr12Compatible' => false,
		], [AlphabeticallySortedUsesSniff::CODE_INCORRECT_ORDER]);
		self::assertAllFixedInFile($report);
	}

}
