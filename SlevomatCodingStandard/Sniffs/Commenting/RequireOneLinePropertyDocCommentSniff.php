<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\DocCommentHelper;
use SlevomatCodingStandard\Helpers\PropertyHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function sprintf;
use const T_VARIABLE;

class RequireOneLinePropertyDocCommentSniff extends AbstractRequireOneLineDocComment
{

	const CODE_MULTI_LINE_PROPERTY_COMMENT = 'MultiLinePropertyComment';

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param int $docCommentStartPointer
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return void
	 */
	public function process(File $phpcsFile, $docCommentStartPointer)
	{
		$propertyPointer = TokenHelper::findNext($phpcsFile, T_VARIABLE, $docCommentStartPointer + 1);
		if ($propertyPointer === null) {
			return;
		}

		// Not a property
		if (!PropertyHelper::isProperty($phpcsFile, $propertyPointer)) {
			return;
		}

		// Check that doc comment belongs to the found property
		$propertyDocCommentStartPointer = DocCommentHelper::findDocCommentOpenPointer($phpcsFile, $propertyPointer);
		if ($propertyDocCommentStartPointer !== $docCommentStartPointer) {
			return;
		}

		// Only validate properties without description
		if (DocCommentHelper::hasDocCommentDescription($phpcsFile, $propertyPointer)) {
			return;
		}

		parent::process($phpcsFile, $docCommentStartPointer);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $docCommentStartPointer
	 */
	protected function addError($phpcsFile, $docCommentStartPointer): bool
	{
		$error = 'Found multi-line comment for property %s with single line content, use one-line comment instead.';

		/** @var int $propertyPointer */
		$propertyPointer = $phpcsFile->findNext(T_VARIABLE, $docCommentStartPointer);

		return $phpcsFile->addFixableError(
			sprintf($error, PropertyHelper::getFullyQualifiedName($phpcsFile, $propertyPointer)),
			$docCommentStartPointer,
			self::CODE_MULTI_LINE_PROPERTY_COMMENT
		);
	}

}
