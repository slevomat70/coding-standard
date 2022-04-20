<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFloatNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeParameterNode;
use PHPStan\PhpDocParser\Ast\Type\ConditionalTypeForParameterNode;
use PHPStan\PhpDocParser\Ast\Type\ConditionalTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use function array_merge;
use function count;
use function in_array;
use function preg_replace;
use function sprintf;
use function strtolower;
use function substr;

/**
 * @internal
 */
class AnnotationTypeHelper
{

	/**
	 * @return IdentifierTypeNode[]|ThisTypeNode[]
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 */
	public static function getIdentifierTypeNodes($typeNode): array
	{
		if ($typeNode instanceof ArrayTypeNode) {
			return self::getIdentifierTypeNodes($typeNode->type);
		}

		if ($typeNode instanceof ArrayShapeNode) {
			$identifierTypeNodes = [];
			foreach ($typeNode->items as $arrayShapeItemNode) {
				$identifierTypeNodes = array_merge($identifierTypeNodes, self::getIdentifierTypeNodes($arrayShapeItemNode->valueType));
			}
			return $identifierTypeNodes;
		}

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			$identifierTypeNodes = [];
			foreach ($typeNode->types as $innerTypeNode) {
				$identifierTypeNodes = array_merge($identifierTypeNodes, self::getIdentifierTypeNodes($innerTypeNode));
			}
			return $identifierTypeNodes;
		}

		if ($typeNode instanceof GenericTypeNode) {
			$identifierTypeNodes = self::getIdentifierTypeNodes($typeNode->type);
			foreach ($typeNode->genericTypes as $innerTypeNode) {
				$identifierTypeNodes = array_merge($identifierTypeNodes, self::getIdentifierTypeNodes($innerTypeNode));
			}
			return $identifierTypeNodes;
		}

		if ($typeNode instanceof NullableTypeNode) {
			return self::getIdentifierTypeNodes($typeNode->type);
		}

		if ($typeNode instanceof CallableTypeNode) {
			$identifierTypeNodes = array_merge([$typeNode->identifier], self::getIdentifierTypeNodes($typeNode->returnType));
			foreach ($typeNode->parameters as $callableParameterNode) {
				$identifierTypeNodes = array_merge($identifierTypeNodes, self::getIdentifierTypeNodes($callableParameterNode->type));
			}
			return $identifierTypeNodes;
		}

		if ($typeNode instanceof ConstTypeNode) {
			return [];
		}

		if ($typeNode instanceof ConditionalTypeNode) {
			return array_merge(
				self::getIdentifierTypeNodes($typeNode->subjectType),
				self::getIdentifierTypeNodes($typeNode->targetType),
				self::getIdentifierTypeNodes($typeNode->if),
				self::getIdentifierTypeNodes($typeNode->else)
			);
		}

		if ($typeNode instanceof ConditionalTypeForParameterNode) {
			return array_merge(
				self::getIdentifierTypeNodes($typeNode->targetType),
				self::getIdentifierTypeNodes($typeNode->if),
				self::getIdentifierTypeNodes($typeNode->else)
			);
		}

