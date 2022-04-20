<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers\Annotation;

use InvalidArgumentException;
use LogicException;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use SlevomatCodingStandard\Helpers\TestCase;

class MixinAnnotationTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testAnnotation()
	{
		$annotation = new MixinAnnotation(
			'@mixin',
			1,
			10,
			'Description',
			new MixinTagValueNode(new IdentifierTypeNode('Exception'), 'Description')
		);

		self::assertSame('@mixin', $annotation->getName());
		self::assertSame(1, $annotation->getStartPointer());
		self::assertSame(10, $annotation->getEndPointer());
		self::assertSame('Description', $annotation->getContent());

		self::assertFalse($annotation->isInvalid());
		self::assertTrue($annotation->hasDescription());
		self::assertSame('Description', $annotation->getDescription());
		self::assertInstanceOf(IdentifierTypeNode::class, $annotation->getType());
		self::assertSame('@mixin Exception Description', $annotation->export());
	}

	/**
	 * @return void
	 */
	public function testUnsupportedAnnotation()
	{
		self::expectException(InvalidArgumentException::class);
		self::expectExceptionMessage('Unsupported annotation @param.');
		new MixinAnnotation('@param', 1, 1, null, null);
	}

	/**
	 * @return void
	 */
	public function testGetContentNodeWhenInvalid()
	{
		self::expectException(LogicException::class);
		self::expectExceptionMessage('Invalid @mixin annotation.');
		$annotation = new MixinAnnotation('@mixin', 1, 1, null, null);
		$annotation->getContentNode();
	}

	/**
	 * @return void
	 */
	public function testGetDescriptionWhenInvalid()
	{
		self::expectException(LogicException::class);
		self::expectExceptionMessage('Invalid @mixin annotation.');
		$annotation = new MixinAnnotation('@mixin', 1, 1, null, null);
		$annotation->getDescription();
	}

	/**
	 * @return void
	 */
	public function testGetTypeWhenInvalid()
	{
		self::expectException(LogicException::class);
		self::expectExceptionMessage('Invalid @mixin annotation.');
		$annotation = new MixinAnnotation('@mixin', 1, 1, null, null);
		$annotation->getType();
	}

}
