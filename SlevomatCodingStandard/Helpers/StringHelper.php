<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use function strlen;
use function strpos;
use function substr;

/**
 * @internal
 */
class StringHelper
{

	/**
	 * @param string $haystack
	 * @param string $needle
	 */
	public static function startsWith($haystack, $needle): bool
	{
		return $needle === '' || strpos($haystack, $needle) === 0;
	}

	/**
	 * @param string $haystack
	 * @param string $needle
	 */
	public static function endsWith($haystack, $needle): bool
	{
		return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
	}

}
