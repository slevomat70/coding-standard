<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

class TypeHelperTest extends TestCase
{

	/**
	 * @return string[][]
	 */
	public function dataValidTypeNames(): array
	{
		return [
			['Foo'],
			['\\Foo'],
			['Foo\\Bar'],
			['\\Foo\\Bar'],
			['Foo\\Bar\\Baz'],
			['\\Foo\\Bar\\Baz'],
			['Foo1'],
			['string'],
			['resource'],
			['self'],
			['static'],
		];
	}

	/**
	 * @dataProvider dataValidTypeNames
	 * @param string $typeName
	 * @return void
	 */
	public function testValidTypeName($typeName)
	{
		self::assertTrue(TypeHelper::isTypeName($typeName));
	}

	/**
	 * @return string[][]
	 */
	public function dataNotValidTypeNames(): array
	{
		return [
			['1Foo'],
			['$this'],
			['Foo\\'],
			['Foo[]'],
			['[]'],
			['\Dogma\Math\Range\RangeSet<T>'],
		];
	}

	/**
	 * @dataProvider dataNotValidTypeNames
	 * @param string $typeName
	 * @return void
	 */
	public function testNotValidTypeName($typeName)
	{
		self::assertFalse(TypeHelper::isTypeName($typeName));
	}

}
