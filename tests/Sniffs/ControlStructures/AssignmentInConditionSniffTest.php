<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use SlevomatCodingStandard\Sniffs\TestCase;
use function range;

class AssignmentInConditionSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testCorrectFile()
	{
		$resultFile = self::checkFile(__DIR__ . '/data/noAssignmentsInConditions.php');
		self::assertNoSniffErrorInFile($resultFile);
	}

	/**
	 * @return void
	 */
	public function testIncorrectFile()
	{
		$resultFile = self::checkFile(__DIR__ . '/data/allAssignmentsInConditions.php');
		self::assertEquals(6, $resultFile->getErrorCount());
		foreach (range(3, 8) as $lineNumber) {
			self::assertSniffError($resultFile, $lineNumber, AssignmentInConditionSniff::CODE_ASSIGNMENT_IN_CONDITION);
		}
	}

	/**
	 * @return void
	 */
	public function testNoErrorsWithIgnoreAssignmentsInsideFunctionCalls()
	{
		$report = self::checkFile(
			__DIR__ . '/data/noAssignmentsInConditionsIgnoreAssignmentsInsideFunctionCalls.php',
			[
				'ignoreAssignmentsInsideFunctionCalls' => true,
			]
		);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrorsWithIgnoreAssignmentsInsideFunctionCalls()
	{
		$resultFile = self::checkFile(
			__DIR__ . '/data/allAssignmentsInConditions.php',
			[
				'ignoreAssignmentsInsideFunctionCalls' => true,
			]
		);

		self::assertEquals(7, $resultFile->getErrorCount());
		foreach (range(3, 8) as $lineNumber) {
			self::assertSniffError($resultFile, $lineNumber, AssignmentInConditionSniff::CODE_ASSIGNMENT_IN_CONDITION);
		}
	}

}
