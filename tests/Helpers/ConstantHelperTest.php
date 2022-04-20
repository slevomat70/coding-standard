<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

class ConstantHelperTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNameWithNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/constantWithNamespace.php');

		$constantPointer = $this->findConstantPointerByName($phpcsFile, 'FOO');
		self::assertSame('\FooNamespace\FOO', ConstantHelper::getFullyQualifiedName($phpcsFile, $constantPointer));
		self::assertSame('FOO', ConstantHelper::getName($phpcsFile, $constantPointer));
	}

	/**
	 * @return void
	 */
	public function testNameWithoutNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/constantWithoutNamespace.php');

		$constantPointer = $this->findConstantPointerByName($phpcsFile, 'FOO');
		self::assertSame('FOO', ConstantHelper::getFullyQualifiedName($phpcsFile, $constantPointer));
		self::assertSame('FOO', ConstantHelper::getName($phpcsFile, $constantPointer));
	}

	/**
	 * @return void
	 */
	public function testGetAllNames()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/constantNames.php');
		self::assertSame(['FOO', 'BOO'], ConstantHelper::getAllNames($phpcsFile));
	}

	/**
	 * @return void
	 */
	public function testGetAllNamesNoNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/constantWithoutNamespace.php');
		self::assertSame(['FOO'], ConstantHelper::getAllNames($phpcsFile));
	}

}
