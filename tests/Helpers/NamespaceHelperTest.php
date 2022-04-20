<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use function sprintf;
use const T_STRING;

class NamespaceHelperTest extends TestCase
{

	/**
	 * @return string[][]
	 */
	public function dataIsFullyQualifiedName(): array
	{
		return [
			[
				'\Foo',
				'\Foo\Bar',
			],
		];
	}

	/**
	 * @dataProvider dataIsFullyQualifiedName
	 * @param string $typeName
	 * @return void
	 */
	public function testIsFullyQualifiedName($typeName)
	{
		self::assertTrue(NamespaceHelper::isFullyQualifiedName($typeName));
	}

	/**
	 * @dataProvider dataIsFullyQualifiedName
	 * @param string $typeName
	 * @return void
	 */
	public function testGetFullyQualifiedTypeNameUnchanged($typeName)
	{
		self::assertSame($typeName, NamespaceHelper::getFullyQualifiedTypeName($typeName));
	}

	/**
	 * @return string[][]
	 */
	public function dataIsNotFullyQualifiedName(): array
	{
		return [
			[
				'Bar',
				'Foo\Bar',
			],
		];
	}

	/**
	 * @dataProvider dataIsNotFullyQualifiedName
	 * @param string $typeName
	 * @return void
	 */
	public function testIsNotFullyQualifiedName($typeName)
	{
		self::assertFalse(NamespaceHelper::isFullyQualifiedName($typeName));
	}

	/**
	 * @dataProvider dataIsNotFullyQualifiedName
	 * @param string $typeName
	 * @return void
	 */
	public function testGetFullyQualifiedTypeNameChanged($typeName)
	{
		self::assertSame(
			sprintf('\\%s', $typeName),
			NamespaceHelper::getFullyQualifiedTypeName($typeName)
		);
	}

	/**
	 * @return string[][]
	 */
	public function dataHasNamespace(): array
	{
		return [
			[
				'\Foo\Bar',
				'Foo\Bar',
			],
		];
	}

	/**
	 * @dataProvider dataHasNamespace
	 * @param string $typeName
	 * @return void
	 */
	public function testHasNamespace($typeName)
	{
		self::assertTrue(NamespaceHelper::hasNamespace($typeName));
	}

	/**
	 * @return string[][]
	 */
	public function dataDoesNotHaveNamespace(): array
	{
		return [
			[
				'Foo',
				'\Foo',
			],
		];
	}

	/**
	 * @dataProvider dataDoesNotHaveNamespace
	 * @param string $typeName
	 * @return void
	 */
	public function testDoesNotHaveNamespace($typeName)
	{
		self::assertFalse(NamespaceHelper::hasNamespace($typeName));
	}

	/**
	 * @return mixed[][]
	 */
	public function dataGetNameParts(): array
	{
		return [
			[
				'\Foo',
				['Foo'],
			],
			[
				'Foo',
				['Foo'],
			],
			[
				'\Foo\Bar\Baz',
				['Foo', 'Bar', 'Baz'],
			],
			[
				'Foo\Bar\Baz',
				['Foo', 'Bar', 'Baz'],
			],
		];
	}

	/**
	 * @dataProvider dataGetNameParts
	 * @param string[] $parts
	 * @param string $namespace
	 * @return void
	 */
	public function testGetNameParts($namespace, $parts)
	{
		self::assertSame($parts, NamespaceHelper::getNameParts($namespace));
	}

