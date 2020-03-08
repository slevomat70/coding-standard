<?php // lint >= 7.4

abstract class Foo {
	public static $staticFoo = 'bar';
	protected  static $staticBar = 'foo';
	private static $staticLvl = 666;
	public $foo = 'bar'; // there may be a comment
	protected  $bar = 'foo';
	private $lvl = 9001;
	private int $int = 0;

	private const CONSTANT = true;

	public static abstract function wow();
	private function such()
	{
	}
}

abstract class Bar {
	/** @var string */
	public static $staticFoo = 'bar'; // there may be a comment

	/** @var string */
	protected  static $staticBar = 'foo';

	/** @var int */
	private static $staticLvl = 666;

	// strange but yeah, whatever
	public $foo = 'bar';

	/** @var string */
	protected  $bar = 'foo';

	/** @var int */
	private $lvl = 9001;
	/**
	 * whatever
	 */
	public static abstract function wow();
	/**
	 * who cares
	 */
	private function such()
	{
	}

	public function testAnonymousClass(): void
	{
		$enum = new class() extends Enum {
			public const FOO = 'foo';
		};

		/** @var Enum $class */
		$class = get_class($enum);

		$enum2 = new class() extends Enum {
			public const FOO = 'foo';
		};

		/** @var Enum $class */
		$class2 = get_class($enum2);
	}

}
