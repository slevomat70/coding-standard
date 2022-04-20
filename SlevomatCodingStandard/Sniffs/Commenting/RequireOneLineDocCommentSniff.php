<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;

class RequireOneLineDocCommentSniff extends AbstractRequireOneLineDocComment
{

	const CODE_MULTI_LINE_DOC_COMMENT = 'MultiLineDocComment';

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $docCommentStartPointer
	 */
	protected function addError($phpcsFile, $docCommentStartPointer): bool
	{
		$error = 'Found multi-line doc comment with single line content, use one-line doc comment instead.';

		return $phpcsFile->addFixableError($error, $docCommentStartPointer, self::CODE_MULTI_LINE_DOC_COMMENT);
	}

}
