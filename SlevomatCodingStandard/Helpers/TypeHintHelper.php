<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\Annotation\TemplateAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\TypeAliasAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\TypeImportAnnotation;
use function array_key_exists;
use function array_map;
use function array_merge;
use function array_unique;
use function count;
use function explode;
use function implode;
use function in_array;
use function sort;
use function sprintf;
use function substr;
use const T_FUNCTION;
use const T_WHITESPACE;

/**
 * @internal
 */
class TypeHintHelper
{

	/**
	 * @param string $typeHint
	 * @param bool $enableObjectTypeHint
	 * @param bool $enableStaticTypeHint
	 * @param bool $enableMixedTypeHint
	 */
	public static function isValidTypeHint(
		$typeHint,
		$enableObjectTypeHint,
		$enableStaticTypeHint,
		$enableMixedTypeHint
	): bool
	{
		if (self::isSimpleTypeHint($typeHint)) {
			return true;
		}

		if ($typeHint === 'object') {
			return $enableObjectTypeHint;
		}

		if ($typeHint === 'static') {
			return $enableStaticTypeHint;
		}

		if ($typeHint === 'mixed') {
			return $enableMixedTypeHint;
		}

		return !self::isSimpleUnofficialTypeHints($typeHint);
	}

	/**
	 * @param string $typeHint
	 */
	public static function isSimpleTypeHint($typeHint): bool
	{
		return in_array($typeHint, self::getSimpleTypeHints(), true);
	}

	/**
	 * @param string $typeHint
	 */
	public static function isSimpleIterableTypeHint($typeHint): bool
	{
		return in_array($typeHint, self::getSimpleIterableTypeHints(), true);
	}

	/**
	 * @param string $typeHint
	 */
	public static function convertLongSimpleTypeHintToShort($typeHint): string
	{
		$longToShort = [
			'integer' => 'int',
			'boolean' => 'bool',
		];
		return array_key_exists($typeHint, $longToShort) ? $longToShort[$typeHint] : $typeHint;
	}

	/**
	 * @param string $typeHint
	 */
	public static function isUnofficialUnionTypeHint($typeHint): bool
	{
		return in_array($typeHint, ['scalar', 'numeric'], true);
	}

	/**
	 * @param string $typeHint
	 */
	public static function isVoidTypeHint($typeHint): bool
	{
		return in_array($typeHint, ['void', 'never', 'never-return', 'never-returns', 'no-return'], true);
	}

