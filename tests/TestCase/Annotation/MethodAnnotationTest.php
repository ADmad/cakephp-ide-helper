<?php

namespace IdeHelper\Test\TestCase\Annotation;

use IdeHelper\Annotation\MethodAnnotation;
use IdeHelper\Annotation\PropertyAnnotation;
use Tools\TestSuite\TestCase;

/**
 */
class MethodAnnotationTest extends TestCase {

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
	}

	/**
	 * @return void
	 */
	public function testBuild() {
		$annotation = new MethodAnnotation('\\Foo\\Model\\Entity\\Bar', 'doSth()');

		$result = (string)$annotation;
		$this->assertSame('@method \\Foo\\Model\\Entity\\Bar doSth()', $result);
	}

	/**
	 * @return void
	 */
	public function testSetType() {
		$annotation = new MethodAnnotation('\\Foo\\Model\\Entity\\Bar', 'doSth()');
		$annotation->setType('\\Something\\Model\\Entity\\Else');

		$result = (string)$annotation;
		$this->assertSame('@method \\Something\\Model\\Entity\\Else doSth()', $result);
	}

	/**
	 * @return void
	 */
	public function testSetTypeComplex() {
		$annotation = new MethodAnnotation('\\Foo\\Model\\Entity\\Bar[]|bool', 'doSth()');
		$annotation->setType('\\Something\\Model\\Entity\\Else[]|bool');

		$result = (string)$annotation;
		$this->assertSame('@method \\Something\\Model\\Entity\\Else[]|bool doSth()', $result);
	}

	/**
	 * @return void
	 */
	public function testMatches() {
		$annotation = new MethodAnnotation('\\Foo\\Model\\Entity\\Bar', 'doSth()');
		$comparisonAnnotation = new MethodAnnotation('Something\\Else', 'doSth()');
		$result = $annotation->matches($comparisonAnnotation);
		$this->assertTrue($result);

		$annotation = new MethodAnnotation('\\Foo\\Model\\Entity\\Bar', 'doSth()');
		$comparisonAnnotation = new MethodAnnotation('Foo\\Model\\Entity\\Bar', 'sthElse()');
		$result = $annotation->matches($comparisonAnnotation);
		$this->assertFalse($result);

		$annotation = new MethodAnnotation('\\Foo\\Model\\Entity\\Bar', 'doSth()');
		$comparisonAnnotation = new PropertyAnnotation('Foo\\Model\\Entity\\Bar', 'doSth()');
		$result = $annotation->matches($comparisonAnnotation);
		$this->assertFalse($result);
	}

}
