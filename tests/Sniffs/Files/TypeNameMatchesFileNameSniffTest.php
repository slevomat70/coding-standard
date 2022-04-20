<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Files;

use SlevomatCodingStandard\Sniffs\TestCase;

class TypeNameMatchesFileNameSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testError()
	{
		$report = self::checkFile(__DIR__ . '/data/rootNamespace/boo.php', [
			'rootNamespaces' => ['tests/Sniffs/Files/data/rootNamespace' => 'RootNamespace'],
			'ignoredNamespaces' => ['IgnoredNamespace'],
		]);

		self::assertSame(1, $report->getErrorCount());
		self::assertSniffError($report, 5, TypeNameMatchesFileNameSniff::CODE_NO_MATCH_BETWEEN_TYPE_NAME_AND_FILE_NAME);

		$report = self::checkFile(__DIR__ . '/data/rootNamespace/coo/Foo.php', [
			'rootNamespaces' => ['tests/Sniffs/Files/data/rootNamespace' => 'RootNamespace'],
			'ignoredNamespaces' => ['IgnoredNamespace'],
		]);

		self::assertSame(1, $report->getErrorCount());
		self::assertSniffError($report, 5, TypeNameMatchesFileNameSniff::CODE_NO_MATCH_BETWEEN_TYPE_NAME_AND_FILE_NAME);
	}

	/**
	 * @return void
	 */
	public function testNoError()
	{
		$report = self::checkFile(__DIR__ . '/data/rootNamespace/Foo.php', [
			'rootNamespaces' => ['tests/Sniffs/Files/data/rootNamespace' => 'RootNamespace'],
			'ignoredNamespaces' => ['IgnoredNamespace'],
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testSkippedDir()
	{
		$report = self::checkFile(__DIR__ . '/data/rootNamespace/skippedDir/Bar.php', [
			'rootNamespaces' => ['tests/Sniffs/Files/data/rootNamespace' => 'RootNamespace'],
			'ignoredNamespaces' => ['IgnoredNamespace'],
			'skipDirs' => ['skippedDir'],
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testIgnoredNamespace()
	{
		$report = self::checkFile(__DIR__ . '/data/ignoredNamespace.php', [
			'ignoredNamespaces' => ['IgnoredNamespace'],
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNoNamespace()
	{
		$report = self::checkFile(__DIR__ . '/data/rootNamespace/noNamespace.php', [
			'rootNamespaces' => ['tests/Sniffs/Files/data/rootNamespace' => 'RootNamespace'],
		]);

		self::assertSame(1, $report->getErrorCount());
		self::assertSniffError($report, 3, TypeNameMatchesFileNameSniff::CODE_NO_MATCH_BETWEEN_TYPE_NAME_AND_FILE_NAME);
	}

	/**
	 * @return void
	 */
	public function testRootNamespacesNormalization()
	{
		$sniffProperties1 = [
			'rootNamespaces' => [
				'tests/Sniffs/Files/data/rootNamespace2/Xxx' => 'RootNamespace2',
				'tests/Sniffs/Files/data/rootNamespace2' => 'RootNamespace2',
				'tests/Sniffs/Files/data/rootNamespace' => 'RootNamespace',
			],
		];
		$sniffProperties2 = [
			'rootNamespaces' => [
				'tests/Sniffs/Files/data/rootNamespace' => 'RootNamespace',
				'tests/Sniffs/Files/data/rootNamespace2' => 'RootNamespace2',
				'tests/Sniffs/Files/data/rootNamespace2/Xxx' => 'RootNamespace2',
			],
		];

		self::assertNoSniffErrorInFile(self::checkFile(__DIR__ . '/data/rootNamespace2/Foo.php', $sniffProperties1));
		self::assertNoSniffErrorInFile(self::checkFile(__DIR__ . '/data/rootNamespace2/Xxx/Boo.php', $sniffProperties1));

		self::assertNoSniffErrorInFile(self::checkFile(__DIR__ . '/data/rootNamespace2/Foo.php', $sniffProperties2));
		self::assertNoSniffErrorInFile(self::checkFile(__DIR__ . '/data/rootNamespace2/Xxx/Boo.php', $sniffProperties2));
	}

	/**
	 * @return void
	 */
	public function testWithProvidedBasePathAndNestedSameDirName()
	{
		$report = self::checkFile(__DIR__ . '/data/data/Foo/Bar.php', [
			'rootNamespaces' => ['data' => 'Data'],
		], [], ['--basepath=' . __DIR__ . '/data']);
		self::assertNoSniffErrorInFile($report);
	}

}
