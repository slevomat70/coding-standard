<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

class StringHelperTest extends TestCase
{

	/**
	 * @return string[][]
	 */
	public function dataStartsWith(): array
	{
		return [
			[
				'foo',
				'foo',
			],
			[
				'foobar',
				'foo',
			],
			[
				'foobar',
				'',
			],
		];
	}

	/**
	 * @dataProvider dataStartsWith
	 * @param string $haystack
	 * @param string $needle
	 * @return void
	 */
	public function testStartsWith($haystack, $needle)
	{
		self::assertTrue(StringHelper::startsWith($haystack, $needle));
	}

	/**
	 * @return string[][]
	 */
	public function dataNotStartsWith(): array
	{
		return [
			[
				'foo',
				'bar',
			],
			[
				'',
				'foo',
			],
			[
				'foobarfoo',
				'bar',
			],
			[
				'Foobar',
				'foo',
			],
		];
	}

	/**
	 * @dataProvider dataNotStartsWith
	 * @param string $haystack
	 * @param string $needle
	 * @return void
	 */
	public function testNotStartsWith($haystack, $needle)
	{
		self::assertFalse(StringHelper::startsWith($haystack, $needle));
	}

	/**
	 * @return string[][]
	 */
	public function dataEndsWith(): array
	{
		return [
			[
				'foobar',
				'bar',
			],
			[
				'foobar',
				'',
			],
			[
				'foobar',
				'foobar',
			],
		];
	}

	/**
	 * @dataProvider dataEndsWith
	 * @param string $haystack
	 * @param string $needle
	 * @return void
	 */
	public function testEndsWith($haystack, $needle)
	{
		self::assertTrue(StringHelper::endsWith($haystack, $needle));
	}

	/**
	 * @return string[][]
	 */
	public function dataNotEndsWith(): array
	{
		return [
			[
				'foobar',
				'foo',
			],
			[
				'',
				'foo',
			],
			[
				'barfoobar',
				'foo',
			],
			[
				'Foobar',
				'Bar',
			],
		];
	}

	/**
	 * @dataProvider dataNotEndsWith
	 * @param string $haystack
	 * @param string $needle
	 * @return void
	 */
	public function testNotEndsWith($haystack, $needle)
	{
		self::assertFalse(StringHelper::endsWith($haystack, $needle));
	}

}
