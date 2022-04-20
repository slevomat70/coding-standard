<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use SlevomatCodingStandard\Sniffs\TestCase;

class ReferenceUsedNamesOnlySniffTest extends TestCase
{

	/**
	 * @return mixed[][]
	 */
	public function dataIgnoredNamesForIrrelevantTests(): array
	{
		return [
			[
				[],
			],
			[
				['LibXMLError'],
			],
		];
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testDoNotReportNamespaceName($ignoredNames)
	{
		$report = self::checkFile(__DIR__ . '/data/shouldBeInUseStatement.php', [
			'ignoredNames' => $ignoredNames,
		]);
		self::assertNoSniffError($report, 3);
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testCreatingNewObjectViaNonFullyQualifiedName($ignoredNames)
	{
		$report = self::checkFile(__DIR__ . '/data/shouldBeInUseStatement.php', [
			'ignoredNames' => $ignoredNames,
		]);
		self::assertNoSniffError($report, 10);
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testCreatingNewObjectViaFullyQualifiedName($ignoredNames)
	{
		$report = self::checkFile(__DIR__ . '/data/shouldBeInUseStatement.php', [
			'ignoredNames' => $ignoredNames,
		]);
		self::assertSniffError($report, 12, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Foo\SomeError');
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testReferencingClassConstantViaFullyQualifiedName($ignoredNames)
	{
		$report = self::checkFile(__DIR__ . '/data/shouldBeInUseStatement.php', [
			'ignoredNames' => $ignoredNames,
		]);
		self::assertSniffError($report, 11, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Some\ConstantClass');
	}

	/**
	 * @return void
	 */
	public function testReferencingConstantViaFullyQualifiedName()
	{
		$report = self::checkFile(__DIR__ . '/data/shouldBeInUseStatement.php');
		self::assertSniffError($report, 16, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Boo\FOO');
	}

	/**
	 * @return void
	 */
	public function testReferencingFunctionViaFullyQualifiedName()
	{
		$report = self::checkFile(__DIR__ . '/data/shouldBeInUseStatement.php');
		self::assertSniffError($report, 17, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Boo\foo');
	}

	/**
	 * @return void
	 */
	public function testReferencingGlobalFunctionViaFallback()
	{
		$report = self::checkFile(__DIR__ . '/data/shouldBeInUseStatement.php', [
			'allowFallbackGlobalFunctions' => false,
		]);
		self::assertSniffError($report, 18, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FALLBACK_GLOBAL_NAME, 'min');
	}

	/**
	 * @return void
	 */
	public function testReferencingDefinedFunction()
	{
		$report = self::checkFile(__DIR__ . '/data/referencingDefinedFunction.php', [
			'allowFallbackGlobalFunctions' => false,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testReferencingGlobalConstantViaFallback()
	{
		$report = self::checkFile(__DIR__ . '/data/shouldBeInUseStatement.php', [
			'allowFallbackGlobalConstants' => false,
		]);
		self::assertSniffError($report, 19, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FALLBACK_GLOBAL_NAME, 'PHP_VERSION');
	}

	/**
	 * @return void
	 */
	public function testReferencingDefinedConstant()
	{
		$report = self::checkFile(__DIR__ . '/data/referencingDefinedConstant.php', [
			'allowFallbackGlobalConstants' => false,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testCreatingObjectFromSpecialExceptionName($ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/shouldBeInUseStatement.php',
			[
				'allowFullyQualifiedExceptions' => true,
				'specialExceptionNames' => [
					'Foo\SomeError',
				],
				'ignoredNames' => $ignoredNames,
			]
		);
		self::assertNoSniffError($report, 12);
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testReportFullyQualifiedInFileWithNamespace($ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/shouldBeInUseStatement.php',
			[
				'ignoredNames' => $ignoredNames,
			]
		);
		self::assertSniffError($report, 13, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Some\CommonException');
		self::assertSniffError($report, 14, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Exception');
		self::assertSniffError($report, 15, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Nette\ObjectPrototype');
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testDoNotAllowFullyQualifiedExtends($ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/fullyQualifiedExtends.php',
			[
				'ignoredNames' => $ignoredNames,
			]
		);
		self::assertSniffError($report, 3, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Lorem\Dolor');
		self::assertNoSniffError($report, 8);
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testDoNotAllowFullyQualifiedImplements($ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/fullyQualifiedImplements.php',
			[
				'ignoredNames' => $ignoredNames,
			]
		);
		self::assertSniffError(
			$report,
			3,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME_WITHOUT_NAMESPACE,
			'\SomeClass'
		);
		self::assertSniffError($report, 3, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Some\OtherClass');
		self::assertNoSniffError($report, 8);
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testAllowFullyQualifiedExceptions($ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/fullyQualifiedExceptionNames.php',
			[
				'allowFullyQualifiedExceptions' => true,
				'ignoredNames' => $ignoredNames,
			]
		);
		self::assertSame(1, $report->getErrorCount());
		self::assertSniffError($report, 28, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Foo\BarError');
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testDoNotAllowFullyQualifiedExceptionsInTypeHint($ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/fullyQualifiedExceptionNames.php',
			[
				'allowFullyQualifiedExceptions' => false,
				'ignoredNames' => $ignoredNames,
			]
		);
		self::assertSniffError($report, 16, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Some\Exception');
		self::assertNoSniffError($report, 3);
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testDoNotAllowFullyQualifiedExceptionsInThrow($ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/fullyQualifiedExceptionNames.php',
			[
				'allowFullyQualifiedExceptions' => false,
				'ignoredNames' => $ignoredNames,
			]
		);
		self::assertSniffError($report, 19, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Some\Other\Exception');
		self::assertNoSniffError($report, 6);
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testDoNotAllowFullyQualifiedExceptionsInCatch($ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/fullyQualifiedExceptionNames.php',
			[
				'allowFullyQualifiedExceptions' => false,
				'ignoredNames' => $ignoredNames,
			]
		);
		self::assertSniffError(
			$report,
			20,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'\Some\Other\DifferentException'
		);
		self::assertSniffError(
			$report,
			22,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME_WITHOUT_NAMESPACE,
			'\Throwable'
		);
		self::assertSniffError(
			$report,
			24,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME_WITHOUT_NAMESPACE,
			'\Exception'
		);
		self::assertSniffError(
			$report,
			26,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME_WITHOUT_NAMESPACE,
			'\TypeError'
		);
		self::assertSniffError($report, 28, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Foo\BarError');
		self::assertNoSniffError($report, 7);
		self::assertNoSniffError($report, 9);
		self::assertNoSniffError($report, 11);
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testDoNotAllowPartialUses($ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/partialUses.php',
			[
				'allowPartialUses' => false,
				'ignoredNames' => $ignoredNames,
			]
		);
		self::assertSniffError($report, 7, ReferenceUsedNamesOnlySniff::CODE_PARTIAL_USE, 'SomeFramework\ObjectPrototype');
		self::assertNoSniffError($report, 6);
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testAllowPartialUses($ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/partialUses.php',
			[
				'allowPartialUses' => true,
				'ignoredNames' => $ignoredNames,
			]
		);
		self::assertNoSniffError($report, 6);
		self::assertNoSniffError($report, 7);
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testUseOnlyWhitelistedNamespaces($ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/whitelistedNamespaces.php',
			[
				'namespacesRequiredToUse' => [
					'Foo',
				],
				'ignoredNames' => $ignoredNames,
			]
		);

		self::assertSniffError($report, 3, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Foo\Bar');
		self::assertNoSniffError($report, 4);
		self::assertNoSniffError($report, 5);
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testDisallowFullyQualifiedImplementsWithMultipleInterfaces($ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/multipleFullyQualifiedImplements.php',
			[
				'ignoredNames' => $ignoredNames,
			]
		);
		self::assertSniffError($report, 3, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME_WITHOUT_NAMESPACE, '\Bar');
		self::assertSniffError($report, 3, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME_WITHOUT_NAMESPACE, '\Baz');
		self::assertSniffError($report, 3, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Foo\Baz');
	}

	/**
	 * @dataProvider dataIgnoredNamesForIrrelevantTests
	 * @param string[] $ignoredNames
	 * @return void
	 */
	public function testDoNotUseTypeInRootNamespaceInFileWithoutNamespace($ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/referencingFullyQualifiedNameInFileWithoutNamespace.php',
			[
				'ignoredNames' => $ignoredNames,
			]
		);

		self::assertSniffError($report, 3, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME_WITHOUT_NAMESPACE, '\Foo');
		self::assertSniffError($report, 4, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, '\Bar\Lorem');
	}

	/**
	 * @return mixed[][]
	 */
	public function dataIgnoredNames(): array
	{
		return [
			[
				false,
				[],
			],
			[
				true,
				[
					'LibXMLError',
					'LibXMLException',
				],
			],
		];
	}

	/**
	 * @dataProvider dataIgnoredNames
	 * @param string[] $ignoredNames
	 * @param bool $allowFullyQualifiedExceptions
	 * @return void
	 */
	public function testIgnoredNames($allowFullyQualifiedExceptions, $ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/ignoredNames.php',
			[
				'allowFullyQualifiedExceptions' => $allowFullyQualifiedExceptions,
				'ignoredNames' => $ignoredNames,
			]
		);
		self::assertNoSniffError($report, 3);
		self::assertNoSniffError($report, 7);
		self::assertSniffError($report, 11, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME_WITHOUT_NAMESPACE);
		self::assertSniffError($report, 15, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME_WITHOUT_NAMESPACE);
	}

	/**
	 * @return void
	 */
	public function testIgnoredNamesWithAllowFullyQualifiedExceptions()
	{
		$report = self::checkFile(
			__DIR__ . '/data/ignoredNames.php',
			['allowFullyQualifiedExceptions' => true]
		);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return mixed[][]
	 */
	public function dataIgnoredNamesInNamespace(): array
	{
		return [
			[
				false,
				[],
			],
			[
				true,
				[
					'LibXMLError',
					'LibXMLException',
				],
			],
		];
	}

	/**
	 * @dataProvider dataIgnoredNamesInNamespace
	 * @param string[] $ignoredNames
	 * @param bool $allowFullyQualifiedExceptions
	 * @return void
	 */
	public function testIgnoredNamesInNamespace($allowFullyQualifiedExceptions, $ignoredNames)
	{
		$report = self::checkFile(
			__DIR__ . '/data/ignoredNamesInNamespace.php',
			[
				'allowFullyQualifiedExceptions' => $allowFullyQualifiedExceptions,
				'ignoredNames' => $ignoredNames,
			]
		);
		self::assertNoSniffError($report, 5);
		self::assertNoSniffError($report, 9);
		self::assertSniffError($report, 13, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);
		self::assertSniffError($report, 17, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);
	}

	/**
	 * @return void
	 */
	public function testIgnoredNamesWithAllowFullyQualifiedExceptionsInNamespace()
	{
		$report = self::checkFile(
			__DIR__ . '/data/ignoredNamesInNamespace.php',
			['allowFullyQualifiedExceptions' => true]
		);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAllowingFullyQualifiedGlobalClasses()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fullyQualifiedGlobalClassesInNamespace.php',
			[
				'allowFullyQualifiedGlobalClasses' => true,
				'allowFullyQualifiedGlobalFunctions' => false,
				'allowFullyQualifiedGlobalConstants' => false,
			]
		);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAllowingFullyQualifiedGlobalFunctions()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fullyQualifiedGlobalFunctionsInNamespace.php',
			[
				'allowFullyQualifiedGlobalClasses' => false,
				'allowFullyQualifiedGlobalFunctions' => true,
				'allowFullyQualifiedGlobalConstants' => false,
			]
		);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAllowingFullyQualifiedGlobalConstants()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fullyQualifiedGlobalConstantsInNamespace.php',
			[
				'allowFullyQualifiedGlobalClasses' => false,
				'allowFullyQualifiedGlobalFunctions' => false,
				'allowFullyQualifiedGlobalConstants' => true,
			]
		);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableReferenceViaFullyQualifiedOrGlobalFallbackName()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableReferenceViaFullyQualifiedName.php', [
			'allowFullyQualifiedExceptions' => true,
			'allowFallbackGlobalFunctions' => false,
			'allowFallbackGlobalConstants' => false,
		], [ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FALLBACK_GLOBAL_NAME]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNotFixableReferenceViaFullyQualifiedName()
	{
		$report = self::checkFile(
			__DIR__ . '/data/notFixableReferenceViaFullyQualifiedName.php',
			[],
			[ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME]
		);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testPartlyFixableReferenceViaFullyQualifiedName()
	{
		$report = self::checkFile(
			__DIR__ . '/data/partlyFixableReferenceViaFullyQualifiedName.php',
			[],
			[ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME]
		);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testFixableReferenceViaFullyQualifiedNameWithoutNamespace()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableReferenceViaFullyQualifiedNameWithoutNamespace.php', [
			'allowFullyQualifiedExceptions' => false,
			'specialExceptionNames' => [
				'BarErrorX',
			],
			'searchAnnotations' => true,
		], [ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME_WITHOUT_NAMESPACE]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testCollidingClassNameDifferentNamespacesAllowed()
	{
		$report = self::checkFile(
			__DIR__ . '/data/collidingClassNameDifferentNamespaces.php',
			['allowFullyQualifiedNameForCollidingClasses' => true]
		);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testCollidingClassNameDifferentNamespacesDisallowed()
	{
		$report = self::checkFile(
			__DIR__ . '/data/collidingClassNameDifferentNamespaces.php',
			['allowFullyQualifiedNameForCollidingClasses' => false]
		);

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError($report, 14, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);
		self::assertSniffError($report, 16, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);
	}

	/**
	 * @return void
	 */
	public function testCollidingClassNameDifferentNamespacesMoreClassesAllowed()
	{
		$report = self::checkFile(
			__DIR__ . '/data/collidingClassNameDifferentNamespacesMoreClasses.php',
			['allowFullyQualifiedNameForCollidingClasses' => true]
		);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testCollidingClassNameDifferentNamespacesMoreClassesDisallowed()
	{
		$report = self::checkFile(
			__DIR__ . '/data/collidingClassNameDifferentNamespacesMoreClasses.php',
			['allowFullyQualifiedNameForCollidingClasses' => false]
		);

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError($report, 7, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);
		self::assertSniffError($report, 9, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);
		self::assertSniffError($report, 12, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);
		self::assertSniffError($report, 14, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);
	}

	/**
	 * @return void
	 */
	public function testFixableWhenCollidingClassNames()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fixableWhenCollidingClassNames.php',
			[
				'searchAnnotations' => true,
				'allowFullyQualifiedNameForCollidingClasses' => true,
				'allowFullyQualifiedGlobalClasses' => true,
				'allowFullyQualifiedGlobalFunctions' => true,
				'allowFullyQualifiedGlobalConstants' => true,
			]
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testCollidingClassNameExtendsAllowed()
	{
		$report = self::checkFile(
			__DIR__ . '/data/collidingClassNameExtends.php',
			['allowFullyQualifiedNameForCollidingClasses' => true]
		);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testCollidingClassNameExtendsDisabled()
	{
		$report = self::checkFile(
			__DIR__ . '/data/collidingClassNameExtends.php',
			['allowFullyQualifiedNameForCollidingClasses' => false]
		);
		self::assertSame(1, $report->getErrorCount());
		self::assertSniffError($report, 5, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);
	}

	/**
	 * @return void
	 */
	public function testCollidingFullyQualifiedFunctionNameAllowed()
	{
		$report = self::checkFile(
			__DIR__ . '/data/collidingFullyQualifiedFunctionNames.php',
			['allowFullyQualifiedNameForCollidingFunctions' => true]
		);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testCollidingFullyQualifiedFunctionNameDisallowed()
	{
		$report = self::checkFile(
			__DIR__ . '/data/collidingFullyQualifiedFunctionNames.php',
			['allowFullyQualifiedNameForCollidingFunctions' => false]
		);

		self::assertSame(1, $report->getErrorCount());
		self::assertSniffError($report, 15, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);
	}

	/**
	 * @return void
	 */
	public function testCollidingFullyQualifiedConstantNameAllowed()
	{
		$report = self::checkFile(
			__DIR__ . '/data/collidingFullyQualifiedConstantNames.php',
			['allowFullyQualifiedNameForCollidingConstants' => true]
		);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testCollidingFullyQualifiedConstantNameDisallowed()
	{
		$report = self::checkFile(
			__DIR__ . '/data/collidingFullyQualifiedConstantNames.php',
			['allowFullyQualifiedNameForCollidingConstants' => false]
		);

		self::assertSame(1, $report->getErrorCount());
		self::assertSniffError($report, 12, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);
	}

	/**
	 * @return void
	 */
	public function testReferencingGlobalFunctionViaFallbackErrorsWithMoreComplexSettings()
	{
		$report = self::checkFile(
			__DIR__ . '/data/referencingGlobalFunctionViaFallbackErrorsWithMoreComplexSettings.php',
			[
				'allowFallbackGlobalFunctions' => false,
				'allowFullyQualifiedNameForCollidingFunctions' => true,
				'allowFullyQualifiedGlobalFunctions' => true,
			]
		);

		self::assertSame(1, $report->getErrorCount());
		self::assertSniffError($report, 17, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FALLBACK_GLOBAL_NAME);
	}

	/**
	 * @return void
	 */
	public function testReferencingGlobalFunctionViaFullyQualifiedWithMoreComplexSettings()
	{
		$report = self::checkFile(
			__DIR__ . '/data/referencingGlobalFunctionViaFullyQualifiedWithMoreComplexSettings.php',
			[
				'allowFallbackGlobalFunctions' => false,
				'allowFullyQualifiedNameForCollidingFunctions' => true,
				'allowFullyQualifiedGlobalFunctions' => false,
			]
		);

		self::assertSame(1, $report->getErrorCount());
		self::assertSniffError($report, 13, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);
	}

	/**
	 * @return void
	 */
	public function testSearchingInAnnotationsDisabled()
	{
		$report = self::checkFile(
			__DIR__ . '/data/shouldBeInUseStatementSearchingInAnnotations.php',
			[
				'searchAnnotations' => false,
			]
		);

		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testSearchingInAnnotations()
	{
		$report = self::checkFile(
			__DIR__ . '/data/shouldBeInUseStatementSearchingInAnnotations.php',
			[
				'searchAnnotations' => true,
				'allowPartialUses' => false,
				'allowFullyQualifiedGlobalClasses' => true,
				'allowFullyQualifiedNameForCollidingClasses' => true,
				'allowFullyQualifiedExceptions' => false,
				'namespacesRequiredToUse' => ['Foo'],
			]
		);

		self::assertSame(44, $report->getErrorCount());

		self::assertSniffError(
			$report,
			9,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			10,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			15,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			19,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			34,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\ArrayObject should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			36,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Something should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			37,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Exception should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			41,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Traversable should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			47,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Something should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			54,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Something should not be referenced via a fully qualified name, but via a use statement.'
		);

		self::assertSniffError(
			$report,
			43,
			ReferenceUsedNamesOnlySniff::CODE_PARTIAL_USE,
			'Partial use statements are not allowed, but referencing BlaBla\Foo found.'
		);

		self::assertSniffError(
			$report,
			70,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			75,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			78,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\ArrayObject should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			81,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			84,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			87,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			90,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			93,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\ArrayObject should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			93,
			ReferenceUsedNamesOnlySniff::CODE_PARTIAL_USE,
			'Partial use statements are not allowed, but referencing BlaBla\Foo found.'
		);
		self::assertSniffError(
			$report,
			93,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\ArrayObject should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			96,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);

		self::assertSniffError(
			$report,
			105,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\ArrayObject should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			105,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);

		self::assertSniffError(
			$report,
			114,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			117,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);

		self::assertSniffError(
			$report,
			121,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			122,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\DateTime should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			122,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\ArrayObject should not be referenced via a fully qualified name, but via a use statement.'
		);

		self::assertSniffError(
			$report,
			132,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Test\Bla\Whatever should not be referenced via a fully qualified name, but via a use statement.'
		);

		self::assertNoSniffError($report, 138);

		self::assertSniffError(
			$report,
			145,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Anything should not be referenced via a fully qualified name, but via a use statement.'
		);

		self::assertSniffError(
			$report,
			153,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Conditional1 should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			153,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Conditional2 should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			153,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Conditional3 should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			153,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Conditional4 should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			153,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Conditional5 should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			153,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Conditional6 should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			153,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Conditional7 should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			160,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Conditional8 should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			160,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Conditional9 should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			160,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Foo\Conditional10 should not be referenced via a fully qualified name, but via a use statement.'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testReferencingGlobalTypesInGlobalNamespace()
	{
		$report = self::checkFile(
			__DIR__ . '/data/referencingGlobalTypesInGlobalNamespace.php',
			[
				'allowFallbackGlobalFunctions' => false,
				'allowFallbackGlobalConstants' => false,
			]
		);

		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWithFileComment()
	{
		$report = self::checkFile(__DIR__ . '/data/referenceUsedNamesOnlyWithFileComment.php');

		self::assertSniffError($report, 11, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWithFileCommentAndDeclare()
	{
		$report = self::checkFile(__DIR__ . '/data/referenceUsedNamesOnlyWithFileCommentAndDeclare.php');

		self::assertSniffError($report, 13, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWithFileCommentBelowDeclare()
	{
		$report = self::checkFile(__DIR__ . '/data/referenceUsedNamesOnlyWithFileCommentBelowDeclare.php');

		self::assertSniffError($report, 14, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWithInlineFileComment()
	{
		$report = self::checkFile(__DIR__ . '/data/referenceUsedNamesOnlyWithInlineFileComment.php');

		self::assertSniffError($report, 9, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWithClassComment()
	{
		$report = self::checkFile(__DIR__ . '/data/referenceUsedNamesOnlyWithClassComment.php');

		self::assertSniffError($report, 11, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWithCollision()
	{
		$report = self::checkFile(__DIR__ . '/data/referenceUsedNamesOnlyWithCollision.php');

		self::assertSniffError($report, 7, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAttributes()
	{
		$report = self::checkFile(__DIR__ . '/data/referenceUsedNamesOnlyInAttributes.php', [
			'allowFallbackGlobalConstants' => false,
		]);

		self::assertSame(5, $report->getErrorCount());

		self::assertSniffError(
			$report,
			8,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Whatever\Anything should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			8,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Constant \PHP_VERSION should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			12,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Doctrine\Column should not be referenced via a fully qualified name, but via a use statement.'
		);
		self::assertSniffError(
			$report,
			18,
			ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
			'Class \Nette\DI\Attributes\Inject should not be referenced via a fully qualified name, but via a use statement.'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWithSubNamespaces()
	{
		$report = self::checkFile(__DIR__ . '/data/referenceUsedNamesOnlyWithSubNamespaces.php', [
			'searchAnnotations' => true,
			'allowPartialUses' => true,
		]);

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 12, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);
		self::assertSniffError($report, 17, ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME);

		self::assertAllFixedInFile($report);
	}

}
