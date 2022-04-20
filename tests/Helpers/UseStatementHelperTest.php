<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use const T_CLASS;
use const T_FUNCTION;
use const T_USE;

class UseStatementHelperTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testIsAnonymousFunctionUse()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/anonymousFunction.php');
		$usePointer = TokenHelper::findNext($phpcsFile, T_USE, 0);
		self::assertTrue(UseStatementHelper::isAnonymousFunctionUse($phpcsFile, $usePointer));
	}

	/**
	 * @return void
	 */
	public function testIsNotAnonymousFunctionUse()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/useStatements.php');
		$usePointer = TokenHelper::findNext($phpcsFile, T_USE, 0);
		self::assertFalse(UseStatementHelper::isAnonymousFunctionUse($phpcsFile, $usePointer));
	}

	/**
	 * @return void
	 */
	public function testIsTraitUse()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/classWithTrait.php');
		$usePointer = TokenHelper::findNext($phpcsFile, T_USE, 0);
		self::assertTrue(UseStatementHelper::isTraitUse($phpcsFile, $usePointer));
	}

	/**
	 * @return void
	 */
	public function testIsTraitUseInAnonymousClass()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/anonymousClassWithTrait.php');
		$usePointer = TokenHelper::findNext($phpcsFile, T_USE, 0);
		self::assertTrue(UseStatementHelper::isTraitUse($phpcsFile, $usePointer));
	}

	/**
	 * @return void
	 */
	public function testIsNotTraitUse()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/useStatements.php');
		$usePointer = TokenHelper::findNext($phpcsFile, T_USE, 0);
		self::assertFalse(UseStatementHelper::isTraitUse($phpcsFile, $usePointer));

		$classPointer = TokenHelper::findNext($phpcsFile, T_CLASS, 0);
		$methodPointer = TokenHelper::findNext($phpcsFile, T_FUNCTION, $classPointer);
		$usePointer = TokenHelper::findNext($phpcsFile, T_USE, $methodPointer);
		self::assertFalse(UseStatementHelper::isTraitUse($phpcsFile, $usePointer));
	}

	/**
	 * @return void
	 */
	public function testGetAlias()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/useStatements.php');
		$bazUsePointer = TokenHelper::findNext($phpcsFile, T_USE, 0);
		self::assertNull(UseStatementHelper::getAlias($phpcsFile, $bazUsePointer));

		$fooUsePointer = TokenHelper::findNext($phpcsFile, T_USE, $bazUsePointer + 1);
		self::assertNull(UseStatementHelper::getAlias($phpcsFile, $fooUsePointer));

		$loremIpsumUsePointer = TokenHelper::findNext($phpcsFile, T_USE, $fooUsePointer + 1);
		self::assertSame('LoremIpsum', UseStatementHelper::getAlias($phpcsFile, $loremIpsumUsePointer));
	}

	/**
	 * @return void
	 */
	public function testGetNameAsReferencedInClassFromUse()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/useStatements.php');
		$bazUsePointer = TokenHelper::findNext($phpcsFile, T_USE, 0);
		self::assertSame('Baz', UseStatementHelper::getNameAsReferencedInClassFromUse($phpcsFile, $bazUsePointer));

		$fooUsePointer = TokenHelper::findNext($phpcsFile, T_USE, $bazUsePointer + 1);
		self::assertSame('Foo', UseStatementHelper::getNameAsReferencedInClassFromUse($phpcsFile, $fooUsePointer));

		$loremIpsumUsePointer = TokenHelper::findNext($phpcsFile, T_USE, $fooUsePointer + 1);
		self::assertSame('LoremIpsum', UseStatementHelper::getNameAsReferencedInClassFromUse($phpcsFile, $loremIpsumUsePointer));
	}

	/**
	 * @return void
	 */
	public function testGetFullyQualifiedTypeNameFromUse()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/useStatements.php');
		$bazUsePointer = TokenHelper::findNext($phpcsFile, T_USE, 0);
		self::assertSame('Bar\Baz', UseStatementHelper::getFullyQualifiedTypeNameFromUse($phpcsFile, $bazUsePointer));

		$fooUsePointer = TokenHelper::findNext($phpcsFile, T_USE, $bazUsePointer + 1);
		self::assertSame('Foo', UseStatementHelper::getFullyQualifiedTypeNameFromUse($phpcsFile, $fooUsePointer));

		$loremIpsumUsePointer = TokenHelper::findNext($phpcsFile, T_USE, $fooUsePointer + 1);
		self::assertSame('Lorem\Ipsum', UseStatementHelper::getFullyQualifiedTypeNameFromUse($phpcsFile, $loremIpsumUsePointer));

		$lerdorfIsBarConstantUsePointer = TokenHelper::findNext($phpcsFile, T_USE, $loremIpsumUsePointer + 1);
		self::assertSame(
			'Lerdorf\IS_BAR',
			UseStatementHelper::getFullyQualifiedTypeNameFromUse($phpcsFile, $lerdorfIsBarConstantUsePointer)
		);

		$rasmusFooConstantUsePointer = TokenHelper::findNext($phpcsFile, T_USE, $lerdorfIsBarConstantUsePointer + 1);
		self::assertSame('Rasmus\FOO', UseStatementHelper::getFullyQualifiedTypeNameFromUse($phpcsFile, $rasmusFooConstantUsePointer));

		$lerdorfIsBarFunctionUsePointer = TokenHelper::findNext($phpcsFile, T_USE, $rasmusFooConstantUsePointer + 1);
		self::assertSame(
			'Lerdorf\isBar',
			UseStatementHelper::getFullyQualifiedTypeNameFromUse($phpcsFile, $lerdorfIsBarFunctionUsePointer)
		);

		$rasmusFooFunctionUsePointer = TokenHelper::findNext($phpcsFile, T_USE, $lerdorfIsBarFunctionUsePointer + 1);
		self::assertSame('Rasmus\foo', UseStatementHelper::getFullyQualifiedTypeNameFromUse($phpcsFile, $rasmusFooFunctionUsePointer));
	}

	/**
	 * @return void
	 */
	public function testGetFileUseStatements()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/useStatements.php');
		$useStatements = UseStatementHelper::getFileUseStatements($phpcsFile)[0];
		self::assertCount(8, $useStatements);
		self::assertSame(3, $useStatements[UseStatement::getUniqueId(UseStatement::TYPE_CLASS, 'Baz')]->getPointer());
		self::assertUseStatement(
			'Bar\Baz',
			'Baz',
			$useStatements[UseStatement::getUniqueId(UseStatement::TYPE_CLASS, 'Baz')],
			false,
			false,
			null
		);
		self::assertUseStatement(
			'Foo',
			'Foo',
			$useStatements[UseStatement::getUniqueId(UseStatement::TYPE_CLASS, 'Foo')],
			false,
			false,
			null
		);
		self::assertUseStatement(
			'Lorem\Ipsum',
			'LoremIpsum',
			$useStatements[UseStatement::getUniqueId(UseStatement::TYPE_CLASS, 'LoremIpsum')],
			false,
			false,
			'LoremIpsum'
		);
		self::assertUseStatement(
			'Zero',
			'Zero',
			$useStatements[UseStatement::getUniqueId(UseStatement::TYPE_CLASS, 'Zero')],
			false,
			false,
			null
		);
		self::assertUseStatement(
			'Rasmus\FOO',
			'FOO',
			$useStatements[UseStatement::getUniqueId(UseStatement::TYPE_CONSTANT, 'FOO')],
			false,
			true,
			null
		);
		self::assertUseStatement(
			'Rasmus\foo',
			'foo',
			$useStatements[UseStatement::getUniqueId(UseStatement::TYPE_FUNCTION, 'foo')],
			true,
			false,
			null
		);
		self::assertUseStatement(
			'Lerdorf\IS_BAR',
			'IS_BAR',
			$useStatements[UseStatement::getUniqueId(UseStatement::TYPE_CONSTANT, 'IS_BAR')],
			false,
			true,
			null
		);
		self::assertUseStatement(
			'Lerdorf\isBar',
			'isBar',
			$useStatements[UseStatement::getUniqueId(UseStatement::TYPE_FUNCTION, 'isBar')],
			true,
			false,
			null
		);
	}

	/**
	 * @param string|null $alias
	 * @return void
	 */
	private function assertUseStatement(
		string $fullyQualifiedTypeName,
		string $referencedName,
		UseStatement $useStatement,
		bool $isFunction,
		bool $isConstant,
		$alias
	)
	{
		self::assertSame($fullyQualifiedTypeName, $useStatement->getFullyQualifiedTypeName());
		self::assertSame($referencedName, $useStatement->getNameAsReferencedInFile());
		self::assertSame($isFunction, $useStatement->isFunction());
		self::assertSame($isConstant, $useStatement->isConstant());
		self::assertSame($alias, $useStatement->getAlias());
	}

}
