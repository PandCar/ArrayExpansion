<?php

use PHPUnit\Framework\TestCase;

class ArrayExpansionTest extends TestCase
{
	public function testArrayGet()
	{
		$arr = [
			'foo' => 'bar',
		];

		$this->assertIsString(ArrayExpansion::array_get($arr, 'foo'));

		$this->assertNull(ArrayExpansion::array_get($arr, 'bar'));

		$this->assertEquals(1000, ArrayExpansion::array_get($arr, 'bar', 1000));
	}
}