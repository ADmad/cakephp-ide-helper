<?php

namespace IdeHelper\Test\TestCase\Generator\Task;

use IdeHelper\Generator\Task\ElementTask;
use Tools\TestSuite\TestCase;
use Tools\TestSuite\ToolsTestTrait;

class ElementTaskTest extends TestCase {

	use ToolsTestTrait;

	/**
	 * @var \IdeHelper\Generator\Task\ElementTask
	 */
	protected $task;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->task = new ElementTask();
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testCollect() {
		$result = $this->task->collect();

		$this->assertCount(1, $result);

		/** @var \IdeHelper\Generator\Directive\Override $directive */
		$directive = array_shift($result);
		$this->assertSame('\Cake\View\View::element(0)', $directive->toArray()['method']);

		$expectedMap = [
			'deeply/nested' => '\Cake\View\View::class',
			'example' => '\Cake\View\View::class'
		];
		$this->assertSame($expectedMap, $directive->toArray()['map']);
	}

}
