<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

class PropertyTypeHintTest extends TestCase
{

	/**
	 * @return void
	 */
	public function test()
	{
		$propertyTypeHint = new TypeHint('?string', true, 1, 6);

		self::assertSame('?string', $propertyTypeHint->getTypeHint());
		self::assertTrue($propertyTypeHint->isNullable());
		self::assertSame(1, $propertyTypeHint->getStartPointer());
		self::assertSame(6, $propertyTypeHint->getEndPointer());
	}

}
