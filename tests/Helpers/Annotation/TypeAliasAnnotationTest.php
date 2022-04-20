<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers\Annotation;

use InvalidArgumentException;
use LogicException;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use SlevomatCodingStandard\Helpers\TestCase;

class TypeAliasAnnotationTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testAnnotation()
	{
		$annotation = new TypeAliasAnnotation(
			'@phpstan-type',
			1,
			10,
			null,
			new TypeAliasTagValueNode('Whatever', new IdentifierTypeNode('Anything'))
		);

		self::assertSame('@phpstan-type', $annotation->getName());
		self::assertSame(1, $annotation->getStartPointer());
		self::assertSame(10, $annotation->getEndPointer());
		self::assertNull($annotation->getContent());

		self::assertFalse($annotation->isInvalid());
		self::assertSame('Whatever', $annotation->getAlias());
		self::assertInstanceOf(IdentifierTypeNode::class, $annotation->getType());
		self::assertSame('@phpstan-type Whatever Anything', $annotation->export());
	}

	/**
	 * @return void
	 */
	public function testUnsupportedAnnotation()
	{
		self::expectException(InvalidArgumentException::class);
		self::expectExceptionMessage('Unsupported annotation @param.');
		new TypeAliasAnnotation('@param', 1, 1, null, null);
	}

	/**
	 * @return void
	 */
	public function testGetContentNodeWhenInvalid()
	{
		self::expectException(LogicException::class);
		self::expectExceptionMessage('Invalid @phpstan-type annotation.');
		$annotation = new TypeAliasAnnotation('@phpstan-type', 1, 1, null, null);
		$annotation->getContentNode();
	}

	/**
	 * @return void
	 */
	public function testGetAliasWhenInvalid()
	{
		self::expectException(LogicException::class);
		self::expectExceptionMessage('Invalid @phpstan-type annotation.');
		$annotation = new TypeAliasAnnotation('@phpstan-type', 1, 1, null, null);
		$annotation->getAlias();
	}

	/**
	 * @return void
	 */
	public function testGetTypeWhenInvalid()
	{
		self::expectException(LogicException::class);
		self::expectExceptionMessage('Invalid @phpstan-type annotation.');
		$annotation = new TypeAliasAnnotation('@phpstan-type', 1, 1, null, null);
		$annotation->getType();
	}

}
