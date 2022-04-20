<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers\Annotation;

use InvalidArgumentException;
use LogicException;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use SlevomatCodingStandard\Helpers\TestCase;

class TemplateAnnotationTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testAnnotation()
	{
		$annotation = new TemplateAnnotation(
			'@template',
			1,
			10,
			'Description',
			new TemplateTagValueNode('Whatever', new IdentifierTypeNode('Anything'), 'Description')
		);

		self::assertSame('@template', $annotation->getName());
		self::assertSame(1, $annotation->getStartPointer());
		self::assertSame(10, $annotation->getEndPointer());
		self::assertSame('Description', $annotation->getContent());

		self::assertFalse($annotation->isInvalid());
		self::assertTrue($annotation->hasDescription());
		self::assertSame('Description', $annotation->getDescription());
		self::assertSame('Whatever', $annotation->getTemplateName());
		self::assertInstanceOf(IdentifierTypeNode::class, $annotation->getBound());
		self::assertSame('@template Whatever of Anything Description', $annotation->export());
	}

	/**
	 * @return void
	 */
	public function testUnsupportedAnnotation()
	{
		self::expectException(InvalidArgumentException::class);
		self::expectExceptionMessage('Unsupported annotation @param.');
		new TemplateAnnotation('@param', 1, 1, null, null);
	}

	/**
	 * @return void
	 */
	public function testGetContentNodeWhenInvalid()
	{
		self::expectException(LogicException::class);
		self::expectExceptionMessage('Invalid @template annotation.');
		$annotation = new TemplateAnnotation('@template', 1, 1, null, null);
		$annotation->getContentNode();
	}

	/**
	 * @return void
	 */
	public function testGetDescriptionWhenInvalid()
	{
		self::expectException(LogicException::class);
		self::expectExceptionMessage('Invalid @template annotation.');
		$annotation = new TemplateAnnotation('@template', 1, 1, null, null);
		$annotation->getDescription();
	}

	/**
	 * @return void
	 */
	public function testGetBoundWhenInvalid()
	{
		self::expectException(LogicException::class);
		self::expectExceptionMessage('Invalid @template annotation.');
		$annotation = new TemplateAnnotation('@template', 1, 1, null, null);
		$annotation->getBound();
	}

}
