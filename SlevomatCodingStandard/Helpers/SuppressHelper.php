<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\Annotation\Annotation;
use function array_reduce;
use function assert;
use function sprintf;
use function strpos;
use const T_DOC_COMMENT_CLOSE_TAG;
use const T_DOC_COMMENT_STAR;

/**
 * @internal
 */
class SuppressHelper
{

	const ANNOTATION = '@phpcsSuppress';

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 * @param string $suppressName
	 */
	public static function isSniffSuppressed($phpcsFile, $pointer, $suppressName): bool
	{
		return array_reduce(
			AnnotationHelper::getAnnotationsByName($phpcsFile, $pointer, self::ANNOTATION),
			static function (bool $carry, Annotation $annotation) use ($suppressName): bool {
				if (
					$annotation->getContent() === $suppressName
					|| strpos($suppressName, sprintf('%s.', $annotation->getContent())) === 0
				) {
					$carry = true;
				}
				return $carry;
			},
			false
		);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 * @param string $suppressName
	 * @return void
	 */
	public static function removeSuppressAnnotation($phpcsFile, $pointer, $suppressName)
	{
		$suppressAnnotation = null;
		foreach (AnnotationHelper::getAnnotationsByName($phpcsFile, $pointer, self::ANNOTATION) as $annotation) {
			if ($annotation->getContent() === $suppressName) {
				$suppressAnnotation = $annotation;
				break;
			}
		}

		assert($suppressAnnotation !== null);

		/** @var int $changeStart */
		$changeStart = TokenHelper::findPrevious($phpcsFile, T_DOC_COMMENT_STAR, $suppressAnnotation->getStartPointer() - 1);
		/** @var int $changeEnd */
		$changeEnd = TokenHelper::findNext(
			$phpcsFile,
			[T_DOC_COMMENT_CLOSE_TAG, T_DOC_COMMENT_STAR],
			$suppressAnnotation->getEndPointer() + 1
		) - 1;
		$phpcsFile->fixer->beginChangeset();
		for ($i = $changeStart; $i <= $changeEnd; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		$phpcsFile->fixer->endChangeset();
	}

}
