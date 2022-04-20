<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;

class SuppressHelperTest extends TestCase
{

	const CHECK_NAME = 'Sniff.Sniff.Sniff.check';

	/** @var File */
	private $testedCodeSnifferFile;

	/**
	 * @return void
	 */
	public function testClassIsSuppressed()
	{
		self::assertTrue(
			SuppressHelper::isSniffSuppressed(
				$this->getTestedCodeSnifferFile(),
				$this->findClassPointerByName($this->getTestedCodeSnifferFile(), 'IsSuppressed'),
				self::CHECK_NAME
			)
		);
	}

	/**
	 * @return void
	 */
	public function testClassIsNotSuppressed()
	{
		self::assertFalse(
			SuppressHelper::isSniffSuppressed(
				$this->getTestedCodeSnifferFile(),
				$this->findClassPointerByName($this->getTestedCodeSnifferFile(), 'IsNotSuppressed'),
				self::CHECK_NAME
			)
		);
	}

	/**
	 * @return void
	 */
	public function testConstantIsSuppressed()
	{
		self::assertTrue(
			SuppressHelper::isSniffSuppressed(
				$this->getTestedCodeSnifferFile(),
				$this->findConstantPointerByName($this->getTestedCodeSnifferFile(), 'IS_SUPPRESSED'),
				self::CHECK_NAME
			)
		);
	}

	/**
	 * @return void
	 */
	public function testConstantIsNotSuppressed()
	{
		self::assertFalse(
			SuppressHelper::isSniffSuppressed(
				$this->getTestedCodeSnifferFile(),
				$this->findConstantPointerByName($this->getTestedCodeSnifferFile(), 'IS_NOT_SUPPRESSED'),
				self::CHECK_NAME
			)
		);
	}

	/**
	 * @return void
	 */
	public function testPropertyIsSuppressed()
	{
		self::assertTrue(
			SuppressHelper::isSniffSuppressed(
				$this->getTestedCodeSnifferFile(),
				$this->findPropertyPointerByName($this->getTestedCodeSnifferFile(), 'isSuppressed'),
				self::CHECK_NAME
			)
		);
	}

	/**
	 * @return void
	 */
	public function testPropertyIsNotSuppressed()
	{
		self::assertFalse(
			SuppressHelper::isSniffSuppressed(
				$this->getTestedCodeSnifferFile(),
				$this->findPropertyPointerByName($this->getTestedCodeSnifferFile(), 'isNotSuppressed'),
				self::CHECK_NAME
			)
		);
	}

	/**
	 * @return string[][]
	 */
	public function dataFunctionIsSuppressed(): array
	{
		return [
			['suppressWithFullName'],
			['suppressWithPartialName'],
			['suppressWithFullDocComment'],
		];
	}

	/**
	 * @dataProvider dataFunctionIsSuppressed
	 * @param string $name
	 * @return void
	 */
	public function testFunctionIsSuppressed($name)
	{
		self::assertTrue(
			SuppressHelper::isSniffSuppressed(
				$this->getTestedCodeSnifferFile(),
				$this->findFunctionPointerByName($this->getTestedCodeSnifferFile(), $name),
				self::CHECK_NAME
			)
		);
	}

	/**
	 * @return string[][]
	 */
	public function dataFunctionIsNotSuppressed(): array
	{
		return [
			['noDocComment'],
			['docCommentWithoutSuppress'],
			['invalidSuppress'],
		];
	}

	/**
	 * @dataProvider dataFunctionIsNotSuppressed
	 * @param string $name
	 * @return void
	 */
	public function testFunctionIsNotSuppressed($name)
	{
		self::assertFalse(
			SuppressHelper::isSniffSuppressed(
				$this->getTestedCodeSnifferFile(),
				$this->findFunctionPointerByName($this->getTestedCodeSnifferFile(), $name),
				self::CHECK_NAME
			)
		);
	}

	private function getTestedCodeSnifferFile(): File
	{
		if ($this->testedCodeSnifferFile === null) {
			$this->testedCodeSnifferFile = $this->getCodeSnifferFile(__DIR__ . '/data/suppress.php');
		}
		return $this->testedCodeSnifferFile;
	}

}
