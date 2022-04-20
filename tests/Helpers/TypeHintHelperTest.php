<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use function preg_split;
use const T_DOC_COMMENT_OPEN_TAG;

class TypeHintHelperTest extends TestCase
{

	/**
	 * @return mixed[][]
	 */
	public function dataIsSimpleTypeHint(): array
	{
		return [
			['int', true],
			['integer', true],
			['float', true],
			['string', true],
			['bool', true],
			['boolean', true],
			['callable', true],
			['self', true],
			['array', true],
			['iterable', true],
			['void', true],

			['\Traversable', false],
			['resource', false],
			['mixed[]', false],
			['object', false],
			['null', false],
		];
	}

	/**
	 * @dataProvider dataIsSimpleTypeHint
	 * @param string $typeHint
	 * @param bool $isSimple
	 * @return void
	 */
	public function testIsSimpleTypeHint($typeHint, $isSimple)
	{
		self::assertSame($isSimple, TypeHintHelper::isSimpleTypeHint($typeHint));
	}

	/**
	 * @return mixed[][]
	 */
	public function dataIsSimpleIterableTypeHint(): array
	{
		return [
			['array', true],
			['iterable', true],

			['\Traversable', false],
			['mixed[]', false],
		];
	}

	/**
	 * @dataProvider dataIsSimpleIterableTypeHint
	 * @param string $typeHint
	 * @param bool $isSimple
	 * @return void
	 */
	public function testIsSimpleIterableTypeHint($typeHint, $isSimple)
	{
		self::assertSame($isSimple, TypeHintHelper::isSimpleIterableTypeHint($typeHint));
	}

	/**
	 * @return mixed[][]
	 */
	public function dataIsSimpleUnofficialTypeHint(): array
	{
		return [
			['null', true],
			['mixed', true],
			['scalar', true],
			['numeric', true],
			['true', true],
			['false', true],
			['resource', true],
			['static', true],
			['$this', true],

			['\Traversable', false],
			['int', false],
			['bool', false],
			['object', true],
			['string', false],
		];
	}

	/**
	 * @dataProvider dataIsSimpleUnofficialTypeHint
	 * @param string $typeHint
	 * @param bool $isSimple
	 * @return void
	 */
	public function testIsSimpleUnofficialTypeHint($typeHint, $isSimple)
	{
		self::assertSame($isSimple, TypeHintHelper::isSimpleUnofficialTypeHints($typeHint));
	}

	/**
	 * @return string[][]
	 */
	public function dataConvertLongSimpleTypeHintToShort(): array
	{
		return [
			['integer', 'int'],
			['boolean', 'bool'],

			['int', 'int'],
			['bool', 'bool'],
			['string', 'string'],
			['float', 'float'],
		];
	}

	/**
	 * @dataProvider dataConvertLongSimpleTypeHintToShort
	 * @param string $long
	 * @param string $short
	 * @return void
	 */
	public function testConvertLongSimpleTypeHintToShort($long, $short)
	{
		self::assertSame($short, TypeHintHelper::convertLongSimpleTypeHintToShort($long));
	}

	/**
	 * @return void
	 */
	public function testFunctionReturnAnnotationWithNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithNamespace.php');

