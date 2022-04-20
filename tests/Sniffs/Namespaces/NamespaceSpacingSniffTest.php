<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use SlevomatCodingStandard\Sniffs\TestCase;

class NamespaceSpacingSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoNamespaceNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingNoNamespace.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNamespaceWithCurlyBraces()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingNamespaceWithCurlyBraces.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDefaultSettingsNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingWithDefaultSettingsNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDefaultSettingsErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingWithDefaultSettingsErrors.php');

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError($report, 2, NamespaceSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_NAMESPACE);
		self::assertSniffError($report, 2, NamespaceSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_NAMESPACE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAfterOpenTagNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingAfterOpenTagNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAfterOpenTagErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingAfterOpenTagErrors.php');

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 2, NamespaceSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_NAMESPACE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAfterLineCommentNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingAfterLineCommentNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAfterLineCommentErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingAfterLineCommentErrors.php');

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 4, NamespaceSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_NAMESPACE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testModifiedSettingsNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingWithModifiedSettingsNoErrors.php', [
			'linesCountBeforeNamespace' => 0,
			'linesCountAfterNamespace' => 2,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testModifiedSettingsErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingWithModifiedSettingsErrors.php', [
			'linesCountBeforeNamespace' => 0,
			'linesCountAfterNamespace' => 2,
		]);

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError($report, 4, NamespaceSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_NAMESPACE);
		self::assertSniffError($report, 4, NamespaceSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_NAMESPACE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNoLineBeforeNamespace()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingNoLineBeforeNamespace.php');

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 3, NamespaceSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_NAMESPACE);
	}

	/**
	 * @return void
	 */
	public function testNoLineAfterNamespace()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingNoLineAfterNamespace.php');

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 3, NamespaceSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_NAMESPACE);
	}

	/**
	 * @return void
	 */
	public function testNoCodeAfterNamespace()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingNoCodeAfterNamespace.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testPhpcsCommentBefore()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingWithPhpcsCommentBefore.php');

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 4, NamespaceSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_NAMESPACE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFileCommentBefore()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingFileCommentBefore.php', [
			'linesCountBeforeNamespace' => 2,
		]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 6, NamespaceSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_NAMESPACE);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testInvalidFileCommentBeforeNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingInvalidFileCommentBeforeNoErrors.php', [
			'linesCountBeforeNamespace' => 1,
		]);

		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testInvalidFileCommentBefore()
	{
		$report = self::checkFile(__DIR__ . '/data/namespaceSpacingInvalidFileCommentBefore.php', [
			'linesCountBeforeNamespace' => 2,
		]);

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 6, NamespaceSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_NAMESPACE);

		self::assertAllFixedInFile($report);
	}

}