	/**
	 * @return string[]
	 * @param string $typeHint
	 */
	public static function convertUnofficialUnionTypeHintToOfficialTypeHints($typeHint): array
	{
		$conversion = [
			'scalar' => ['string', 'int', 'float', 'bool'],
			'numeric' => ['int', 'float'],
		];

		return $conversion[$typeHint];
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 * @param string $typeHint
	 */
	public static function isTypeDefinedInAnnotation($phpcsFile, $pointer, $typeHint): bool
	{
		/** @var int $docCommentOpenPointer */
		$docCommentOpenPointer = DocCommentHelper::findDocCommentOpenPointer($phpcsFile, $pointer);
		return self::isTemplate($phpcsFile, $docCommentOpenPointer, $typeHint)
			|| self::isAlias($phpcsFile, $docCommentOpenPointer, $typeHint);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 * @param string $typeHint
	 */
	public static function getFullyQualifiedTypeHint($phpcsFile, $pointer, $typeHint): string
	{
		if (self::isSimpleTypeHint($typeHint)) {
			return self::convertLongSimpleTypeHintToShort($typeHint);
		}

		return NamespaceHelper::resolveClassName($phpcsFile, $typeHint, $pointer);
	}

	/**
	 * @return string[]
	 */
	public static function getSimpleTypeHints(): array
	{
		static $simpleTypeHints;

		if ($simpleTypeHints === null) {
			$simpleTypeHints = [
				'int',
				'integer',
				'float',
				'string',
				'bool',
				'boolean',
				'callable',
				'self',
				'array',
				'iterable',
				'void',
			];
		}

		return $simpleTypeHints;
	}

	/**
	 * @return string[]
	 */
	public static function getSimpleIterableTypeHints(): array
	{
		return [
			'array',
			'iterable',
		];
	}

	/**
	 * @param string $typeHint
	 */
	public static function isSimpleUnofficialTypeHints($typeHint): bool
	{
		static $simpleUnofficialTypeHints;

		if ($simpleUnofficialTypeHints === null) {
			$simpleUnofficialTypeHints = [
				'null',
				'mixed',
				'scalar',
				'numeric',
				'true',
				'false',
				'object',
				'resource',
				'static',
				'$this',
				'class-string',
				'trait-string',
				'callable-string',
				'numeric-string',
				'array-key',
				'list',
				'empty',
			];
		}

		return in_array($typeHint, $simpleUnofficialTypeHints, true);
	}

	/**
	 * @param string[] $traversableTypeHints
	 * @param string $type
	 */
	public static function isTraversableType($type, $traversableTypeHints): bool
	{
		return self::isSimpleIterableTypeHint($type) || in_array($type, $traversableTypeHints, true);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $functionPointer
	 * @param string $typeHint
	 * @param string $typeHintInAnnotation
	 */
	public static function typeHintEqualsAnnotation(
		$phpcsFile,
		$functionPointer,
		$typeHint,
		$typeHintInAnnotation
	): bool
	{
		$typeHintParts = explode('|', self::normalize($typeHint));
		$typeHintInAnnotationParts = explode('|', self::normalize($typeHintInAnnotation));

		if (count($typeHintParts) !== count($typeHintInAnnotationParts)) {
			return false;
		}

		for ($i = 0; $i < count($typeHintParts); $i++) {
			if (self::getFullyQualifiedTypeHint($phpcsFile, $functionPointer, $typeHintParts[$i]) !== self::getFullyQualifiedTypeHint(
				$phpcsFile,
				$functionPointer,
				$typeHintInAnnotationParts[$i]
			)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $endPointer
	 */
	public static function getStartPointer($phpcsFile, $endPointer): int
	{
		$previousPointer = TokenHelper::findPreviousExcluding(
			$phpcsFile,
			array_merge([T_WHITESPACE], TokenHelper::getTypeHintTokenCodes()),
			$endPointer - 1
		);
		return TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $previousPointer + 1);
	}

	private static function isTemplate(File $phpcsFile, int $docCommentOpenPointer, string $typeHint): bool
	{
		static $templateAnnotationNames = null;
		if ($templateAnnotationNames === null) {
			foreach (['template', 'template-covariant'] as $annotationName) {
				$templateAnnotationNames[] = sprintf('@%s', $annotationName);
				foreach (AnnotationHelper::PREFIXES as $prefixAnnotationName) {
					$templateAnnotationNames[] = sprintf('@%s-%s', $prefixAnnotationName, $annotationName);
				}
			}
		}

		$containsTypeHintInTemplateAnnotation = static function (int $docCommentOpenPointer) use ($phpcsFile, $templateAnnotationNames, $typeHint): bool {
			$annotations = AnnotationHelper::getAnnotations($phpcsFile, $docCommentOpenPointer);
			foreach ($templateAnnotationNames as $templateAnnotationName) {
				if (!array_key_exists($templateAnnotationName, $annotations)) {
					continue;
				}

				/** @var TemplateAnnotation $templateAnnotation */
				foreach ($annotations[$templateAnnotationName] as $templateAnnotation) {
					if ($templateAnnotation->isInvalid()) {
						continue;
					}

					if ($templateAnnotation->getTemplateName() === $typeHint) {
						return true;
					}
				}
			}

			return false;
		};

		$tokens = $phpcsFile->getTokens();

		$docCommentOwnerPointer = TokenHelper::findNext(
			$phpcsFile,
			array_merge([T_FUNCTION], TokenHelper::$typeKeywordTokenCodes),
			$tokens[$docCommentOpenPointer]['comment_closer'] + 1
		);
		if (
			$docCommentOwnerPointer !== null
			&& $tokens[$tokens[$docCommentOpenPointer]['comment_closer']]['line'] + 1 === $tokens[$docCommentOwnerPointer]['line']
		) {
			if ($containsTypeHintInTemplateAnnotation($docCommentOpenPointer)) {
				return true;
			}

			if ($tokens[$docCommentOwnerPointer]['code'] !== T_FUNCTION) {
				return false;
			}
		} else {
			$docCommentOwnerPointer = null;
		}

		$pointerToFindClass = $docCommentOpenPointer;
		if ($docCommentOwnerPointer === null) {
			$functionPointer = TokenHelper::findPrevious($phpcsFile, T_FUNCTION, $docCommentOpenPointer - 1);
			if ($functionPointer !== null) {
				$pointerToFindClass = $functionPointer;
			}
		}

		$classPointer = ClassHelper::getClassPointer($phpcsFile, $pointerToFindClass);

		if ($classPointer === null) {
			return false;
		}

		$classDocCommentOpenPointer = DocCommentHelper::findDocCommentOpenPointer($phpcsFile, $classPointer);
		if ($classDocCommentOpenPointer === null) {
			return false;
		}

		return $containsTypeHintInTemplateAnnotation($classDocCommentOpenPointer);
	}

	private static function isAlias(File $phpcsFile, int $docCommentOpenPointer, string $typeHint): bool
	{
		static $aliasAnnotationNames = null;
		if ($aliasAnnotationNames === null) {
			foreach (['type', 'import-type'] as $annotationName) {
				foreach (AnnotationHelper::PREFIXES as $prefixAnnotationName) {
					$aliasAnnotationNames[] = sprintf('@%s-%s', $prefixAnnotationName, $annotationName);
				}
			}
		}

		$classPointer = ClassHelper::getClassPointer($phpcsFile, $docCommentOpenPointer);

		if ($classPointer === null) {
			return false;
		}

		$classDocCommentOpenPointer = DocCommentHelper::findDocCommentOpenPointer($phpcsFile, $classPointer);
		if ($classDocCommentOpenPointer === null) {
			return false;
		}

		$annotations = AnnotationHelper::getAnnotations($phpcsFile, $classDocCommentOpenPointer);
		foreach ($aliasAnnotationNames as $aliasAnnotationName) {
			if (!array_key_exists($aliasAnnotationName, $annotations)) {
				continue;
			}

			/** @var TypeAliasAnnotation|TypeImportAnnotation $aliasAnnotation */
			foreach ($annotations[$aliasAnnotationName] as $aliasAnnotation) {
				if ($aliasAnnotation->isInvalid()) {
					continue;
				}

				if ($aliasAnnotation->getAlias() === $typeHint) {
					return true;
				}
			}
		}

		return false;
	}

	private static function normalize(string $typeHint): string
	{
		if (StringHelper::startsWith($typeHint, '?')) {
			$typeHint = substr($typeHint, 1) . '|null';
		}

		$parts = explode('|', $typeHint);

		if (in_array('mixed', $parts, true)) {
			return 'mixed';
		}

		$convertedParts = [];
		foreach ($parts as $part) {
			if (self::isUnofficialUnionTypeHint($part)) {
				$convertedParts = array_merge($convertedParts, self::convertUnofficialUnionTypeHintToOfficialTypeHints($part));
			} else {
				$convertedParts[] = $part;
			}
		}

		$convertedParts = array_unique($convertedParts);

		if (count($convertedParts) > 1) {
			$convertedParts = array_map(static function (string $part): string {
				return TypeHintHelper::isVoidTypeHint($part) ? 'null' : $part;
			}, $convertedParts);
		}

		sort($convertedParts);

		return implode('|', $convertedParts);
	}

}