	/**
	 * @return void
	 */
	public function testFindCurrentNamespaceName()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/namespacedFile.php');
		$namespace = NamespaceHelper::findCurrentNamespaceName(
			$phpcsFile,
			TokenHelper::getLastTokenPointer($phpcsFile)
		);
		self::assertSame('Foo\Bar', $namespace);
	}

	/**
	 * @return void
	 */
	public function testFindCurrentNamespaceNameInFileWithoutNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/fileWithoutNamespace.php');
		self::assertNull(
			NamespaceHelper::findCurrentNamespaceName(
				$phpcsFile,
				TokenHelper::getLastTokenPointer($phpcsFile)
			)
		);
	}

	/**
	 * @return void
	 */
	public function testClosestNamespaceNameWithMultipleNamespacesInFile()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/multipleNamespaces.php');
		$namespace = NamespaceHelper::findCurrentNamespaceName(
			$phpcsFile,
			TokenHelper::getLastTokenPointer($phpcsFile)
		);
		self::assertSame('Lorem\Ipsum', $namespace);
	}

	/**
	 * @return void
	 */
	public function testGetAllNamespacesWithMultipleNamespacesInFile()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/multipleNamespaces.php');
		$namespaces = NamespaceHelper::getAllNamespacesPointers($phpcsFile);
		self::assertEquals([2, 16], $namespaces);
	}

	/**
	 * @return void
	 */
	public function testResolveClassNameWithMoreNamespaces()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/moreNamespaces.php');

		self::assertSame(
			'\Something\Foo',
			NamespaceHelper::resolveClassName($phpcsFile, 'Foo', $this->findPointerByLineAndType($phpcsFile, 7, T_STRING))
		);
		self::assertSame(
			'\Anything\Foo',
			NamespaceHelper::resolveClassName($phpcsFile, 'Foo', $this->findPointerByLineAndType($phpcsFile, 16, T_STRING))
		);
	}

	/**
	 * @return string[][]
	 */
	public function dataGetUnqualifiedNameFromFullyQualifiedName(): array
	{
		return [
			[
				'Foo',
				'\Foo',
			],
			[
				'Foo',
				'Foo',
			],
			[
				'Baz',
				'\Foo\Bar\Baz',
			],
			[
				'Baz',
				'Foo\Bar\Baz',
			],
		];
	}

	/**
	 * @dataProvider dataGetUnqualifiedNameFromFullyQualifiedName
	 * @param string $unqualifiedName
	 * @param string $fullyQualifiedName
	 * @return void
	 */
	public function testGetUnqualifiedNameFromFullyQualifiedName($unqualifiedName, $fullyQualifiedName)
	{
		self::assertSame($unqualifiedName, NamespaceHelper::getUnqualifiedNameFromFullyQualifiedName($fullyQualifiedName));
	}

	/**
	 * @return string[][]
	 */
	public function dataIsQualifiedName(): array
	{
		return [
			['\Foo'],
			['\Foo\Bar'],
			['Foo\Bar'],
		];
	}

	/**
	 * @dataProvider dataIsQualifiedName
	 * @param string $name
	 * @return void
	 */
	public function testIsQualifiedName($name)
	{
		self::assertTrue(NamespaceHelper::isQualifiedName($name));
	}

	/**
	 * @return string[][]
	 */
	public function dataIsNotQualifiedName(): array
	{
		return [
			['Foo'],
		];
	}

	/**
	 * @dataProvider dataIsNotQualifiedName
	 * @param string $name
	 * @return void
	 */
	public function testIsNotQualifiedName($name)
	{
		self::assertFalse(NamespaceHelper::isQualifiedName($name));
	}

	/**
	 * @return string[][]
	 */
	public function dataNormalizeToCanonicalName(): array
	{
		return [
			[
				'Foo',
				'Foo',
			],
			[
				'Foo',
				'\Foo',
			],
			[
				'Foo\Bar\Baz',
				'Foo\Bar\Baz',
			],
			[
				'Foo\Bar\Baz',
				'\Foo\Bar\Baz',
			],
		];
	}

	/**
	 * @dataProvider dataNormalizeToCanonicalName
	 * @param string $normalizedName
	 * @param string $originalName
	 * @return void
	 */
	public function testNormalizeToCanonicalName($normalizedName, $originalName)
	{
		self::assertSame($normalizedName, NamespaceHelper::normalizeToCanonicalName($originalName));
	}

	/**
	 * @return string[][]
	 */
	public function dataTypeIsInNamespace(): array
	{
		return [
			[
				'Foo\Bar',
				'Foo',
			],
			[
				'\Foo\Bar',
				'Foo',
			],
			[
				'Lorem\Ipsum\Dolor',
				'Lorem\Ipsum',
			],
			[
				'\Lorem\Ipsum\Dolor',
				'Lorem\Ipsum',
			],
		];
	}

	/**
	 * @dataProvider dataTypeIsInNamespace
	 * @param string $typeName
	 * @param string $namespace
	 * @return void
	 */
	public function testTypeIsInNamespace($typeName, $namespace)
	{
		self::assertTrue(NamespaceHelper::isTypeInNamespace($typeName, $namespace));
	}

	/**
	 * @return string[][]
	 */
	public function dataTypeIsNotInNamespace(): array
	{
		return [
			[
				'Foo\Bar',
				'Bar',
			],
			[
				'\Foo\Bar',
				'Bar',
			],
			[
				'Fooo\Bar',
				'Foo',
			],
			[
				'\Fooo\Bar',
				'Foo',
			],
			[
				'Lorem\Ipsum\DolorBar',
				'Lorem\Ipsum\Dolor',
			],
			[
				'\Lorem\Ipsum\DolorBar',
				'Lorem\Ipsum\Dolor',
			],
		];
	}

	/**
	 * @dataProvider dataTypeIsNotInNamespace
	 * @param string $typeName
	 * @param string $namespace
	 * @return void
	 */
	public function testTypeIsNotInNamespace($typeName, $namespace)
	{
		self::assertFalse(NamespaceHelper::isTypeInNamespace($typeName, $namespace));
	}

}