		$functionPointer = $this->findFunctionPointerByName($phpcsFile, 'fooFunctionWithReturnAnnotation');
		$returnAnnotation = FunctionHelper::findReturnAnnotation($phpcsFile, $functionPointer);
		self::assertSame('void', TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $functionPointer, $returnAnnotation->getContent()));
	}

	/**
	 * @return void
	 */
	public function testFunctionReturnTypeHintWithNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithNamespace.php');

		$functionPointer = $this->findFunctionPointerByName($phpcsFile, 'fooFunctionWithReturnTypeHint');
		$returnTypeHint = FunctionHelper::findReturnTypeHint($phpcsFile, $functionPointer);
		self::assertSame(
			'\FooNamespace\FooClass',
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $functionPointer, $returnTypeHint->getTypeHint())
		);
	}

	/**
	 * @return void
	 */
	public function testFunctionParameterAnnotationWithNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithNamespace.php');

		$functionPointer = $this->findFunctionPointerByName($phpcsFile, 'fooFunctionWithParameterAnnotation');
		$parameterAnnotation = FunctionHelper::getParametersAnnotations($phpcsFile, $functionPointer)[0];
		$parts = preg_split('~\\s+~', $parameterAnnotation->getContent());
		self::assertTrue(is_array($parts));
		$parameterTypeHint = $parts[0];
		self::assertSame(
			'\Doctrine\Common\Collections\ArrayCollection',
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $functionPointer, $parameterTypeHint)
		);
	}

	/**
	 * @return void
	 */
	public function testFunctionParameterTypeHintWithNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithNamespace.php');

		$functionPointer = $this->findFunctionPointerByName($phpcsFile, 'fooFunctionWithParameterTypeHint');
		$parameterTypeHint = FunctionHelper::getParametersTypeHints($phpcsFile, $functionPointer)['$parameter'];
		self::assertSame(
			'\Doctrine\Common\Collections\ArrayCollection',
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $functionPointer, $parameterTypeHint->getTypeHint())
		);
	}

	/**
	 * @return void
	 */
	public function testMethodReturnAnnotationWithNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithNamespace.php');

		$methodPointer = $this->findFunctionPointerByName($phpcsFile, 'fooMethodWithReturnAnnotation');
		$returnAnnotation = FunctionHelper::findReturnAnnotation($phpcsFile, $methodPointer);
		self::assertSame(
			'\FooNamespace\FooClass',
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $methodPointer, $returnAnnotation->getContent())
		);
	}

	/**
	 * @return void
	 */
	public function testMethodReturnTypeHintWithNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithNamespace.php');

		$methodPointer = $this->findFunctionPointerByName($phpcsFile, 'fooMethodWithReturnTypeHint');
		$returnTypeHint = FunctionHelper::findReturnTypeHint($phpcsFile, $methodPointer);
		self::assertSame('bool', TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $methodPointer, $returnTypeHint->getTypeHint()));
	}

	/**
	 * @return void
	 */
	public function testMethodParameterAnnotationWithNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithNamespace.php');

		$methodPointer = $this->findFunctionPointerByName($phpcsFile, 'fooMethodWithParameterAnnotation');
		$parameterAnnotation = FunctionHelper::getParametersAnnotations($phpcsFile, $methodPointer)[0];
		$parts = preg_split('~\\s+~', $parameterAnnotation->getContent());
		self::assertTrue(is_array($parts));
		$parameterTypeHint = $parts[0];
		self::assertSame(
			'\Doctrine\ORM\Mapping\Id',
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $methodPointer, $parameterTypeHint)
		);
	}

	/**
	 * @return void
	 */
	public function testMethodParameterTypeHintWithNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithNamespace.php');

		$methodPointer = $this->findFunctionPointerByName($phpcsFile, 'fooMethodWithParameterTypeHint');
		$parameterTypeHint = FunctionHelper::getParametersTypeHints($phpcsFile, $methodPointer)['$parameter'];
		self::assertSame(
			'\Doctrine\ORM\Mapping\Id',
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $methodPointer, $parameterTypeHint->getTypeHint())
		);
	}

	/**
	 * @return void
	 */
	public function testFunctionReturnAnnotationWithoutNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithoutNamespace.php');

		$functionPointer = $this->findFunctionPointerByName($phpcsFile, 'fooFunctionWithReturnAnnotation');
		$returnAnnotation = FunctionHelper::findReturnAnnotation($phpcsFile, $functionPointer);
		self::assertSame('void', TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $functionPointer, $returnAnnotation->getContent()));
	}

	/**
	 * @return void
	 */
	public function testFunctionReturnTypeHintWithoutNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithoutNamespace.php');

		$functionPointer = $this->findFunctionPointerByName($phpcsFile, 'fooFunctionWithReturnTypeHint');
		$returnTypeHint = FunctionHelper::findReturnTypeHint($phpcsFile, $functionPointer);
		self::assertSame(
			'\FooClass',
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $functionPointer, $returnTypeHint->getTypeHint())
		);
	}

	/**
	 * @return void
	 */
	public function testFunctionParameterAnnotationWithoutNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithoutNamespace.php');

		$functionPointer = $this->findFunctionPointerByName($phpcsFile, 'fooFunctionWithParameterAnnotation');
		$parameterAnnotation = FunctionHelper::getParametersAnnotations($phpcsFile, $functionPointer)[0];
		$parts = preg_split('~\\s+~', $parameterAnnotation->getContent());
		self::assertTrue(is_array($parts));
		$parameterTypeHint = $parts[0];
		self::assertSame(
			'\Doctrine\Common\Collections\ArrayCollection',
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $functionPointer, $parameterTypeHint)
		);
	}

	/**
	 * @return void
	 */
	public function testFunctionParameterTypeHintWithoutNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithoutNamespace.php');

		$functionPointer = $this->findFunctionPointerByName($phpcsFile, 'fooFunctionWithParameterTypeHint');
		$parameterTypeHint = FunctionHelper::getParametersTypeHints($phpcsFile, $functionPointer)['$parameter'];
		self::assertSame(
			'\Doctrine\Common\Collections\ArrayCollection',
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $functionPointer, $parameterTypeHint->getTypeHint())
		);
	}

	/**
	 * @return void
	 */
	public function testMethodReturnAnnotationWithoutNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithoutNamespace.php');

		$methodPointer = $this->findFunctionPointerByName($phpcsFile, 'fooMethodWithReturnAnnotation');
		$returnAnnotation = FunctionHelper::findReturnAnnotation($phpcsFile, $methodPointer);
		self::assertSame(
			'\FooClass',
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $methodPointer, $returnAnnotation->getContent())
		);
	}

	/**
	 * @return void
	 */
	public function testMethodReturnTypeHintWithoutNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithoutNamespace.php');

		$methodPointer = $this->findFunctionPointerByName($phpcsFile, 'fooMethodWithReturnTypeHint');
		$returnTypeHint = FunctionHelper::findReturnTypeHint($phpcsFile, $methodPointer);
		self::assertSame('bool', TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $methodPointer, $returnTypeHint->getTypeHint()));
	}

	/**
	 * @return void
	 */
	public function testMethodParameterAnnotationWithoutNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithoutNamespace.php');

		$methodPointer = $this->findFunctionPointerByName($phpcsFile, 'fooMethodWithParameterAnnotation');
		$parameterAnnotation = FunctionHelper::getParametersAnnotations($phpcsFile, $methodPointer)[0];
		$parts = preg_split('~\\s+~', $parameterAnnotation->getContent());
		self::assertTrue(is_array($parts));
		$parameterTypeHint = $parts[0];
		self::assertSame(
			'\Doctrine\ORM\Mapping\Id',
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $methodPointer, $parameterTypeHint)
		);
	}

	/**
	 * @return void
	 */
	public function testMethodParameterTypeHintWithoutNamespace()
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintWithoutNamespace.php');

		$methodPointer = $this->findFunctionPointerByName($phpcsFile, 'fooMethodWithParameterTypeHint');
		$parameterTypeHint = FunctionHelper::getParametersTypeHints($phpcsFile, $methodPointer)['$parameter'];
		self::assertSame(
			'\Doctrine\ORM\Mapping\Id',
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $methodPointer, $parameterTypeHint->getTypeHint())
		);
	}

	/**
	 * @return mixed[][]
	 */
	public function dataIsTypeDefinedInAnnotation(): array
	{
		return [
			['Whatever', false],
		];
	}

	/**
	 * @dataProvider dataIsTypeDefinedInAnnotation
	 * @param string $typeHintName
	 * @param bool $isTemplate
	 * @return void
	 */
	public function testIsTypeDefinedInAnnotation($typeHintName, $isTemplate)
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintsDefinedInAnnotation.php');

		$docCommentOpenPointer = $this->findPointerByLineAndType($phpcsFile, 3, T_DOC_COMMENT_OPEN_TAG);

		self::assertNotNull($docCommentOpenPointer);

		self::assertSame($isTemplate, TypeHintHelper::isTypeDefinedInAnnotation($phpcsFile, $docCommentOpenPointer, $typeHintName));
	}

	/**
	 * @return mixed[][]
	 */
	public function dataIsTypeDefinedInAnnotationWhenAnnotationIsInvalid(): array
	{
		return [
			[22, 'Alias'],
			[29, 'Template'],
		];
	}

	/**
	 * @dataProvider dataIsTypeDefinedInAnnotationWhenAnnotationIsInvalid
	 * @param int $line
	 * @param string $type
	 * @return void
	 */
	public function testIsTypeDefinedInAnnotationWhenAnnotationIsInvalid($line, $type)
	{
		$phpcsFile = $this->getCodeSnifferFile(__DIR__ . '/data/typeHintsDefinedInAnnotation.php');

		$docCommentOpenPointer = $this->findPointerByLineAndType($phpcsFile, $line, T_DOC_COMMENT_OPEN_TAG);

		self::assertNotNull($docCommentOpenPointer);

		self::assertFalse(TypeHintHelper::isTypeDefinedInAnnotation($phpcsFile, $docCommentOpenPointer, $type));
	}

}