		/** @var IdentifierTypeNode|ThisTypeNode $typeNode */
		$typeNode = $typeNode;
		return [$typeNode];
	}

	/**
	 * @return ConstTypeNode[]
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 */
	public static function getConstantTypeNodes($typeNode): array
	{
		if ($typeNode instanceof ArrayTypeNode) {
			return self::getConstantTypeNodes($typeNode->type);
		}

		if ($typeNode instanceof ArrayShapeNode) {
			$constTypeNodes = [];
			foreach ($typeNode->items as $arrayShapeItemNode) {
				$constTypeNodes = array_merge($constTypeNodes, self::getConstantTypeNodes($arrayShapeItemNode->valueType));
			}
			return $constTypeNodes;
		}

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			$constTypeNodes = [];
			foreach ($typeNode->types as $innerTypeNode) {
				$constTypeNodes = array_merge($constTypeNodes, self::getConstantTypeNodes($innerTypeNode));
			}
			return $constTypeNodes;
		}

		if ($typeNode instanceof GenericTypeNode) {
			$constTypeNodes = [];
			foreach ($typeNode->genericTypes as $innerTypeNode) {
				$constTypeNodes = array_merge($constTypeNodes, self::getConstantTypeNodes($innerTypeNode));
			}
			return $constTypeNodes;
		}

		if ($typeNode instanceof NullableTypeNode) {
			return self::getConstantTypeNodes($typeNode->type);
		}

		if ($typeNode instanceof CallableTypeNode) {
			$constTypeNodes = self::getConstantTypeNodes($typeNode->returnType);
			foreach ($typeNode->parameters as $callableParameterNode) {
				$constTypeNodes = array_merge($constTypeNodes, self::getConstantTypeNodes($callableParameterNode->type));
			}
			return $constTypeNodes;
		}

		if ($typeNode instanceof ConditionalTypeNode) {
			return array_merge(
				self::getConstantTypeNodes($typeNode->subjectType),
				self::getConstantTypeNodes($typeNode->targetType),
				self::getConstantTypeNodes($typeNode->if),
				self::getConstantTypeNodes($typeNode->else)
			);
		}

		if ($typeNode instanceof ConditionalTypeForParameterNode) {
			return array_merge(
				self::getConstantTypeNodes($typeNode->targetType),
				self::getConstantTypeNodes($typeNode->if),
				self::getConstantTypeNodes($typeNode->else)
			);
		}

		if (!$typeNode instanceof ConstTypeNode) {
			return [];
		}

		return [$typeNode];
	}

	/**
	 * @return UnionTypeNode[]
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 */
	public static function getUnionTypeNodes($typeNode): array
	{
		if ($typeNode instanceof UnionTypeNode) {
			return [$typeNode];
		}

		if ($typeNode instanceof NullableTypeNode) {
			return self::getUnionTypeNodes($typeNode->type);
		}

		if ($typeNode instanceof ArrayTypeNode) {
			return self::getUnionTypeNodes($typeNode->type);
		}

		if ($typeNode instanceof ArrayShapeNode) {
			$unionTypeNodes = [];
			foreach ($typeNode->items as $arrayShapeItemNode) {
				$unionTypeNodes = array_merge($unionTypeNodes, self::getUnionTypeNodes($arrayShapeItemNode->valueType));
			}
			return $unionTypeNodes;
		}

		if ($typeNode instanceof IntersectionTypeNode) {
			$unionTypeNodes = [];
			foreach ($typeNode->types as $innerTypeNode) {
				$unionTypeNodes = array_merge($unionTypeNodes, self::getUnionTypeNodes($innerTypeNode));
			}
			return $unionTypeNodes;
		}

		if ($typeNode instanceof GenericTypeNode) {
			$unionTypeNodes = [];
			foreach ($typeNode->genericTypes as $innerTypeNode) {
				$unionTypeNodes = array_merge($unionTypeNodes, self::getUnionTypeNodes($innerTypeNode));
			}
			return $unionTypeNodes;
		}

		if ($typeNode instanceof CallableTypeNode) {
			$unionTypeNodes = self::getUnionTypeNodes($typeNode->returnType);
			foreach ($typeNode->parameters as $callableParameterNode) {
				$unionTypeNodes = array_merge($unionTypeNodes, self::getUnionTypeNodes($callableParameterNode->type));
			}
			return $unionTypeNodes;
		}

		if ($typeNode instanceof ConditionalTypeNode) {
			return array_merge(
				self::getUnionTypeNodes($typeNode->subjectType),
				self::getUnionTypeNodes($typeNode->targetType),
				self::getUnionTypeNodes($typeNode->if),
				self::getUnionTypeNodes($typeNode->else)
			);
		}

		if ($typeNode instanceof ConditionalTypeForParameterNode) {
			return array_merge(
				self::getUnionTypeNodes($typeNode->targetType),
				self::getUnionTypeNodes($typeNode->if),
				self::getUnionTypeNodes($typeNode->else)
			);
		}

		return [];
	}

	/**
	 * @return ArrayTypeNode[]
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 */
	public static function getArrayTypeNodes($typeNode): array
	{
		if ($typeNode instanceof ArrayTypeNode) {
			return array_merge([$typeNode], self::getArrayTypeNodes($typeNode->type));
		}

		if ($typeNode instanceof ArrayShapeNode) {
			$arrayTypeNodes = [];
			foreach ($typeNode->items as $arrayShapeItemNode) {
				$arrayTypeNodes = array_merge($arrayTypeNodes, self::getArrayTypeNodes($arrayShapeItemNode->valueType));
			}
			return $arrayTypeNodes;
		}

		if ($typeNode instanceof NullableTypeNode) {
			return self::getArrayTypeNodes($typeNode->type);
		}

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			$arrayTypeNodes = [];
			foreach ($typeNode->types as $innerTypeNode) {
				$arrayTypeNodes = array_merge($arrayTypeNodes, self::getArrayTypeNodes($innerTypeNode));
			}
			return $arrayTypeNodes;
		}

		if ($typeNode instanceof GenericTypeNode) {
			$arrayTypeNodes = [];
			foreach ($typeNode->genericTypes as $innerTypeNode) {
				$arrayTypeNodes = array_merge($arrayTypeNodes, self::getArrayTypeNodes($innerTypeNode));
			}
			return $arrayTypeNodes;
		}

		if ($typeNode instanceof CallableTypeNode) {
			$arrayTypeNodes = self::getArrayTypeNodes($typeNode->returnType);
			foreach ($typeNode->parameters as $callableParameterNode) {
				$arrayTypeNodes = array_merge($arrayTypeNodes, self::getArrayTypeNodes($callableParameterNode->type));
			}
			return $arrayTypeNodes;
		}

		if ($typeNode instanceof ConditionalTypeNode) {
			return array_merge(
				self::getArrayTypeNodes($typeNode->subjectType),
				self::getArrayTypeNodes($typeNode->targetType),
				self::getArrayTypeNodes($typeNode->if),
				self::getArrayTypeNodes($typeNode->else)
			);
		}

		if ($typeNode instanceof ConditionalTypeForParameterNode) {
			return array_merge(
				self::getArrayTypeNodes($typeNode->targetType),
				self::getArrayTypeNodes($typeNode->if),
				self::getArrayTypeNodes($typeNode->else)
			);
		}

		return [];
	}

	/**
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 */
	public static function getTypeHintFromNode($typeNode): string
	{
		return $typeNode instanceof ThisTypeNode
			? (string) $typeNode
			: $typeNode->name;
	}

	/**
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 */
	public static function export($typeNode): string
	{
		$exportedTypeNode = (string) preg_replace(['~\\s*([&|])\\s*~'], '\\1', (string) $typeNode);

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			$exportedTypeNode = substr($exportedTypeNode, 1, -1);
		}

		if ($typeNode instanceof ArrayTypeNode && $typeNode->type instanceof CallableTypeNode) {
			$exportedTypeNode = sprintf('(%s)[]', substr($exportedTypeNode, 0, -2));
		}

		return $exportedTypeNode;
	}

	/**
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $masterTypeNode
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNodeToChange
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $changedTypeNode
	 */
	public static function change($masterTypeNode, $typeNodeToChange, $changedTypeNode): TypeNode
	{
		if ($masterTypeNode === $typeNodeToChange) {
			return $changedTypeNode;
		}

		if ($masterTypeNode instanceof UnionTypeNode) {
			$types = [];
			foreach ($masterTypeNode->types as $typeNone) {
				$types[] = self::change($typeNone, $typeNodeToChange, $changedTypeNode);
			}

			return new UnionTypeNode($types);
		}

		if ($masterTypeNode instanceof IntersectionTypeNode) {
			$types = [];
			foreach ($masterTypeNode->types as $typeNone) {
				$types[] = self::change($typeNone, $typeNodeToChange, $changedTypeNode);
			}

			return new IntersectionTypeNode($types);
		}

		if ($masterTypeNode instanceof GenericTypeNode) {
			$genericTypes = [];
			foreach ($masterTypeNode->genericTypes as $genericTypeNode) {
				$genericTypes[] = self::change($genericTypeNode, $typeNodeToChange, $changedTypeNode);
			}

			/** @var IdentifierTypeNode $identificatorTypeNode */
			$identificatorTypeNode = self::change($masterTypeNode->type, $typeNodeToChange, $changedTypeNode);
			return new GenericTypeNode($identificatorTypeNode, $genericTypes);
		}

		if ($masterTypeNode instanceof ArrayTypeNode) {
			return new ArrayTypeNode(self::change($masterTypeNode->type, $typeNodeToChange, $changedTypeNode));
		}

		if ($masterTypeNode instanceof ArrayShapeNode) {
			$arrayShapeItemNodes = [];
			foreach ($masterTypeNode->items as $arrayShapeItemNode) {
				$arrayShapeItemNodes[] = self::change($arrayShapeItemNode, $typeNodeToChange, $changedTypeNode);
			}

			return new ArrayShapeNode($arrayShapeItemNodes);
		}

		if ($masterTypeNode instanceof ArrayShapeItemNode) {
			return new ArrayShapeItemNode(
				$masterTypeNode->keyName,
				$masterTypeNode->optional,
				self::change($masterTypeNode->valueType, $typeNodeToChange, $changedTypeNode)
			);
		}

		if ($masterTypeNode instanceof NullableTypeNode) {
			return new NullableTypeNode(self::change($masterTypeNode->type, $typeNodeToChange, $changedTypeNode));
		}

		if ($masterTypeNode instanceof CallableTypeNode) {
			$callableParameters = [];
			foreach ($masterTypeNode->parameters as $parameterTypeNode) {
				$callableParameters[] = new CallableTypeParameterNode(
					self::change($parameterTypeNode->type, $typeNodeToChange, $changedTypeNode),
					$parameterTypeNode->isReference,
					$parameterTypeNode->isVariadic,
					$parameterTypeNode->parameterName,
					$parameterTypeNode->isOptional
				);
			}

			/** @var IdentifierTypeNode $identificatorTypeNode */
			$identificatorTypeNode = self::change($masterTypeNode->identifier, $typeNodeToChange, $changedTypeNode);
			return new CallableTypeNode(
				$identificatorTypeNode,
				$callableParameters,
				self::change($masterTypeNode->returnType, $typeNodeToChange, $changedTypeNode)
			);
		}

		if ($masterTypeNode instanceof ConditionalTypeNode) {
			return new ConditionalTypeNode(
				self::change($masterTypeNode->subjectType, $typeNodeToChange, $changedTypeNode),
				self::change($masterTypeNode->targetType, $typeNodeToChange, $changedTypeNode),
				self::change($masterTypeNode->if, $typeNodeToChange, $changedTypeNode),
				self::change($masterTypeNode->else, $typeNodeToChange, $changedTypeNode),
				$masterTypeNode->negated
			);
		}

		if ($masterTypeNode instanceof ConditionalTypeForParameterNode) {
			return new ConditionalTypeForParameterNode(
				$masterTypeNode->parameterName,
				self::change($masterTypeNode->targetType, $typeNodeToChange, $changedTypeNode),
				self::change($masterTypeNode->if, $typeNodeToChange, $changedTypeNode),
				self::change($masterTypeNode->else, $typeNodeToChange, $changedTypeNode),
				$masterTypeNode->negated
			);
		}

		return clone $masterTypeNode;
	}

	/**
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 */
	public static function containsStaticOrThisType($typeNode): bool
	{
		if ($typeNode instanceof ThisTypeNode) {
			return true;
		}

		if ($typeNode instanceof IdentifierTypeNode) {
			return strtolower($typeNode->name) === 'static';
		}

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			foreach ($typeNode->types as $innerTypeNode) {
				if (self::containsStaticOrThisType($innerTypeNode)) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 */
	public static function containsOneType($typeNode): bool
	{
		if ($typeNode instanceof IdentifierTypeNode) {
			return true;
		}

		if ($typeNode instanceof ThisTypeNode) {
			return true;
		}

		if ($typeNode instanceof GenericTypeNode) {
			return true;
		}

		if ($typeNode instanceof CallableTypeNode) {
			return true;
		}

		if ($typeNode instanceof ArrayShapeNode) {
			return true;
		}

		if ($typeNode instanceof ArrayTypeNode) {
			return true;
		}

		if ($typeNode instanceof ConstTypeNode) {
			if ($typeNode->constExpr instanceof ConstExprIntegerNode) {
				return true;
			}

			if ($typeNode->constExpr instanceof ConstExprFloatNode) {
				return true;
			}

			if ($typeNode->constExpr instanceof ConstExprStringNode) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 */
	public static function containsJustTwoTypes($typeNode): bool
	{
		if ($typeNode instanceof NullableTypeNode && self::containsOneType($typeNode->type)) {
			return true;
		}

		if (
			!$typeNode instanceof UnionTypeNode
			&& !$typeNode instanceof IntersectionTypeNode
		) {
			return false;
		}

		return count($typeNode->types) === 2;
	}

	/**
	 * @param array<int, string> $traversableTypeHints
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 */
	public static function containsTraversableType($typeNode, $phpcsFile, $pointer, $traversableTypeHints): bool
	{
		if ($typeNode instanceof GenericTypeNode) {
			return true;
		}

		if ($typeNode instanceof ArrayShapeNode) {
			return true;
		}

		if ($typeNode instanceof ArrayTypeNode) {
			return true;
		}

		if ($typeNode instanceof IdentifierTypeNode) {
			$fullyQualifiedType = TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $pointer, $typeNode->name);
			return TypeHintHelper::isTraversableType($fullyQualifiedType, $traversableTypeHints);
		}

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			foreach ($typeNode->types as $innerTypeNode) {
				if (self::containsTraversableType($innerTypeNode, $phpcsFile, $pointer, $traversableTypeHints)) {
					return true;
				}
			}
		}

		return
			(
				$typeNode instanceof ConditionalTypeNode
				|| $typeNode instanceof ConditionalTypeForParameterNode
			) && (
				self::containsTraversableType($typeNode->if, $phpcsFile, $pointer, $traversableTypeHints)
				|| self::containsTraversableType($typeNode->else, $phpcsFile, $pointer, $traversableTypeHints)
			);
	}

	/**
	 * @param array<int, string> $traversableTypeHints
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 * @param bool $inTraversable
	 */
	public static function containsItemsSpecificationForTraversable(
		$typeNode,
		$phpcsFile,
		$pointer,
		$traversableTypeHints,
		$inTraversable = false
	): bool
	{
		if ($typeNode instanceof GenericTypeNode) {
			foreach ($typeNode->genericTypes as $genericType) {
				if (!self::containsItemsSpecificationForTraversable($genericType, $phpcsFile, $pointer, $traversableTypeHints, true)) {
					return false;
				}
			}

			return true;
		}

		if ($typeNode instanceof ArrayShapeNode) {
			foreach ($typeNode->items as $arrayShapeItemNode) {
				if (!self::containsItemsSpecificationForTraversable(
					$arrayShapeItemNode->valueType,
					$phpcsFile,
					$pointer,
					$traversableTypeHints,
					true
				)) {
					return false;
				}
			}

			return true;
		}

		if ($typeNode instanceof NullableTypeNode) {
			return self::containsItemsSpecificationForTraversable($typeNode->type, $phpcsFile, $pointer, $traversableTypeHints, true);
		}

		if ($typeNode instanceof IdentifierTypeNode) {
			if (TypeHintHelper::isTypeDefinedInAnnotation($phpcsFile, $pointer, $typeNode->name)) {
				// We can expect it's better type for traversable
				return true;
			}

			if (!$inTraversable) {
				return false;
			}

			return !TypeHintHelper::isTraversableType(
				TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $pointer, $typeNode->name),
				$traversableTypeHints
			);
		}

		if ($typeNode instanceof ConstTypeNode) {
			return $inTraversable;
		}

		if ($typeNode instanceof CallableTypeNode) {
			return $inTraversable;
		}

		if ($typeNode instanceof ArrayTypeNode) {
			return self::containsItemsSpecificationForTraversable($typeNode->type, $phpcsFile, $pointer, $traversableTypeHints, true);
		}

		if (
			$typeNode instanceof UnionTypeNode
			|| $typeNode instanceof IntersectionTypeNode
		) {
			foreach ($typeNode->types as $innerTypeNode) {
				if (
					!$inTraversable
					&& $innerTypeNode instanceof IdentifierTypeNode
					&& strtolower($innerTypeNode->name) === 'null'
				) {
					continue;
				}

				if (self::containsItemsSpecificationForTraversable(
					$innerTypeNode,
					$phpcsFile,
					$pointer,
					$traversableTypeHints,
					$inTraversable
				)) {
					return true;
				}
			}
		}

		if ($typeNode instanceof ConditionalTypeNode || $typeNode instanceof ConditionalTypeForParameterNode) {
			return
				self::containsItemsSpecificationForTraversable($typeNode->if, $phpcsFile, $pointer, $traversableTypeHints, $inTraversable)
				|| self::containsItemsSpecificationForTraversable(
					$typeNode->else,
					$phpcsFile,
					$pointer,
					$traversableTypeHints,
					$inTraversable
				);
		}

		return false;
	}

	/**
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 * @param bool $enableUnionTypeHint
	 */
	public static function getTypeHintFromOneType($typeNode, $enableUnionTypeHint = false): string
	{
		if ($typeNode instanceof GenericTypeNode) {
			return $typeNode->type->name;
		}

		if ($typeNode instanceof IdentifierTypeNode) {
			if (strtolower($typeNode->name) === 'true') {
				return 'bool';
			}

			if (strtolower($typeNode->name) === 'false') {
				return $enableUnionTypeHint ? 'false' : 'bool';
			}

			if (in_array(strtolower($typeNode->name), ['class-string', 'trait-string', 'callable-string', 'numeric-string'], true)) {
				return 'string';
			}

			return $typeNode->name;
		}

		if ($typeNode instanceof CallableTypeNode) {
			return $typeNode->identifier->name;
		}

		if ($typeNode instanceof ArrayTypeNode) {
			return 'array';
		}

		if ($typeNode instanceof ArrayShapeNode) {
			return 'array';
		}

		if ($typeNode instanceof ConstTypeNode) {
			if ($typeNode->constExpr instanceof ConstExprIntegerNode) {
				return 'int';
			}

			if ($typeNode->constExpr instanceof ConstExprFloatNode) {
				return 'float';
			}

			if ($typeNode->constExpr instanceof ConstExprStringNode) {
				return 'string';
			}
		}

		return (string) $typeNode;
	}

	/**
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 * @param array<int, string> $traversableTypeHints
	 * @return string[]
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 * @param bool $enableUnionTypeHint
	 */
	public static function getTraversableTypeHintsFromType(
		$typeNode,
		$phpcsFile,
		$pointer,
		$traversableTypeHints,
		$enableUnionTypeHint = false
	): array
	{
		$typeHints = [];

		foreach ($typeNode->types as $type) {
			if (
				$type instanceof GenericTypeNode
				|| $type instanceof ThisTypeNode
				|| $type instanceof IdentifierTypeNode
			) {
				$typeHints[] = self::getTypeHintFromOneType($type);
			}
		}

		if (!$enableUnionTypeHint && count($typeHints) > 1) {
			return [];
		}

		foreach ($typeHints as $typeHint) {
			if (!TypeHintHelper::isTraversableType(
				TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $pointer, $typeHint),
				$traversableTypeHints
			)) {
				return [];
			}
		}

		return $typeHints;
	}

	/**
	 * @param \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode
	 * @return \PHPStan\PhpDocParser\Ast\Type\TypeNode|null
	 */
	public static function getItemsSpecificationTypeFromType($typeNode)
	{
		foreach ($typeNode->types as $type) {
			if ($type instanceof ArrayTypeNode) {
				return $type;
			}
		}

		return null;
	}

}
